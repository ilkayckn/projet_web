/**
 * films.js — CinéBase
 * Données des films et logique du catalogue
 */

/* ============================================================
   Données – Films
   ============================================================ */
const FILMS = [
  {
    id: 1,
    titre: "Inception",
    annee: 2010,
    realisateur: "Christopher Nolan",
    acteurs: "Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page",
    genres: ["Science-Fiction", "Thriller", "Action"],
    note: 8.8,
    duree: 148,
    synopsis:
      "Un voleur qui s'introduit dans les rêves des autres pour extraire des informations se voit proposer une tâche inverse : implanter une idée dans l'esprit d'un homme d'affaires.",
    affiche: null,
    featured: true,
  },
  {
    id: 2,
    titre: "Le Parrain",
    annee: 1972,
    realisateur: "Francis Ford Coppola",
    acteurs: "Marlon Brando, Al Pacino, James Caan",
    genres: ["Drame", "Crime"],
    note: 9.2,
    duree: 175,
    synopsis:
      "Le patriarche vieillissant d'une dynastie criminelle transfère le contrôle de son empire clandestin à son fils réticent.",
    affiche: null,
    featured: false,
  },
  {
    id: 3,
    titre: "Interstellar",
    annee: 2014,
    realisateur: "Christopher Nolan",
    acteurs: "Matthew McConaughey, Anne Hathaway, Jessica Chastain",
    genres: ["Science-Fiction", "Drame", "Aventure"],
    note: 8.6,
    duree: 169,
    synopsis:
      "Une équipe d'explorateurs voyage à travers un trou de ver dans l'espace afin d'assurer la survie de l'humanité.",
    affiche: null,
    featured: true,
  },
  {
    id: 4,
    titre: "The Dark Knight",
    annee: 2008,
    realisateur: "Christopher Nolan",
    acteurs: "Christian Bale, Heath Ledger, Aaron Eckhart",
    genres: ["Action", "Crime", "Drame"],
    note: 9.0,
    duree: 152,
    synopsis:
      "Batman, Gordon et Dent s'unissent pour démanteler le crime organisé à Gotham, mais le Joker sème le chaos.",
    affiche: null,
    featured: false,
  },
  {
    id: 5,
    titre: "Parasite",
    annee: 2019,
    realisateur: "Bong Joon-ho",
    acteurs: "Song Kang-ho, Lee Sun-kyun, Cho Yeo-jeong",
    genres: ["Drame", "Thriller", "Comédie"],
    note: 8.5,
    duree: 132,
    synopsis:
      "L'histoire d'une famille pauvre qui s'infiltre peu à peu dans la vie d'une famille riche.",
    affiche: null,
    featured: true,
  },
  {
    id: 6,
    titre: "Pulp Fiction",
    annee: 1994,
    realisateur: "Quentin Tarantino",
    acteurs: "John Travolta, Uma Thurman, Samuel L. Jackson",
    genres: ["Crime", "Drame"],
    note: 8.9,
    duree: 154,
    synopsis:
      "Les histoires entrelacées de gangsters, d'un boxeur et de criminels dans Los Angeles.",
    affiche: null,
    featured: false,
  },
  {
    id: 7,
    titre: "Forrest Gump",
    annee: 1994,
    realisateur: "Robert Zemeckis",
    acteurs: "Tom Hanks, Robin Wright, Gary Sinise",
    genres: ["Drame", "Romance", "Comédie"],
    note: 8.8,
    duree: 142,
    synopsis:
      "Un homme du sud aux capacités mentales limitées est au cœur des moments les plus importants de la seconde moitié du XXe siècle.",
    affiche: null,
    featured: false,
  },
  {
    id: 8,
    titre: "Le Silence des Agneaux",
    annee: 1991,
    realisateur: "Jonathan Demme",
    acteurs: "Jodie Foster, Anthony Hopkins, Scott Glenn",
    genres: ["Thriller", "Crime", "Horreur"],
    note: 8.6,
    duree: 118,
    synopsis:
      "Une jeune stagiaire du FBI doit faire appel à un tueur en série emprisonné pour attraper un autre meurtrier.",
    affiche: null,
    featured: false,
  },
  {
    id: 9,
    titre: "Avengers: Endgame",
    annee: 2019,
    realisateur: "Anthony et Joe Russo",
    acteurs: "Robert Downey Jr., Chris Evans, Mark Ruffalo",
    genres: ["Action", "Science-Fiction", "Aventure"],
    note: 8.4,
    duree: 181,
    synopsis:
      "Après les événements dévastateurs d'Infinity War, les Avengers tentent de renverser les actions de Thanos.",
    affiche: null,
    featured: false,
  },
  {
    id: 10,
    titre: "Titanic",
    annee: 1997,
    realisateur: "James Cameron",
    acteurs: "Leonardo DiCaprio, Kate Winslet, Billy Zane",
    genres: ["Romance", "Drame", "Aventure"],
    note: 7.9,
    duree: 194,
    synopsis:
      "Une romance se noue entre un jeune artiste pauvre et une aristocrate à bord du célèbre navire.",
    affiche: null,
    featured: false,
  },
  {
    id: 11,
    titre: "La La Land",
    annee: 2016,
    realisateur: "Damien Chazelle",
    acteurs: "Ryan Gosling, Emma Stone",
    genres: ["Romance", "Drame", "Musical"],
    note: 8.0,
    duree: 128,
    synopsis:
      "Un pianiste de jazz et une actrice en herbe tombent amoureux à Los Angeles, poursuivant leurs rêves.",
    affiche: null,
    featured: false,
  },
  {
    id: 12,
    titre: "Matrix",
    annee: 1999,
    realisateur: "Lana et Lilly Wachowski",
    acteurs: "Keanu Reeves, Laurence Fishburne, Carrie-Anne Moss",
    genres: ["Science-Fiction", "Action"],
    note: 8.7,
    duree: 136,
    synopsis:
      "Un programmeur informatique découvre que la réalité est une simulation et rejoint une rébellion contre ses créateurs.",
    affiche: null,
    featured: true,
  },
];

/* ============================================================
   Genres disponibles (pour les filtres)
   ============================================================ */
const GENRES = [
  { nom: "Action",        icone: "💥" },
  { nom: "Aventure",      icone: "🗺️" },
  { nom: "Comédie",       icone: "😄" },
  { nom: "Crime",         icone: "🔫" },
  { nom: "Drame",         icone: "🎭" },
  { nom: "Horreur",       icone: "👻" },
  { nom: "Musical",       icone: "🎵" },
  { nom: "Romance",       icone: "❤️" },
  { nom: "Science-Fiction", icone: "🚀" },
  { nom: "Thriller",      icone: "🔪" },
];

/* ============================================================
   Helpers
   ============================================================ */

/**
 * Retourne les étoiles HTML pour une note donnée (sur 10).
 */
function renderStars(note) {
  const stars = Math.round(note / 2);
  return "★".repeat(stars) + "☆".repeat(5 - stars);
}

/**
 * Formate la durée en "Xh Ymin".
 */
function formatDuree(minutes) {
  const h = Math.floor(minutes / 60);
  const m = minutes % 60;
  return h > 0 ? `${h}h ${m > 0 ? m + "min" : ""}`.trim() : `${m}min`;
}

/**
 * Construit le HTML d'une carte film.
 */
function buildFilmCard(film) {
  const genres = film.genres.slice(0, 2).map(
    (g) => `<span class="badge badge-genre">${g}</span>`
  ).join("");

  const posterContent = film.affiche
    ? `<img src="${film.affiche}" alt="Affiche de ${film.titre}" loading="lazy">`
    : `<div class="film-card-poster-placeholder">
         <span class="icon">🎬</span>
         <span>${film.titre}</span>
       </div>`;

  return `
    <article class="film-card" data-id="${film.id}">
      <div class="film-card-poster">
        ${posterContent}
        <div class="film-card-rating">⭐ ${film.note}</div>
        <div class="film-card-overlay">
          <a href="film-details.html?id=${film.id}">Voir le film</a>
        </div>
      </div>
      <div class="film-card-body">
        <h3 class="film-card-title" title="${film.titre}">${film.titre}</h3>
        <div class="film-card-meta">
          <span>${film.annee}</span>
          <span>${formatDuree(film.duree)}</span>
        </div>
      </div>
    </article>`;
}

/**
 * Filtre les films selon les critères donnés.
 */
function filtrerFilms({ recherche = "", genre = "", anneeMin = 0, tri = "note" } = {}) {
  let resultats = [...FILMS];

  if (recherche.trim()) {
    const q = recherche.toLowerCase();
    resultats = resultats.filter(
      (f) =>
        f.titre.toLowerCase().includes(q) ||
        f.realisateur.toLowerCase().includes(q) ||
        f.acteurs.toLowerCase().includes(q)
    );
  }

  if (genre) {
    resultats = resultats.filter((f) => f.genres.includes(genre));
  }

  if (anneeMin) {
    resultats = resultats.filter((f) => f.annee >= anneeMin);
  }

  switch (tri) {
    case "note":
      resultats.sort((a, b) => b.note - a.note);
      break;
    case "annee_desc":
      resultats.sort((a, b) => b.annee - a.annee);
      break;
    case "annee_asc":
      resultats.sort((a, b) => a.annee - b.annee);
      break;
    case "titre":
      resultats.sort((a, b) => a.titre.localeCompare(b.titre));
      break;
    default:
      break;
  }

  return resultats;
}

/**
 * Récupère un film par son id.
 */
function getFilmById(id) {
  return FILMS.find((f) => f.id === Number(id)) || null;
}
