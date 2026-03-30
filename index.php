<?php
/**
 * index.php — Page d'accueil : listing des critiques
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/CritiqueController.php';

startSession();

$controller = new CritiqueController();
$controller->index();
