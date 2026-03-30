<?php
/**
 * views/auth/login.php — Formulaire de connexion
 * Données attendues : $flash (optionnel)
 */
$pageTitle = 'Connexion — REVIEWEO';
?>
<?php require __DIR__ . '/../partials/header.php'; ?>

<main class="rv-auth-wrapper d-flex align-items-center justify-content-center py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

        <!-- En-tête -->
        <div class="text-center mb-4">
          <i class="bi bi-camera-reels-fill text-warning" style="font-size:2.5rem"></i>
          <h1 class="h3 mt-2 fw-bold">Connexion</h1>
          <p class="text-muted small">Accédez à votre compte REVIEWEO</p>
        </div>

        <!-- Carte -->
        <div class="card rv-card shadow-sm">
          <div class="card-body p-4">
            <form action="/login.php" method="POST" novalidate>

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
              <div class="mb-4">
                <label for="password" class="form-label fw-semibold">
                  <i class="bi bi-lock me-1"></i>Mot de passe
                </label>
                <input type="password" id="password" name="password"
                       class="form-control rv-input"
                       placeholder="••••••••"
                       required autocomplete="current-password" />
              </div>

              <!-- Bouton -->
              <button type="submit" class="btn btn-warning w-100 fw-bold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
              </button>

            </form>
          </div>
        </div>

        <!-- Inscription -->
        <p class="text-center text-muted small mt-3">
          Pas encore de compte ?
          <a href="/register.php" class="text-warning fw-semibold">S'inscrire</a>
        </p>

        <!-- Comptes de démonstration -->
        <div class="card rv-card mt-3">
          <div class="card-body py-2 px-3">
            <p class="text-muted small mb-1 fw-semibold">
              <i class="bi bi-info-circle me-1"></i>Comptes de démo (mdp : <code>password</code>)
            </p>
            <ul class="list-unstyled mb-0 small text-muted">
              <li><code>admin@revieweo.fr</code> — Admin</li>
              <li><code>critique1@revieweo.fr</code> — Critique</li>
              <li><code>user1@revieweo.fr</code> — Utilisateur</li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>
