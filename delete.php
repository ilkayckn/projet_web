<?php
/**
 * delete.php — Suppression d'une critique (POST uniquement)
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/CritiqueController.php';

startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /index.php');
    exit;
}

$controller = new CritiqueController();
$controller->delete();
