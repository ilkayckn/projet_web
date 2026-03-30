<?php
/**
 * views/critiques/list.php — Listing des critiques (page d'accueil)
 * Données attendues : $critiques, $categories, $user, $flash, $catFilter, $searchFilter
 */
$pageTitle = 'REVIEWEO — Critiques';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main>

  <!-- ── Hero ──────────────────────────────────────────── -->
  <section class="rv-hero py-5">
    <div class="container text-center">
      <h1 class="display-5 fw-bold text-white mb-2">
        <i class="bi bi-camera-reels-fill text-warning me-2"></i>REVIEWEO
      </h1>
      <p class="lead text-muted mb-4">
        Découvrez les meilleures critiques de films, séries, jeux vidéo et plus encore.
      </p>
      <?php if (!isLoggedIn()): ?>
      <a href="/register.php" class="btn btn-warning fw-bold me-2">
        <i class="bi bi-person-plus me-1"></i>Rejoindre la communauté
      </a>
      <a href="/login.php" class="btn btn-outline-light">
        <i class="bi bi-box-arrow-in-right me-1"></i>Se connecter
      </a>
      <?php elseif (isCritique()): ?>
      <a href="/create.php" class="btn btn-warning fw-bold">
        <i class="bi bi-plus-circle me-1"></i>Publier une critique
      </a>
      <?php endif; ?>
    </div>
  </section>

  <!-- ── Filtres ────────────────────────────────────────── -->
  <section class="rv-filters py-3 sticky-top" style="top:56px; z-index:100;">
    <div class="container">
      <form action="/index.php" method="GET" class="row g-2 align-items-center">

        <!-- Filtre catégorie -->
        <div class="col-auto">
          <select name="cat" class="form-select form-select-sm rv-select"
                  aria-label="Filtrer par catégorie" onchange="this.form.submit()">
            <option value="">🎭 Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= (int) $cat['id'] ?>"
              <?= ((int)($catFilter ?? 0) === (int)$cat['id'] ? 'selected' : '') ?>>
              <?= e($cat['nom']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Barre de recherche (desktop) -->
        <div class="col">
          <div class="input-group input-group-sm">
            <input type="search" name="search" class="form-control rv-input"
                   placeholder="Rechercher un titre ou un contenu…"
                   value="<?= e($searchFilter ?? '') ?>" />
            <button class="btn btn-warning btn-sm" type="submit">
              <i class="bi bi-search"></i>
            </button>
          </div>
        </div>

        <!-- Réinitialiser -->
        <?php if ($catFilter || $searchFilter): ?>
        <div class="col-auto">
          <a href="/index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-x-circle me-1"></i>Réinitialiser
          </a>
        </div>
        <?php endif; ?>

      </form>
    </div>
  </section>

  <!-- ── Liste des critiques ───────────────────────────── -->
  <section class="container py-4">

    <?php if (empty($critiques)): ?>
    <div class="text-center py-5">
      <i class="bi bi-search text-muted" style="font-size:3rem"></i>
      <p class="text-muted mt-3 fs-5">Aucune critique trouvée.</p>
      <?php if (isCritique()): ?>
      <a href="/create.php" class="btn btn-warning mt-2">
        <i class="bi bi-plus me-1"></i>Publier la première
      </a>
      <?php endif; ?>
    </div>
    <?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <span class="text-muted small">
        <strong class="text-white"><?= count($critiques) ?></strong>
        critique<?= count($critiques) > 1 ? 's' : '' ?> trouvée<?= count($critiques) > 1 ? 's' : '' ?>
      </span>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
      <?php foreach ($critiques as $critique): ?>
      <div class="col">
        <article class="card rv-card h-100 <?= $critique['epingle'] ? 'rv-pinned' : '' ?>">

          <?php if ($critique['epingle']): ?>
          <div class="rv-pin-badge">
            <i class="bi bi-pin-fill"></i> Épinglée
          </div>
          <?php endif; ?>

          <div class="card-body d-flex flex-column">

            <!-- Note -->
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div class="rv-note-badge">
                <i class="bi bi-star-fill text-warning me-1"></i>
                <strong><?= (int) $critique['note'] ?></strong>/10
              </div>
              <?php if ($critique['categories']): ?>
              <span class="badge rv-cat-badge text-truncate" style="max-width:120px"
                    title="<?= e($critique['categories']) ?>">
                <?= e($critique['categories']) ?>
              </span>
              <?php endif; ?>
            </div>

            <!-- Titre -->
            <h2 class="card-title h5 fw-bold mb-1">
              <a href="/critique.php?id=<?= (int) $critique['id'] ?>"
                 class="text-white text-decoration-none rv-link">
                <?= e($critique['titre']) ?>
              </a>
            </h2>

            <!-- Extrait -->
            <p class="card-text text-muted small flex-grow-1">
              <?= e(mb_strimwidth($critique['contenu'], 0, 140, '…')) ?>
            </p>

            <!-- Méta -->
            <div class="mt-auto pt-3 border-top rv-meta d-flex justify-content-between align-items-center">
              <small class="text-muted">
                <i class="bi bi-person-circle me-1"></i><?= e($critique['auteur']) ?>
                <span class="mx-2">·</span>
                <?= date('d/m/Y', strtotime($critique['date_creation'])) ?>
              </small>
              <span class="rv-likes text-muted small">
                <i class="bi bi-heart-fill text-danger me-1"></i>
                <?= (int) $critique['nb_likes'] ?>
              </span>
            </div>

          </div><!-- /.card-body -->
        </article>
      </div>
      <?php endforeach; ?>
    </div><!-- /.row -->

    <?php endif; ?>
  </section>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
