<?php
/**
 * auth.php — Fonctions d'aide à l'authentification et aux rôles
 */

/** Démarre la session si elle n'est pas encore démarrée */
function startSession(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/** Retourne l'utilisateur connecté (tableau) ou null */
function currentUser(): ?array
{
    startSession();
    return $_SESSION['user'] ?? null;
}

/** Retourne true si un utilisateur est connecté */
function isLoggedIn(): bool
{
    return currentUser() !== null;
}

/** Retourne le rôle de l'utilisateur connecté, ou null */
function currentRole(): ?string
{
    return currentUser()['role'] ?? null;
}

/** Retourne true si le rôle est au moins 'critique' */
function isCritique(): bool
{
    return in_array(currentRole(), ['critique', 'admin'], true);
}

/** Retourne true si l'utilisateur est admin */
function isAdmin(): bool
{
    return currentRole() === 'admin';
}

/**
 * Redirige vers login.php si l'utilisateur n'est pas connecté.
 * Accepte un message flash optionnel.
 */
function requireLogin(string $message = ''): void
{
    if (!isLoggedIn()) {
        if ($message) {
            startSession();
            $_SESSION['flash'] = ['type' => 'warning', 'message' => $message];
        }
        header('Location: /login.php');
        exit;
    }
}

/**
 * Redirige avec une erreur si le rôle est insuffisant.
 * @param string $minRole 'user' | 'critique' | 'admin'
 */
function requireRole(string $minRole): void
{
    requireLogin('Vous devez être connecté pour accéder à cette page.');

    $hierarchy = ['user' => 1, 'critique' => 2, 'admin' => 3];
    $userLevel = $hierarchy[currentRole()] ?? 0;
    $required  = $hierarchy[$minRole] ?? 99;

    if ($userLevel < $required) {
        startSession();
        $_SESSION['flash'] = [
            'type'    => 'danger',
            'message' => 'Accès refusé : droits insuffisants.',
        ];
        header('Location: /index.php');
        exit;
    }
}

/**
 * Stocke un message flash en session et redirige.
 */
function flashAndRedirect(string $type, string $message, string $url): never
{
    startSession();
    $_SESSION['flash'] = compact('type', 'message');
    header("Location: $url");
    exit;
}

/**
 * Récupère et supprime le message flash de la session.
 * Retourne null si aucun message.
 */
function getFlash(): ?array
{
    startSession();
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Échappe une chaîne pour l'affichage HTML.
 */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
