<?php
/**
 * AdminController.php — Panneau d'administration
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Critique.php';
require_once __DIR__ . '/../helpers/auth.php';

class AdminController
{
    private User     $userModel;
    private Critique $critiqueModel;

    public function __construct()
    {
        $this->userModel     = new User();
        $this->critiqueModel = new Critique();
    }

    // ── Dashboard admin ──────────────────────────────────────

    /** Tableau de bord principal */
    public function dashboard(): void
    {
        requireRole('admin');
        $flash       = getFlash();
        $totalUsers  = $this->userModel->count();
        $totalCritiques = $this->critiqueModel->count();
        require __DIR__ . '/../../views/admin/dashboard.php';
    }

    // ── Gestion des utilisateurs ────────────────────────────

    /** Liste tous les utilisateurs */
    public function listUsers(): void
    {
        requireRole('admin');
        $flash = getFlash();
        $users = $this->userModel->findAll();
        require __DIR__ . '/../../views/admin/users.php';
    }

    /** Met à jour le rôle d'un utilisateur */
    public function updateUserRole(): void
    {
        requireRole('admin');

        $id   = (int) ($_POST['id']   ?? 0);
        $role = $_POST['role'] ?? '';

        if (!in_array($role, ['user', 'critique', 'admin'], true)) {
            flashAndRedirect('danger', 'Rôle invalide.', '/admin/users.php');
        }

        $current = currentUser();
        if ($current['id'] === $id) {
            flashAndRedirect('danger', 'Vous ne pouvez pas modifier votre propre rôle.', '/admin/users.php');
        }

        $this->userModel->updateRole($id, $role);
        flashAndRedirect('success', 'Rôle mis à jour.', '/admin/users.php');
    }

    /** Supprime un utilisateur */
    public function deleteUser(): void
    {
        requireRole('admin');

        $id = (int) ($_POST['id'] ?? 0);

        $current = currentUser();
        if ($current['id'] === $id) {
            flashAndRedirect('danger', 'Vous ne pouvez pas supprimer votre propre compte.', '/admin/users.php');
        }

        $this->userModel->delete($id);
        flashAndRedirect('success', 'Utilisateur supprimé.', '/admin/users.php');
    }

    // ── Gestion des critiques ────────────────────────────────

    /** Liste toutes les critiques (admin) */
    public function listCritiques(): void
    {
        requireRole('admin');
        $flash     = getFlash();
        $critiques = $this->critiqueModel->findAll();
        require __DIR__ . '/../../views/admin/critiques.php';
    }

    /** Supprime n'importe quelle critique */
    public function deleteCritique(): void
    {
        requireRole('admin');

        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->critiqueModel->findById($id)) {
            flashAndRedirect('danger', 'Critique introuvable.', '/admin/critiques.php');
        }

        $this->critiqueModel->delete($id);
        flashAndRedirect('success', 'Critique supprimée.', '/admin/critiques.php');
    }

    /** Bascule le statut épinglé d'une critique */
    public function togglePin(): void
    {
        requireRole('admin');

        $id = (int) ($_POST['id'] ?? 0);

        if (!$this->critiqueModel->findById($id)) {
            flashAndRedirect('danger', 'Critique introuvable.', '/admin/critiques.php');
        }

        $this->critiqueModel->togglePin($id);
        flashAndRedirect('success', 'Statut d\'épinglage mis à jour.', '/admin/critiques.php');
    }
}
