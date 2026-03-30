# 🎬 CinéBase — Projet Web Cinéma

Base de projet web sur le thème du film et du cinéma.

## Structure du projet

```
projet_web/
├── index.html          # Page d'accueil (hero, top films, genres, films récents)
├── films.html          # Catalogue complet avec recherche et filtres
├── film-details.html   # Fiche détaillée d'un film
├── css/
│   └── style.css       # Feuille de style (thème cinéma sombre)
├── js/
│   ├── films.js        # Données des films + fonctions (filtre, tri, rendu)
│   └── main.js         # Navigation, interactions UI, toast
└── images/             # Dossier pour les affiches (à remplir)
```

## Pages

| Page | Description |
|------|-------------|
| `index.html` | Page d'accueil avec film mis en avant, statistiques, top films, genres et films récents |
| `films.html` | Catalogue complet — recherche full-text, filtre par genre, période et tri |
| `film-details.html` | Fiche d'un film : affiche, synopsis, casting, genres, films similaires |

## Fonctionnalités incluses

- 🌑 Thème cinéma sombre (noir, or, rouge)
- 📱 Design responsive (mobile, tablette, desktop)
- 🔍 Recherche en temps réel (titre, réalisateur, acteur)
- 🎭 Filtre par genre, période et tri
- ⭐ Notes et durées formatées
- 🔗 Navigation dynamique via URL (`?id=`, `?genre=`, `?q=`)
- ♿ Balises ARIA pour l'accessibilité

## Comment démarrer

Ouvrez simplement `index.html` dans votre navigateur, ou servez le dossier avec un serveur local :

```bash
# Python
python3 -m http.server 8080

# Node.js (npx)
npx serve .
```

Puis ouvrez [http://localhost:8080](http://localhost:8080).

## Étendre le projet

### Ajouter un film

Dans `js/films.js`, ajoutez un objet dans le tableau `FILMS` :

```js
{
  id: 13,
  titre: "Mon Film",
  annee: 2024,
  realisateur: "Nom Prénom",
  acteurs: "Acteur 1, Acteur 2",
  genres: ["Action", "Drame"],
  note: 7.5,
  duree: 120,          // en minutes
  synopsis: "Description…",
  affiche: "images/mon-film.jpg",  // null si pas d'image
  featured: false,
}
```

### Ajouter une affiche

Déposez l'image dans le dossier `images/` et renseignez le chemin dans `affiche`.

### Ajouter un genre

Dans le tableau `GENRES` de `js/films.js` :

```js
{ nom: "Documentaire", icone: "📹" }
```
