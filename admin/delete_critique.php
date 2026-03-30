<?php
/**
 * admin/delete_critique.php — Suppression d'une critique par l'admin (POST)
 */

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/critiques.php');
    exit;
}

$controller = new AdminController();
$controller->deleteCritique();
