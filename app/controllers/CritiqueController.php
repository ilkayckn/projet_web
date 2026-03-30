<?php
/**
 * CritiqueController.php — CRUD des critiques
 */

require_once __DIR__ . '/../models/Critique.php';
require_once __DIR__ . '/../models/Categorie.php';
require_once __DIR__ . '/../helpers/auth.php';

class CritiqueController
{
    private Critique  $critiqueModel;
    private Categorie $categorieModel;

    public function __construct()
    {
        $this->critiqueModel  = new Critique();
        $this->categorieModel = new Categorie();
    }

    // ── Liste ────────────────────────────────────────────────

    /** Affiche la liste des critiques (page d'accueil) */
    public function index(): void
    {
        $flash      = getFlash();
        $user       = currentUser();
        $categories = $this->categorieModel->findAll();

        // Filtres GET
        $catFilter    = isset($_GET['cat'])    ? (int) $_GET['cat']   : null;
        $searchFilter = trim($_GET['search'] ?? '');

        $critiques  = $this->critiqueModel->findAll(
            $catFilter    ?: null,
            $searchFilter ?: null
        );

        require __DIR__ . '/../../views/critiques/list.php';
    }

    // ── Détail ───────────────────────────────────────────────

    /** Affiche une critique en détail */
    public function show(): void
    {
        $id       = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $critique = $this->critiqueModel->findById($id);

        if (!$critique) {
            flashAndRedirect('danger', 'Critique introuvable.', '/index.php');
        }

        $user       = currentUser();
        $flash      = getFlash();
        $likedByMe  = false;

        if ($user) {
            $likeModel = new \Like();
            $likedByMe = $likeModel->hasLiked($user['id'], $critique['id']);
        }

        require __DIR__ . '/../../views/critiques/detail.php';
    }

    // ── Création ─────────────────────────────────────────────

    /** Affiche le formulaire de création */
    public function showCreate(): void
    {
        requireRole('critique');
        $flash      = getFlash();
        $categories = $this->categorieModel->findAll();
        $critique   = null; // formulaire vide
        require __DIR__ . '/../../views/critiques/form.php';
    }

    /** Traite la création */
    public function handleCreate(): void
    {
        requireRole('critique');

        $titre      = trim($_POST['titre']   ?? '');
        $contenu    = trim($_POST['contenu'] ?? '');
        $note       = (int) ($_POST['note']  ?? 5);
        $catIds     = array_map('intval', (array) ($_POST['categories'] ?? []));

        if (!$titre || !$contenu) {
            flashAndRedirect('danger', 'Titre et contenu sont obligatoires.', '/create.php');
        }

        $note = max(1, min(10, $note));
        $user = currentUser();

        $id = $this->critiqueModel->create($titre, $contenu, $note, $user['id'], $catIds);
        flashAndRedirect('success', 'Critique publiée avec succès !', "/critique.php?id=$id");
    }

    // ── Modification ─────────────────────────────────────────

    /** Affiche le formulaire d'édition */
    public function showEdit(): void
    {
        requireRole('critique');
        $id       = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $critique = $this->critiqueModel->findById($id);

        if (!$critique) {
            flashAndRedirect('danger', 'Critique introuvable.', '/dashboard.php');
        }

        $user = currentUser();

        // Seul l'auteur ou un admin peut modifier
        if ($critique['id_user'] !== $user['id'] && !isAdmin()) {
            flashAndRedirect('danger', 'Vous ne pouvez pas modifier cette critique.', '/dashboard.php');
        }

        $flash         = getFlash();
        $categories    = $this->categorieModel->findAll();
        $selectedCats  = $this->critiqueModel->getCategorieIds($id);
        require __DIR__ . '/../../views/critiques/form.php';
    }

    /** Traite la modification */
    public function handleEdit(): void
    {
        requireRole('critique');

        $id      = (int) ($_POST['id']      ?? 0);
        $titre   = trim($_POST['titre']     ?? '');
        $contenu = trim($_POST['contenu']   ?? '');
        $note    = (int) ($_POST['note']    ?? 5);
        $catIds  = array_map('intval', (array) ($_POST['categories'] ?? []));

        $critique = $this->critiqueModel->findById($id);

        if (!$critique) {
            flashAndRedirect('danger', 'Critique introuvable.', '/dashboard.php');
        }

        $user = currentUser();

        if ($critique['id_user'] !== $user['id'] && !isAdmin()) {
            flashAndRedirect('danger', 'Accès refusé.', '/dashboard.php');
        }

        if (!$titre || !$contenu) {
            flashAndRedirect('danger', 'Titre et contenu sont obligatoires.', "/edit.php?id=$id");
        }

        $note = max(1, min(10, $note));
        $this->critiqueModel->update($id, $titre, $contenu, $note, $catIds);
        flashAndRedirect('success', 'Critique mise à jour.', "/critique.php?id=$id");
    }

    // ── Suppression ──────────────────────────────────────────

    /** Supprime une critique (auteur ou admin) */
    public function delete(): void
    {
        requireRole('critique');

        $id       = (int) ($_POST['id'] ?? 0);
        $critique = $this->critiqueModel->findById($id);

        if (!$critique) {
            flashAndRedirect('danger', 'Critique introuvable.', '/dashboard.php');
        }

        $user = currentUser();

        if ($critique['id_user'] !== $user['id'] && !isAdmin()) {
            flashAndRedirect('danger', 'Accès refusé.', '/dashboard.php');
        }

        $this->critiqueModel->delete($id);
        flashAndRedirect('success', 'Critique supprimée.', '/dashboard.php');
    }

    // ── Tableau de bord ──────────────────────────────────────

    /** Dashboard du critique connecté */
    public function dashboard(): void
    {
        requireRole('critique');
        $user     = currentUser();
        $flash    = getFlash();
        $critiques = $this->critiqueModel->findByUser($user['id']);
        require __DIR__ . '/../../views/dashboard/index.php';
    }
}
