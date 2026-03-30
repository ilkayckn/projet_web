<?php
/**
 * admin/delete_user.php — Suppression d'un utilisateur (POST)
 */

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

startSession();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/users.php');
    exit;
}

$controller = new AdminController();
$controller->deleteUser();
