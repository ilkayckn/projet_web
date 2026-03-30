<?php
/**
 * views/critiques/form.php — Formulaire de création / modification
 * Données : $critique (null = création), $categories, $selectedCats (edit), $flash
 */
$isEdit    = !empty($critique);
$pageTitle = ($isEdit ? 'Modifier' : 'Nouvelle') . ' critique — REVIEWEO';
$selectedCats = $selectedCats ?? [];
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="container py-4">

  <!-- Fil d'Ariane -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb rv-breadcrumb">
      <li class="breadcrumb-item"><a href="/index.php" class="text-warning">Accueil</a></li>
      <li class="breadcrumb-item"><a href="/dashboard.php" class="text-warning">Tableau de bord</a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page">
        <?= $isEdit ? 'Modifier' : 'Nouvelle critique' ?>
      </li>
    </ol>
  </nav>

  <div class="row justify-content-center">
    <div class="col-lg-8">

      <div class="card rv-card shadow-sm">
        <div class="card-header rv-card-header">
          <h1 class="h4 mb-0 fw-bold">
            <i class="bi bi-<?= $isEdit ? 'pencil' : 'plus-circle' ?> me-2 text-warning"></i>
            <?= $isEdit ? 'Modifier la critique' : 'Publier une nouvelle critique' ?>
          </h1>
        </div>
        <div class="card-body p-4">

          <form action="<?= $isEdit ? '/edit.php' : '/create.php' ?>" method="POST" novalidate>

            <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) $critique['id'] ?>">
            <?php endif; ?>

            <!-- Titre -->
            <div class="mb-4">
              <label for="titre" class="form-label fw-semibold">
                <i class="bi bi-type me-1"></i>Titre <span class="text-danger">*</span>
              </label>
              <input type="text" id="titre" name="titre"
                     class="form-control rv-input"
                     placeholder="Ex : Inception — Un chef-d'œuvre de Nolan"
                     value="<?= e($isEdit ? $critique['titre'] : ($_POST['titre'] ?? '')) ?>"
                     maxlength="200" required />
              <div class="form-text text-muted">Max. 200 caractères</div>
            </div>

            <!-- Note -->
            <div class="mb-4">
              <label for="note" class="form-label fw-semibold">
                <i class="bi bi-star me-1"></i>Note <span class="text-danger">*</span>
              </label>
              <div class="d-flex align-items-center gap-3">
                <input type="range" id="note" name="note"
                       class="form-range rv-range flex-grow-1"
                       min="1" max="10" step="1"
                       value="<?= (int) ($isEdit ? $critique['note'] : ($_POST['note'] ?? 5)) ?>"
                       oninput="document.getElementById('note-display').textContent = this.value" />
                <div class="rv-note-display text-center">
                  <strong id="note-display" class="text-warning fs-4">
                    <?= (int) ($isEdit ? $critique['note'] : ($_POST['note'] ?? 5)) ?>
                  </strong>
                  <span class="text-muted">/10</span>
                </div>
              </div>
            </div>

            <!-- Catégories -->
            <?php if (!empty($categories)): ?>
            <div class="mb-4">
              <label class="form-label fw-semibold">
                <i class="bi bi-tags me-1"></i>Catégories
              </label>
              <div class="d-flex flex-wrap gap-2">
                <?php foreach ($categories as $cat): ?>
                <?php $checked = in_array((string) $cat['id'], array_map('strval', $selectedCats ?: ($_POST['categories'] ?? [])), true); ?>
                <div class="form-check rv-checkbox-cat">
                  <input class="form-check-input" type="checkbox"
                         name="categories[]"
                         value="<?= (int) $cat['id'] ?>"
                         id="cat-<?= (int) $cat['id'] ?>"
                         <?= $checked ? 'checked' : '' ?> />
                  <label class="form-check-label badge rv-cat-badge p-2"
                         for="cat-<?= (int) $cat['id'] ?>">
                    <?= e($cat['nom']) ?>
                  </label>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Contenu -->
            <div class="mb-4">
              <label for="contenu" class="form-label fw-semibold">
                <i class="bi bi-text-paragraph me-1"></i>Critique <span class="text-danger">*</span>
              </label>
              <textarea id="contenu" name="contenu"
                        class="form-control rv-textarea"
                        rows="10"
                        placeholder="Rédigez votre critique ici…"
                        required><?= e($isEdit ? $critique['contenu'] : ($_POST['contenu'] ?? '')) ?></textarea>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2 justify-content-end">
              <a href="<?= $isEdit ? '/critique.php?id=' . (int) $critique['id'] : '/dashboard.php' ?>"
                 class="btn btn-outline-secondary">
                <i class="bi bi-x me-1"></i>Annuler
              </a>
              <button type="submit" class="btn btn-warning fw-bold">
                <i class="bi bi-<?= $isEdit ? 'check-lg' : 'send' ?> me-1"></i>
                <?= $isEdit ? 'Enregistrer' : 'Publier' ?>
              </button>
            </div>

          </form>
        </div>
      </div>

    </div>
  </div>

</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
