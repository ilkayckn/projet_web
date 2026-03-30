<?php
/**
 * register.php — Inscription
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

startSession();

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleRegister();
} else {
    $controller->showRegister();
}
