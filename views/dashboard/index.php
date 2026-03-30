<?php
/**
 * views/dashboard/index.php — Tableau de bord du critique
 * Données : $user, $critiques, $flash
 */
$pageTitle = 'Tableau de bord — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <!-- En-tête -->
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h3 fw-bold mb-1">
        <i class="bi bi-speedometer2 text-warning me-2"></i>Mon tableau de bord
      </h1>
      <p class="text-muted mb-0">Gérez vos critiques, <?= e($user['pseudo']) ?></p>
    </div>
    <a href="/create.php" class="btn btn-warning fw-bold">
      <i class="bi bi-plus-circle me-1"></i>Nouvelle critique
    </a>
  </div>

  <!-- Statistiques -->
  <div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-warning"><?= count($critiques) ?></div>
        <div class="text-muted small">Critique<?= count($critiques) > 1 ? 's' : '' ?> publiée<?= count($critiques) > 1 ? 's' : '' ?></div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-warning">
          <?= array_sum(array_column($critiques, 'nb_likes')) ?>
        </div>
        <div class="text-muted small">Likes reçus</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-warning">
          <?= $critiques ? number_format(array_sum(array_column($critiques, 'note')) / count($critiques), 1) : '—' ?>
        </div>
        <div class="text-muted small">Note moyenne</div>
      </div>
    </div>
    <div class="col-sm-6 col-lg-3">
      <div class="card rv-card text-center py-3">
        <div class="fs-2 fw-bold text-<?= $user['role'] === 'admin' ? 'danger' : 'warning' ?>">
          <?= e($user['role']) ?>
        </div>
        <div class="text-muted small">Rôle</div>
      </div>
    </div>
  </div>

  <!-- Mes critiques -->
  <div class="card rv-card">
    <div class="card-header rv-card-header d-flex justify-content-between align-items-center">
      <h2 class="h5 mb-0"><i class="bi bi-list-ul me-2"></i>Mes critiques</h2>
    </div>
    <div class="card-body p-0">
      <?php if (empty($critiques)): ?>
      <div class="text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:2.5rem"></i>
        <p class="text-muted mt-3">Vous n'avez pas encore publié de critique.</p>
        <a href="/create.php" class="btn btn-warning btn-sm">
          <i class="bi bi-plus me-1"></i>Créer la première
        </a>
      </div>
      <?php else: ?>
      <div class="table-responsive">
        <table class="table rv-table mb-0">
          <thead>
            <tr>
              <th>Titre</th>
              <th class="text-center">Note</th>
              <th class="text-center">Likes</th>
              <th>Date</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($critiques as $c): ?>
            <tr>
              <td>
                <?php if ($c['epingle']): ?>
                <i class="bi bi-pin-fill text-warning me-1" title="Épinglée"></i>
                <?php endif; ?>
                <a href="/critique.php?id=<?= (int) $c['id'] ?>" class="text-white text-decoration-none rv-link">
                  <?= e(mb_strimwidth($c['titre'], 0, 60, '…')) ?>
                </a>
              </td>
              <td class="text-center">
                <span class="badge rv-note-badge"><?= (int) $c['note'] ?>/10</span>
              </td>
              <td class="text-center text-muted">
                <i class="bi bi-heart-fill text-danger me-1"></i><?= (int) $c['nb_likes'] ?>
              </td>
              <td class="text-muted small">
                <?= date('d/m/Y', strtotime($c['date_creation'])) ?>
              </td>
              <td class="text-end">
                <a href="/edit.php?id=<?= (int) $c['id'] ?>"
                   class="btn btn-sm btn-outline-warning me-1" title="Modifier">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="/delete.php" method="POST" class="d-inline"
                      onsubmit="return confirm('Supprimer cette critique ?')">
                  <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php endif; ?>
    </div>
  </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
