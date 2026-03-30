<?php
/**
 * ajax/like.php — Gestion des likes en AJAX
 * Méthode : POST
 * Corps    : JSON { "id": <int> }
 * Réponse  : JSON { "liked": bool, "count": int }
 */

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/models/Like.php';
require_once __DIR__ . '/../app/models/Critique.php';

startSession();
header('Content-Type: application/json; charset=utf-8');

// ── Vérifier que l'utilisateur est connecté ──────────────
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Non authentifié. Connectez-vous pour liker.']);
    exit;
}

// ── Lire et valider le corps JSON ────────────────────────
$body       = file_get_contents('php://input');
$data       = json_decode($body, true);
$critiqueId = isset($data['id']) ? (int) $data['id'] : 0;

if ($critiqueId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Identifiant de critique invalide.']);
    exit;
}

// ── Vérifier que la critique existe ─────────────────────
$critiqueModel = new Critique();
if (!$critiqueModel->findById($critiqueId)) {
    http_response_code(404);
    echo json_encode(['error' => 'Critique introuvable.']);
    exit;
}

// ── Basculer le like ─────────────────────────────────────
$user      = currentUser();
$likeModel = new Like();
$result    = $likeModel->toggle($user['id'], $critiqueId);

echo json_encode($result);
