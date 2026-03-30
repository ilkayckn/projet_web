<?php
/**
 * Like.php — Modèle like
 */

require_once __DIR__ . '/../config/database.php';

class Like
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Retourne true si l'utilisateur a déjà liké cette critique.
     */
    public function hasLiked(int $userId, int $critiqueId): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM likes WHERE id_user = :uid AND id_critique = :cid'
        );
        $stmt->execute([':uid' => $userId, ':cid' => $critiqueId]);
        return (bool) $stmt->fetch();
    }

    /**
     * Ajoute un like.
     */
    public function add(int $userId, int $critiqueId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT IGNORE INTO likes (id_user, id_critique) VALUES (:uid, :cid)'
        );
        $stmt->execute([':uid' => $userId, ':cid' => $critiqueId]);
    }

    /**
     * Retire un like.
     */
    public function remove(int $userId, int $critiqueId): void
    {
        $stmt = $this->pdo->prepare(
            'DELETE FROM likes WHERE id_user = :uid AND id_critique = :cid'
        );
        $stmt->execute([':uid' => $userId, ':cid' => $critiqueId]);
    }

    /**
     * Bascule le like (ajoute si absent, retire si présent).
     * Retourne ['liked' => bool, 'count' => int].
     */
    public function toggle(int $userId, int $critiqueId): array
    {
        if ($this->hasLiked($userId, $critiqueId)) {
            $this->remove($userId, $critiqueId);
            $liked = false;
        } else {
            $this->add($userId, $critiqueId);
            $liked = true;
        }

        // Recompter les likes
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) FROM likes WHERE id_critique = :cid'
        );
        $stmt->execute([':cid' => $critiqueId]);
        $count = (int) $stmt->fetchColumn();

        return ['liked' => $liked, 'count' => $count];
    }
}
