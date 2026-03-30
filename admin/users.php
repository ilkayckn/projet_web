<?php
/**
 * admin/users.php — Liste des utilisateurs
 */

require_once __DIR__ . '/../app/helpers/auth.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

startSession();

$controller = new AdminController();
$controller->listUsers();
