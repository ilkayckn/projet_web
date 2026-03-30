<?php
/**
 * logout.php — Déconnexion (POST uniquement)
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $controller->logout();
} else {
    header('Location: /index.php');
    exit;
}
