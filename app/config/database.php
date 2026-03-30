<?php
/**
 * Database.php — Connexion PDO (singleton)
 * ─────────────────────────────────────────
 * Modifiez les constantes ci-dessous pour votre environnement.
 */

class Database
{
    // ── Paramètres de connexion ──────────────────────────────
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'revieweo';
    private const DB_USER = 'root';
    private const DB_PASS = '';
    private const DB_CHAR = 'utf8mb4';

    /** Instance unique (singleton) */
    private static ?PDO $instance = null;

    /** Empêche l'instanciation directe */
    private function __construct() {}

    /**
     * Retourne l'instance PDO partagée.
     *
     * @throws RuntimeException si la connexion échoue
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                self::DB_HOST,
                self::DB_NAME,
                self::DB_CHAR
            );

            try {
                self::$instance = new PDO($dsn, self::DB_USER, self::DB_PASS, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // En production, ne pas exposer le message d'erreur
                throw new RuntimeException('Erreur de connexion à la base de données.');
            }
        }

        return self::$instance;
    }
}
