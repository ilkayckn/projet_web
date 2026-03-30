<?php
/**
 * views/admin/users.php — Gestion des utilisateurs
 * Données : $users, $flash
 */
$pageTitle = 'Utilisateurs — Admin — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h1 class="h3 fw-bold mb-1">
        <i class="bi bi-people-fill text-warning me-2"></i>Gestion des utilisateurs
      </h1>
      <p class="text-muted mb-0"><?= count($users) ?> utilisateur<?= count($users) > 1 ? 's' : '' ?> inscrit<?= count($users) > 1 ? 's' : '' ?></p>
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
              <th>Pseudo</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Inscrit le</th>
              <th class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php $me = currentUser(); ?>
            <?php foreach ($users as $u): ?>
            <tr>
              <td class="text-muted small"><?= (int) $u['id'] ?></td>
              <td class="fw-semibold"><?= e($u['pseudo']) ?></td>
              <td class="text-muted small"><?= e($u['email']) ?></td>
              <td>
                <?php if ($u['id'] === $me['id']): ?>
                <span class="badge bg-warning text-dark"><?= e($u['role']) ?></span>
                <span class="text-muted small ms-1">(vous)</span>
                <?php else: ?>
                <form action="/admin/update_role.php" method="POST" class="d-inline-flex align-items-center gap-1">
                  <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                  <select name="role" class="form-select form-select-sm rv-select"
                          style="width:110px"
                          onchange="this.form.submit()"
                          aria-label="Rôle de <?= e($u['pseudo']) ?>">
                    <option value="user"      <?= $u['role'] === 'user'      ? 'selected' : '' ?>>Utilisateur</option>
                    <option value="critique"  <?= $u['role'] === 'critique'  ? 'selected' : '' ?>>Critique</option>
                    <option value="admin"     <?= $u['role'] === 'admin'     ? 'selected' : '' ?>>Admin</option>
                  </select>
                </form>
                <?php endif; ?>
              </td>
              <td class="text-muted small">
                <?= date('d/m/Y', strtotime($u['created_at'])) ?>
              </td>
              <td class="text-end">
                <?php if ($u['id'] !== $me['id']): ?>
                <form action="/admin/delete_user.php" method="POST" class="d-inline rv-confirm-delete"
                      data-confirm="Supprimer l'utilisateur &quot;<?= e($u['pseudo']) ?>&quot; ?">
                  <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
                <?php endif; ?>
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
