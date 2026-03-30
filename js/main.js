/**
 * main.js — CinéBase
 * Navigation, interactions UI, toast notifications
 */

/* ── Navigation mobile ──────────────────────────────────── */
(function initNav() {
  const toggle = document.querySelector(".navbar-toggle");
  const nav = document.querySelector(".navbar-nav");
  if (!toggle || !nav) return;

  toggle.addEventListener("click", () => {
    nav.classList.toggle("open");
    const expanded = nav.classList.contains("open");
    toggle.setAttribute("aria-expanded", expanded);
  });

  // Fermer le menu si on clique ailleurs
  document.addEventListener("click", (e) => {
    if (!toggle.contains(e.target) && !nav.contains(e.target)) {
      nav.classList.remove("open");
      toggle.setAttribute("aria-expanded", "false");
    }
  });
})();

/* ── Marquer le lien actif dans la navbar ───────────────── */
(function markActiveLink() {
  const links = document.querySelectorAll(".navbar-nav a");
  const path = window.location.pathname.split("/").pop() || "index.html";
  links.forEach((link) => {
    const href = link.getAttribute("href");
    if (href === path || (path === "" && href === "index.html")) {
      link.classList.add("active");
    }
  });
})();

/* ── Formulaire de recherche dans la navbar ─────────────── */
(function initNavSearch() {
  const form = document.querySelector(".navbar .search-form");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const q = form.querySelector("input").value.trim();
    if (q) {
      window.location.href = `films.html?q=${encodeURIComponent(q)}`;
    }
  });
})();

/* ── Toast notification ─────────────────────────────────── */
function showToast(message, duration = 3000) {
  let toast = document.getElementById("toast");
  if (!toast) {
    toast = document.createElement("div");
    toast.id = "toast";
    toast.className = "toast";
    document.body.appendChild(toast);
  }
  toast.textContent = message;
  toast.classList.add("show");
  clearTimeout(toast._timer);
  toast._timer = setTimeout(() => toast.classList.remove("show"), duration);
}

/* ── Retour en haut (scroll to top) ─────────────────────── */
(function initScrollTop() {
  const btn = document.getElementById("scroll-top");
  if (!btn) return;

  window.addEventListener("scroll", () => {
    btn.style.display = window.scrollY > 400 ? "flex" : "none";
  });

  btn.addEventListener("click", () => window.scrollTo({ top: 0, behavior: "smooth" }));
})();

/* ── Exposer les fonctions globalement ───────────────────── */
window.showToast = showToast;
