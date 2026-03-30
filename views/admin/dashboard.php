<?php
/**
 * views/admin/dashboard.php — Tableau de bord admin
 * Données : $totalUsers, $totalCritiques, $flash
 */
$pageTitle = 'Admin — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <div class="d-flex align-items-center mb-4">
    <div>
      <h1 class="h3 fw-bold mb-1">
        <i class="bi bi-shield-lock text-warning me-2"></i>Panneau d'administration
      </h1>
      <p class="text-muted mb-0">Gérez l'ensemble de la plateforme REVIEWEO</p>
    </div>
  </div>

  <!-- Stats -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-warning"><?= (int) $totalUsers ?></div>
        <div class="text-muted small">Utilisateurs inscrits</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-warning"><?= (int) $totalCritiques ?></div>
        <div class="text-muted small">Critiques publiées</div>
      </div>
    </div>
  </div>

  <!-- Actions rapides -->
  <div class="row g-3">
    <div class="col-md-6">
      <a href="/admin/users.php" class="card rv-card rv-action-card text-decoration-none h-100">
        <div class="card-body d-flex align-items-center gap-3 p-4">
          <div class="rv-action-icon">
            <i class="bi bi-people-fill fs-2 text-warning"></i>
          </div>
          <div>
            <div class="fw-bold text-white fs-5">Gérer les utilisateurs</div>
            <div class="text-muted small">Changer les rôles, supprimer des comptes</div>
          </div>
          <i class="bi bi-chevron-right text-muted ms-auto"></i>
        </div>
      </a>
    </div>
    <div class="col-md-6">
      <a href="/admin/critiques.php" class="card rv-card rv-action-card text-decoration-none h-100">
        <div class="card-body d-flex align-items-center gap-3 p-4">
          <div class="rv-action-icon">
            <i class="bi bi-chat-square-text-fill fs-2 text-warning"></i>
          </div>
          <div>
            <div class="fw-bold text-white fs-5">Gérer les critiques</div>
            <div class="text-muted small">Épingler, supprimer, modérer</div>
          </div>
          <i class="bi bi-chevron-right text-muted ms-auto"></i>
        </div>
      </a>
    </div>
  </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
