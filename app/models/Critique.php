<?php
/**
 * Critique.php — Modèle critique
 */

require_once __DIR__ . '/../config/database.php';

class Critique
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // ── Lecture ─────────────────────────────────────────────

    /**
     * Retourne toutes les critiques avec auteur, nombre de likes et catégories.
     * Les critiques épinglées apparaissent en premier.
     */
    public function findAll(?int $categorieId = null, ?string $search = null): array
    {
        $where  = [];
        $params = [];

        if ($categorieId !== null) {
            $where[]             = 'EXISTS (
                SELECT 1 FROM critique_categorie cc
                WHERE cc.id_critique = c.id AND cc.id_categorie = :cat_id
            )';
            $params[':cat_id'] = $categorieId;
        }

        if ($search !== null && $search !== '') {
            $where[]          = '(c.titre LIKE :search OR c.contenu LIKE :search2)';
            $params[':search']  = '%' . $search . '%';
            $params[':search2'] = '%' . $search . '%';
        }

        $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "
            SELECT
                c.id,
                c.titre,
                c.contenu,
                c.note,
                c.date_creation,
                c.epingle,
                c.id_user,
                u.pseudo AS auteur,
                (SELECT COUNT(*) FROM likes l WHERE l.id_critique = c.id) AS nb_likes,
                GROUP_CONCAT(cat.nom ORDER BY cat.nom SEPARATOR ', ') AS categories
            FROM critiques c
            INNER JOIN users u ON u.id = c.id_user
            LEFT JOIN critique_categorie cc ON cc.id_critique = c.id
            LEFT JOIN categories cat ON cat.id = cc.id_categorie
            $whereClause
            GROUP BY c.id, c.titre, c.contenu, c.note, c.date_creation, c.epingle, c.id_user, u.pseudo
            ORDER BY c.epingle DESC, c.date_creation DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Retourne une critique complète par son id (avec auteur, likes, catégories).
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id,
                c.titre,
                c.contenu,
                c.note,
                c.date_creation,
                c.date_modification,
                c.epingle,
                c.id_user,
                u.pseudo AS auteur,
                (SELECT COUNT(*) FROM likes l WHERE l.id_critique = c.id) AS nb_likes,
                GROUP_CONCAT(cat.nom ORDER BY cat.nom SEPARATOR ', ') AS categories
            FROM critiques c
            INNER JOIN users u ON u.id = c.id_user
            LEFT JOIN critique_categorie cc ON cc.id_critique = c.id
            LEFT JOIN categories cat ON cat.id = cc.id_categorie
            WHERE c.id = :id
            GROUP BY c.id, c.titre, c.contenu, c.note, c.date_creation, c.date_modification, c.epingle, c.id_user, u.pseudo
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Retourne les critiques d'un utilisateur donné.
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                c.id, c.titre, c.note, c.date_creation, c.epingle,
                (SELECT COUNT(*) FROM likes l WHERE l.id_critique = c.id) AS nb_likes
            FROM critiques c
            WHERE c.id_user = :uid
            ORDER BY c.date_creation DESC
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll();
    }

    /** Retourne le nombre total de critiques */
    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM critiques')->fetchColumn();
    }

    // ── Écriture ────────────────────────────────────────────

    /**
     * Crée une critique et lie ses catégories.
     *
     * @param array $categorieIds tableau d'ids de catégories
     */
    public function create(
        string $titre,
        string $contenu,
        int    $note,
        int    $userId,
        array  $categorieIds = []
    ): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO critiques (titre, contenu, note, id_user)
            VALUES (:titre, :contenu, :note, :uid)
        ");
        $stmt->execute([
            ':titre'   => $titre,
            ':contenu' => $contenu,
            ':note'    => $note,
            ':uid'     => $userId,
        ]);
        $newId = (int) $this->pdo->lastInsertId();

        $this->syncCategories($newId, $categorieIds);
        return $newId;
    }

    /**
     * Met à jour une critique existante.
     */
    public function update(
        int    $id,
        string $titre,
        string $contenu,
        int    $note,
        array  $categorieIds = []
    ): void {
        $stmt = $this->pdo->prepare("
            UPDATE critiques
            SET titre = :titre, contenu = :contenu, note = :note
            WHERE id = :id
        ");
        $stmt->execute([
            ':titre'   => $titre,
            ':contenu' => $contenu,
            ':note'    => $note,
            ':id'      => $id,
        ]);

        $this->syncCategories($id, $categorieIds);
    }

    /**
     * Supprime une critique (cascade sur likes et catégories).
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM critiques WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    /**
     * Bascule le statut épinglé d'une critique (admin).
     */
    public function togglePin(int $id): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE critiques SET epingle = NOT epingle WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
    }

    // ── Catégories ───────────────────────────────────────────

    /** Synchronise les catégories d'une critique (supprime puis recrée). */
    private function syncCategories(int $critiqueId, array $categorieIds): void
    {
        // Supprimer les anciennes liaisons
        $del = $this->pdo->prepare('DELETE FROM critique_categorie WHERE id_critique = :cid');
        $del->execute([':cid' => $critiqueId]);

        if (empty($categorieIds)) {
            return;
        }

        $ins = $this->pdo->prepare(
            'INSERT INTO critique_categorie (id_critique, id_categorie) VALUES (:cid, :catid)'
        );
        foreach ($categorieIds as $catId) {
            $ins->execute([':cid' => $critiqueId, ':catid' => (int) $catId]);
        }
    }

    /**
     * Retourne les ids de catégories liés à une critique.
     */
    public function getCategorieIds(int $critiqueId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_categorie FROM critique_categorie WHERE id_critique = :cid'
        );
        $stmt->execute([':cid' => $critiqueId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
