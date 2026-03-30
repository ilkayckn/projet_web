<?php
/**
 * edit.php — Modification d'une critique
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/CritiqueController.php';

startSession();

$controller = new CritiqueController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->handleEdit();
} else {
    $controller->showEdit();
}
