<?php
/**
 * AuthController.php — Gestion de l'authentification
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/auth.php';

class AuthController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // ── Connexion ────────────────────────────────────────────

    /** Affiche le formulaire de connexion */
    public function showLogin(): void
    {
        if (isLoggedIn()) {
            header('Location: /index.php');
            exit;
        }
        $flash = getFlash();
        require __DIR__ . '/../../views/auth/login.php';
    }

    /** Traite la soumission du formulaire de connexion */
    public function handleLogin(): void
    {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            flashAndRedirect('danger', 'Veuillez remplir tous les champs.', '/login.php');
        }

        $user = $this->userModel->authenticate($email, $password);

        if (!$user) {
            flashAndRedirect('danger', 'Email ou mot de passe incorrect.', '/login.php');
        }

        startSession();
        $_SESSION['user'] = $user;
        flashAndRedirect('success', 'Bienvenue, ' . $user['pseudo'] . ' !', '/index.php');
    }

    // ── Inscription ──────────────────────────────────────────

    /** Affiche le formulaire d'inscription */
    public function showRegister(): void
    {
        if (isLoggedIn()) {
            header('Location: /index.php');
            exit;
        }
        $flash = getFlash();
        require __DIR__ . '/../../views/auth/register.php';
    }

    /** Traite la soumission du formulaire d'inscription */
    public function handleRegister(): void
    {
        $pseudo   = trim($_POST['pseudo']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm  = trim($_POST['confirm']  ?? '');

        // Validations
        if (!$pseudo || !$email || !$password || !$confirm) {
            flashAndRedirect('danger', 'Veuillez remplir tous les champs.', '/register.php');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flashAndRedirect('danger', 'Adresse email invalide.', '/register.php');
        }

        if (strlen($password) < 6) {
            flashAndRedirect('danger', 'Le mot de passe doit contenir au moins 6 caractères.', '/register.php');
        }

        if ($password !== $confirm) {
            flashAndRedirect('danger', 'Les mots de passe ne correspondent pas.', '/register.php');
        }

        // Vérification unicité
        if ($this->userModel->findByEmail($email)) {
            flashAndRedirect('danger', 'Cet email est déjà utilisé.', '/register.php');
        }

        if ($this->userModel->findByPseudo($pseudo)) {
            flashAndRedirect('danger', 'Ce pseudo est déjà pris.', '/register.php');
        }

        // Création
        $id = $this->userModel->create($pseudo, $email, $password, 'user');

        // Connexion automatique
        startSession();
        $_SESSION['user'] = [
            'id'    => $id,
            'pseudo' => $pseudo,
            'email' => $email,
            'role'  => 'user',
        ];
        flashAndRedirect('success', 'Compte créé avec succès. Bienvenue !', '/index.php');
    }

    // ── Déconnexion ──────────────────────────────────────────

    /** Détruit la session et redirige */
    public function logout(): void
    {
        startSession();
        session_destroy();
        header('Location: /login.php');
        exit;
    }
}
