<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($pageTitle ?? 'REVIEWEO') ?></title>
  <meta name="description" content="REVIEWEO — La plateforme de critiques films, séries, jeux vidéo et plus encore." />

  <!-- Bootstrap 5 -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous" />

  <!-- Bootstrap Icons -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <!-- Styles personnalisés -->
  <link rel="stylesheet" href="/css/revieweo.css" />
</head>
<body>

<!-- ═══════════════════════════════════════════════════════════
     BARRE DE NAVIGATION
═══════════════════════════════════════════════════════════ -->
<nav class="navbar navbar-expand-lg navbar-dark rv-navbar sticky-top">
  <div class="container">

    <!-- Logo -->
    <a class="navbar-brand fw-bold" href="/index.php">
      <i class="bi bi-camera-reels-fill text-warning me-1"></i>REVIEWEO
    </a>

    <!-- Toggler mobile -->
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMain"
            aria-controls="navMain" aria-expanded="false" aria-label="Menu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">

      <!-- Liens principaux -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '') ?>"
             href="/index.php">
            <i class="bi bi-house-fill me-1"></i>Accueil
          </a>
        </li>
        <?php if (isCritique()): ?>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'create.php' ? 'active' : '') ?>"
             href="/create.php">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle critique
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= (basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '') ?>"
             href="/dashboard.php">
            <i class="bi bi-speedometer2 me-1"></i>Mon tableau de bord
          </a>
        </li>
        <?php endif; ?>
        <?php if (isAdmin()): ?>
        <li class="nav-item">
          <a class="nav-link <?= (strpos($_SERVER['PHP_SELF'], '/admin/') !== false ? 'active' : '') ?>"
             href="/admin/index.php">
            <i class="bi bi-shield-lock me-1"></i>Admin
          </a>
        </li>
        <?php endif; ?>
      </ul>

      <!-- Barre de recherche -->
      <form class="d-flex me-3" action="/index.php" method="GET" role="search">
        <div class="input-group input-group-sm">
          <input type="search" class="form-control rv-search"
                 name="search" placeholder="Rechercher…"
                 value="<?= e($_GET['search'] ?? '') ?>"
                 aria-label="Rechercher une critique" />
          <button class="btn btn-warning btn-sm" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>

      <!-- Utilisateur -->
      <ul class="navbar-nav">
        <?php if (isLoggedIn()): $u = currentUser(); ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button"
             data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i>
            <?= e($u['pseudo']) ?>
            <span class="badge rv-role-badge ms-1"><?= e($u['role']) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end rv-dropdown">
            <?php if (isCritique()): ?>
            <li><a class="dropdown-item" href="/dashboard.php">
              <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
            </a></li>
            <li><hr class="dropdown-divider"></li>
            <?php endif; ?>
            <li>
              <form action="/logout.php" method="POST" class="d-inline">
                <button type="submit" class="dropdown-item text-danger">
                  <i class="bi bi-box-arrow-right me-2"></i>Se déconnecter
                </button>
              </form>
            </li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item">
          <a class="nav-link" href="/login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Connexion</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-warning btn-sm ms-1" href="/register.php">S'inscrire</a>
        </li>
        <?php endif; ?>
      </ul>

    </div><!-- /.collapse -->
  </div><!-- /.container -->
</nav>

<!-- Message flash -->
<?php if (!empty($flash)): ?>
<div class="container mt-3">
  <div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show" role="alert">
    <?= e($flash['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
  </div>
</div>
<?php endif; ?>
