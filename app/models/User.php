<?php
/**
 * User.php — Modèle utilisateur
 */

require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    // ── Lecture ─────────────────────────────────────────────

    /** Retourne tous les utilisateurs */
    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, pseudo, email, role, created_at FROM users ORDER BY created_at DESC'
        );
        return $stmt->fetchAll();
    }

    /** Retourne un utilisateur par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, pseudo, email, role, created_at FROM users WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /** Retourne un utilisateur par son email */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE email = :email'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ?: null;
    }

    /** Retourne un utilisateur par son pseudo */
    public function findByPseudo(string $pseudo): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM users WHERE pseudo = :pseudo'
        );
        $stmt->execute([':pseudo' => $pseudo]);
        return $stmt->fetch() ?: null;
    }

    /** Retourne le nombre d'utilisateurs */
    public function count(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    // ── Écriture ─────────────────────────────────────────────

    /**
     * Crée un utilisateur.
     * Le mot de passe doit être en clair (hashé ici).
     */
    public function create(string $pseudo, string $email, string $password, string $role = 'user'): int
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->pdo->prepare(
            'INSERT INTO users (pseudo, email, password, role) VALUES (:pseudo, :email, :password, :role)'
        );
        $stmt->execute([
            ':pseudo'   => $pseudo,
            ':email'    => $email,
            ':password' => $hash,
            ':role'     => $role,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Met à jour le rôle d'un utilisateur (admin uniquement).
     */
    public function updateRole(int $id, string $role): void
    {
        $stmt = $this->pdo->prepare('UPDATE users SET role = :role WHERE id = :id');
        $stmt->execute([':role' => $role, ':id' => $id]);
    }

    /**
     * Supprime un utilisateur par son id.
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    // ── Authentification ────────────────────────────────────

    /**
     * Vérifie les identifiants et retourne l'utilisateur si valide, null sinon.
     */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            // Ne jamais retourner le hash en session
            unset($user['password']);
            return $user;
        }
        return null;
    }
}
