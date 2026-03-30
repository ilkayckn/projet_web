<?php
/**
 * views/critiques/detail.php — Page de détail d'une critique
 * Données : $critique, $user, $flash, $likedByMe
 */
$pageTitle = e($critique['titre']) . ' — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <!-- Fil d'Ariane -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb rv-breadcrumb">
      <li class="breadcrumb-item"><a href="/index.php" class="text-warning">Accueil</a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page">
        <?= e(mb_strimwidth($critique['titre'], 0, 50, '…')) ?>
      </li>
    </ol>
  </nav>

  <div class="row g-4">

    <!-- ── Critique principale ─────────────────────────── -->
    <div class="col-lg-8">
      <article class="card rv-card">
        <div class="card-body p-4">

          <!-- En-tête -->
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">

            <div>
              <?php if ($critique['epingle']): ?>
              <span class="badge bg-warning text-dark mb-2">
                <i class="bi bi-pin-fill me-1"></i>Épinglée
              </span>
              <?php endif; ?>
              <h1 class="h2 fw-bold text-white mb-1"><?= e($critique['titre']) ?></h1>
              <?php if ($critique['categories']): ?>
              <span class="badge rv-cat-badge"><?= e($critique['categories']) ?></span>
              <?php endif; ?>
            </div>

            <!-- Note -->
            <div class="rv-big-note text-center">
              <span class="display-6 fw-bold text-warning"><?= (int) $critique['note'] ?></span>
              <span class="text-muted">/10</span>
              <div class="rv-stars">
                <?php
                $note  = (int) $critique['note'];
                $full  = intdiv($note, 2);
                $half  = ($note % 2 !== 0) ? 1 : 0;
                $empty = 5 - $full - $half;
                echo str_repeat('<i class="bi bi-star-fill text-warning"></i>', $full);
                if ($half) echo '<i class="bi bi-star-half text-warning"></i>';
                echo str_repeat('<i class="bi bi-star text-muted"></i>', $empty);
                ?>
              </div>
            </div>

          </div>

          <!-- Contenu -->
          <div class="rv-contenu text-muted lh-lg mb-4">
            <?= nl2br(e($critique['contenu'])) ?>
          </div>

          <!-- Bouton Like -->
          <div class="d-flex align-items-center gap-3 mt-3 pt-3 border-top">
            <?php if (isLoggedIn()): ?>
            <button
              class="btn rv-like-btn <?= $likedByMe ? 'liked' : '' ?>"
              data-critique-id="<?= (int) $critique['id'] ?>"
              aria-pressed="<?= $likedByMe ? 'true' : 'false' ?>"
              aria-label="J'aime cette critique">
              <i class="bi bi-heart<?= $likedByMe ? '-fill' : '' ?> me-1"></i>
              <span class="like-count"><?= (int) $critique['nb_likes'] ?></span>
            </button>
            <?php else: ?>
            <span class="text-muted small">
              <i class="bi bi-heart text-danger me-1"></i>
              <strong><?= (int) $critique['nb_likes'] ?></strong> j'aime —
              <a href="/login.php" class="text-warning">Connectez-vous</a> pour liker
            </span>
            <?php endif; ?>
          </div>

        </div>
      </article>
    </div><!-- /.col -->

    <!-- ── Barre latérale ──────────────────────────────── -->
    <div class="col-lg-4">
      <aside>

        <!-- Infos auteur -->
        <div class="card rv-card mb-3">
          <div class="card-body">
            <h2 class="h6 text-muted text-uppercase fw-semibold mb-3">
              <i class="bi bi-person-circle me-1"></i>À propos
            </h2>
            <ul class="list-unstyled mb-0 small text-muted">
              <li class="mb-2">
                <i class="bi bi-person me-2 text-warning"></i>
                <strong class="text-white"><?= e($critique['auteur']) ?></strong>
              </li>
              <li class="mb-2">
                <i class="bi bi-calendar3 me-2 text-warning"></i>
                <?= date('d/m/Y à H:i', strtotime($critique['date_creation'])) ?>
              </li>
              <?php if ($critique['date_modification']): ?>
              <li class="mb-2">
                <i class="bi bi-pencil me-2 text-warning"></i>
                Modifiée le <?= date('d/m/Y', strtotime($critique['date_modification'])) ?>
              </li>
              <?php endif; ?>
            </ul>
          </div>
        </div>

        <!-- Actions (auteur ou admin) -->
        <?php $u = currentUser(); ?>
        <?php if ($u && ($u['id'] === $critique['id_user'] || isAdmin())): ?>
        <div class="card rv-card">
          <div class="card-body">
            <h2 class="h6 text-muted text-uppercase fw-semibold mb-3">
              <i class="bi bi-gear me-1"></i>Actions
            </h2>
            <div class="d-grid gap-2">
              <a href="/edit.php?id=<?= (int) $critique['id'] ?>" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>Modifier
              </a>
              <form action="/delete.php" method="POST"
                    onsubmit="return confirm('Supprimer cette critique définitivement ?')">
                <input type="hidden" name="id" value="<?= (int) $critique['id'] ?>">
                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                  <i class="bi bi-trash me-1"></i>Supprimer
                </button>
              </form>
              <?php if (isAdmin()): ?>
              <form action="/admin/pin.php" method="POST">
                <input type="hidden" name="id" value="<?= (int) $critique['id'] ?>">
                <button type="submit"
                        class="btn btn-sm w-100 <?= $critique['epingle'] ? 'btn-warning' : 'btn-outline-secondary' ?>">
                  <i class="bi bi-pin<?= $critique['epingle'] ? '-fill' : '' ?> me-1"></i>
                  <?= $critique['epingle'] ? 'Désépingler' : 'Épingler' ?>
                </button>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </aside>
    </div><!-- /.col -->

  </div><!-- /.row -->

  <!-- Retour -->
  <div class="mt-4">
    <a href="/index.php" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
    </a>
  </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
