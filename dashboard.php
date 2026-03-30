<?php
/**
 * dashboard.php — Tableau de bord du critique
 */

require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/controllers/CritiqueController.php';

startSession();

$controller = new CritiqueController();
$controller->dashboard();
