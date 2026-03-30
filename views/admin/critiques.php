<?php
/**
 * views/admin/critiques.php — Gestion des critiques (admin)
 * Données : $critiques, $flash
 */
$pageTitle = 'Critiques — Admin — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h3 fw-bold mb-1">
        <i class="bi bi-chat-square-text-fill text-warning me-2"></i>Gestion des critiques
      </h1>
      <p class="text-muted mb-0"><?= count($critiques) ?> critique<?= count($critiques) > 1 ? 's' : '' ?></p>
    </div>
    <a href="/admin/index.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
  </div>

  <div class="card rv-card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table rv-table mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Titre</th>
              <th>Auteur</th>
              <th class="text-center">Note</th>
              <th class="text-center">Likes</th>
              <th>Date</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($critiques as $c): ?>
            <tr>
              <td class="text-muted small"><?= (int) $c['id'] ?></td>
              <td>
                <?php if ($c['epingle']): ?>
                <i class="bi bi-pin-fill text-warning me-1" title="Épinglée"></i>
                <?php endif; ?>
                <a href="/critique.php?id=<?= (int) $c['id'] ?>"
                   class="text-white text-decoration-none rv-link">
                  <?= e(mb_strimwidth($c['titre'], 0, 50, '…')) ?>
                </a>
              </td>
              <td class="text-muted small"><?= e($c['auteur']) ?></td>
              <td class="text-center">
                <span class="badge rv-note-badge"><?= (int) $c['note'] ?>/10</span>
              </td>
              <td class="text-center text-muted small">
                <i class="bi bi-heart-fill text-danger me-1"></i><?= (int) $c['nb_likes'] ?>
              </td>
              <td class="text-muted small">
                <?= date('d/m/Y', strtotime($c['date_creation'])) ?>
              </td>
              <td class="text-end">
                <!-- Épingler / Désépingler -->
                <form action="/admin/pin.php" method="POST" class="d-inline">
                  <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                  <button type="submit"
                          class="btn btn-sm <?= $c['epingle'] ? 'btn-warning' : 'btn-outline-secondary' ?> me-1"
                          title="<?= $c['epingle'] ? 'Désépingler' : 'Épingler' ?>">
                    <i class="bi bi-pin<?= $c['epingle'] ? '-fill' : '' ?>"></i>
                  </button>
                </form>
                <!-- Modifier -->
                <a href="/edit.php?id=<?= (int) $c['id'] ?>"
                   class="btn btn-sm btn-outline-warning me-1" title="Modifier">
                  <i class="bi bi-pencil"></i>
                </a>
                <!-- Supprimer -->
                <form action="/admin/delete_critique.php" method="POST" class="d-inline"
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
    </div>
  </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
