<?php
/**
 * critique.php — Détail d'une critique
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/CritiqueController.php';
require_once __DIR__ . '/app/models/Like.php';

startSession();

$controller = new CritiqueController();
$controller->show();
