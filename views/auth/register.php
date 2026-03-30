<?php
/**
 * views/auth/register.php — Formulaire d'inscription
 */
$pageTitle = 'Inscription — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="rv-auth-wrapper d-flex align-items-center justify-content-center py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <!-- En-tête -->
        <div class="text-center mb-4">
          <i class="bi bi-person-plus-fill text-warning" style="font-size:2.5rem"></i>
          <h1 class="h3 mt-2 fw-bold">Créer un compte</h1>
          <p class="text-muted small">Rejoignez la communauté REVIEWEO</p>
        </div>

        <!-- Carte -->
        <div class="card rv-card shadow-sm">
          <div class="card-body p-4">
            <form action="/register.php" method="POST" novalidate>

              <!-- Pseudo -->
              <div class="mb-3">
                <label for="pseudo" class="form-label fw-semibold">
                  <i class="bi bi-person me-1"></i>Pseudo
                </label>
                <input type="text" id="pseudo" name="pseudo"
                       class="form-control rv-input"
                       placeholder="MonPseudo"
                       value="<?= e($_POST['pseudo'] ?? '') ?>"
                       required autocomplete="username" />
              </div>

              <!-- Email -->
              <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                  <i class="bi bi-envelope me-1"></i>Email
                </label>
                <input type="email" id="email" name="email"
                       class="form-control rv-input"
                       placeholder="vous@exemple.fr"
                       value="<?= e($_POST['email'] ?? '') ?>"
                       required autocomplete="email" />
              </div>

              <!-- Mot de passe -->
              <div class="mb-3">
                <label for="password" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1"></i>Mot de passe
                </label>
                <input type="password" id="password" name="password"
                       class="form-control rv-input"
                       placeholder="Min. 6 caractères"
                       required autocomplete="new-password" />
              </div>

              <!-- Confirmation -->
              <div class="mb-4">
                <label for="confirm" class="form-label fw-semibold">
                  <i class="bi bi-lock-fill me-1"></i>Confirmer le mot de passe
                </label>
                <input type="password" id="confirm" name="confirm"
                       class="form-control rv-input"
                       placeholder="••••••••"
                       required autocomplete="new-password" />
              </div>

              <!-- Bouton -->
              <button type="submit" class="btn btn-warning w-100 fw-bold">
                <i class="bi bi-person-check me-2"></i>Créer mon compte
              </button>

            </form>
          </div>
        </div>

        <!-- Déjà inscrit -->
        <p class="text-center text-muted small mt-3">
          Déjà un compte ?
          <a href="/login.php" class="text-warning fw-semibold">Se connecter</a>
        </p>

      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
