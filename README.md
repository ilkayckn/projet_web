# 🎬 REVIEWEO — Plateforme de critiques

Application web full-stack de critiques et de notation (films, séries, jeux vidéo…).

## ⚙️ Stack technique

| Couche | Technologie |
|--------|------------|
| Frontend | HTML + **Bootstrap 5** + CSS personnalisé |
| Interactions | **JavaScript** (AJAX fetch pour les likes) |
| Backend | **PHP 8+ OOP** (MVC simplifié) |
| Base de données | **MySQL / MariaDB** (PDO, requêtes préparées) |

## 📁 Architecture du projet

```
projet_web/
├── index.php                  # Page d'accueil (liste des critiques)
├── login.php                  # Connexion
├── register.php               # Inscription
├── logout.php                 # Déconnexion
├── critique.php               # Détail d'une critique
├── create.php                 # Créer une critique (rôle : critique+)
├── edit.php                   # Modifier une critique
├── delete.php                 # Supprimer une critique
├── dashboard.php              # Tableau de bord du critique
│
├── admin/
│   ├── index.php              # Tableau de bord admin
│   ├── users.php              # Gérer les utilisateurs
│   ├── critiques.php          # Gérer toutes les critiques
│   ├── update_role.php        # Changer le rôle d'un utilisateur
│   ├── delete_user.php        # Supprimer un utilisateur
│   ├── delete_critique.php    # Supprimer une critique (admin)
│   └── pin.php                # Épingler / désépingler
│
├── ajax/
│   └── like.php               # Endpoint AJAX pour les likes
│
├── app/
│   ├── config/
│   │   └── database.php       # Connexion PDO (singleton)
│   ├── models/
│   │   ├── User.php
│   │   ├── Critique.php
│   │   ├── Categorie.php
│   │   └── Like.php
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── CritiqueController.php
│   │   └── AdminController.php
│   └── helpers/
│       └── auth.php           # Fonctions session / rôle / flash
│
├── views/
│   ├── partials/
│   │   ├── header.php         # Navbar Bootstrap
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── critiques/
│   │   ├── list.php           # Grille de critiques + filtres
│   │   ├── detail.php         # Fiche complète + like AJAX
│   │   └── form.php           # Formulaire création / édition
│   ├── dashboard/
│   │   └── index.php          # Dashboard critique
│   └── admin/
│       ├── dashboard.php
│       ├── users.php
│       └── critiques.php
│
├── css/
│   ├── revieweo.css           # Thème sombre personnalisé
│   └── style.css              # (héritage CinéBase)
├── js/
│   ├── app.js                 # AJAX likes
│   ├── films.js               # (héritage)
│   └── main.js                # (héritage)
└── sql/
    └── revieweo.sql           # Schéma complet + données de démo
```

## 🗄️ Base de données

### Schéma

```sql
User(id, pseudo, email, password, role, created_at)
Critique(id, titre, contenu, note, date_creation, date_modification, epingle, id_user)
Categorie(id, nom)
Like(id_user, id_critique, created_at)          -- clé primaire composite
Critique_Categorie(id_critique, id_categorie)   -- table pivot
```

### Installation

```bash
mysql -u root -p < sql/revieweo.sql
```

## 🚀 Lancement

### Prérequis
- PHP 8.0+
- MySQL / MariaDB
- Serveur web (Apache / Nginx) **ou** PHP built-in server

### Configuration

Éditez `app/config/database.php` et ajustez les constantes :

```php
private const DB_HOST = 'localhost';
private const DB_NAME = 'revieweo';
private const DB_USER = 'root';
private const DB_PASS = '';
```

### Serveur PHP intégré (développement)

```bash
cd projet_web
php -S localhost:8080
# Ouvrir http://localhost:8080
```

## 👥 Comptes de démonstration

| Email | Mot de passe | Rôle |
|-------|-------------|------|
| `admin@revieweo.fr` | `password` | Admin |
| `critique1@revieweo.fr` | `password` | Critique |
| `user1@revieweo.fr` | `password` | Utilisateur |

## 🔐 Gestion des rôles

| Action | Utilisateur | Critique | Admin |
|--------|:-----------:|:--------:|:-----:|
| Voir les critiques | ✅ | ✅ | ✅ |
| Liker (AJAX) | ✅ | ✅ | ✅ |
| S'inscrire / connexion | ✅ | ✅ | ✅ |
| Créer une critique | ❌ | ✅ | ✅ |
| Modifier / supprimer ses critiques | ❌ | ✅ | ✅ |
| Tableau de bord | ❌ | ✅ | ✅ |
| Gérer tous les utilisateurs | ❌ | ❌ | ✅ |
| Gérer toutes les critiques | ❌ | ❌ | ✅ |
| Épingler des critiques | ❌ | ❌ | ✅ |

## 🔒 Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Requêtes SQL via PDO + paramètres liés (protection SQL injection)
- Sorties HTML échappées avec `htmlspecialchars()` (protection XSS)
- Vérification de rôle côté serveur pour chaque action sensible
- Logout via POST (protection CSRF basique)
