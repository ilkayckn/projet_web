<?php
/**
 * Categorie.php — Modèle catégorie
 */

require_once __DIR__ . '/../config/database.php';

class Categorie
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /** Retourne toutes les catégories */
    public function findAll(): array
    {
        return $this->pdo->query('SELECT * FROM categories ORDER BY nom')->fetchAll();
    }

    /** Retourne une catégorie par son id */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM categories WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }
}
