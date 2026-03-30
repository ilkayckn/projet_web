/**
 * app.js — REVIEWEO
 * Système de like AJAX + interactions UI
 */

'use strict';

/* ── Like AJAX ──────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {

  /**
   * Gère le clic sur un bouton "like".
   * Le bouton doit avoir l'attribut data-critique-id.
   */
  document.querySelectorAll('.rv-like-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const critiqueId = parseInt(btn.dataset.critiqueId, 10);

      if (!critiqueId) return;

      // Désactiver temporairement pour éviter le double-clic
      btn.disabled = true;

      fetch('/ajax/like.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ id: critiqueId }),
      })
        .then(function (res) {
          if (res.status === 401) {
            // Non connecté → rediriger vers login
            window.location.href = '/login.php';
            return null;
          }
          if (!res.ok) {
            throw new Error('Erreur serveur');
          }
          return res.json();
        })
        .then(function (data) {
          if (!data) return;

          const countEl = btn.querySelector('.like-count');
          const icon    = btn.querySelector('i');

          // Mettre à jour le compteur
          if (countEl) countEl.textContent = data.count;

          // Basculer l'état visuel
          if (data.liked) {
            btn.classList.add('liked');
            btn.setAttribute('aria-pressed', 'true');
            if (icon) {
              icon.classList.remove('bi-heart');
              icon.classList.add('bi-heart-fill');
            }
          } else {
            btn.classList.remove('liked');
            btn.setAttribute('aria-pressed', 'false');
            if (icon) {
              icon.classList.remove('bi-heart-fill');
              icon.classList.add('bi-heart');
            }
          }

          // Animation bounce
          btn.classList.add('rv-like-bounce');
          btn.addEventListener('animationend', function () {
            btn.classList.remove('rv-like-bounce');
          }, { once: true });
        })
        .catch(function (err) {
          console.error('Erreur like :', err);
        })
        .finally(function () {
          btn.disabled = false;
        });
    });
  });

  /* ── Formulaires de confirmation sécurisés ────────────── */
  /**
   * Les formulaires avec .rv-confirm-delete lisent le message
   * de confirmation depuis data-confirm (déjà échappé côté PHP)
   * au lieu d'utiliser des chaînes inline avec données utilisateur.
   */
  document.querySelectorAll('form.rv-confirm-delete').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      const message = form.dataset.confirm || 'Confirmer cette action ?';
      if (!window.confirm(message)) {
        e.preventDefault();
      }
    });
  });

});

