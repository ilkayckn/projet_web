-- ============================================================
-- REVIEWEO — Script SQL complet
-- Base de données : revieweo
-- ============================================================

CREATE DATABASE IF NOT EXISTS revieweo
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE revieweo;

-- ─────────────────────────────────────────────────────────────
-- Table : users
-- Rôles : 'user' | 'critique' | 'admin'
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    pseudo        VARCHAR(50)     NOT NULL UNIQUE,
    email         VARCHAR(150)    NOT NULL UNIQUE,
    password      VARCHAR(255)    NOT NULL,           -- bcrypt hash
    role          ENUM('user','critique','admin') NOT NULL DEFAULT 'user',
    created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Table : categories
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom   VARCHAR(80)  NOT NULL UNIQUE,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Table : critiques
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS critiques (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    titre           VARCHAR(200)    NOT NULL,
    contenu         TEXT            NOT NULL,
    note            TINYINT UNSIGNED NOT NULL DEFAULT 5,   -- 1 à 10
    date_creation   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME      NULL ON UPDATE CURRENT_TIMESTAMP,
    epingle         TINYINT(1)      NOT NULL DEFAULT 0,    -- admin peut épingler
    id_user         INT UNSIGNED    NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_critique_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Table : likes  (clé composite : un user = un like par critique)
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS likes (
    id_user      INT UNSIGNED NOT NULL,
    id_critique  INT UNSIGNED NOT NULL,
    created_at   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_user, id_critique),
    CONSTRAINT fk_like_user
        FOREIGN KEY (id_user) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_like_critique
        FOREIGN KEY (id_critique) REFERENCES critiques(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Table pivot : critique_categorie
-- ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS critique_categorie (
    id_critique  INT UNSIGNED NOT NULL,
    id_categorie INT UNSIGNED NOT NULL,
    PRIMARY KEY (id_critique, id_categorie),
    CONSTRAINT fk_cc_critique
        FOREIGN KEY (id_critique) REFERENCES critiques(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cc_categorie
        FOREIGN KEY (id_categorie) REFERENCES categories(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────────────────────
-- Données initiales : catégories
-- ─────────────────────────────────────────────────────────────
INSERT INTO categories (nom) VALUES
    ('Film'),
    ('Série'),
    ('Jeu vidéo'),
    ('Musique'),
    ('Livre'),
    ('Anime'),
    ('Documentaire');

-- ─────────────────────────────────────────────────────────────
-- Données initiales : utilisateurs (mot de passe = "password")
-- Hash généré avec password_hash("password", PASSWORD_BCRYPT)
-- ─────────────────────────────────────────────────────────────
INSERT INTO users (pseudo, email, password, role) VALUES
    ('admin',   'admin@revieweo.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
    ('critique1','critique1@revieweo.fr','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'critique'),
    ('user1',   'user1@revieweo.fr',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- ─────────────────────────────────────────────────────────────
-- Données initiales : critiques
-- ─────────────────────────────────────────────────────────────
INSERT INTO critiques (titre, contenu, note, id_user, epingle) VALUES
    (
        'Inception – Un chef-d\'œuvre de Nolan',
        'Christopher Nolan signe ici un film d\'une ambition narrative et visuelle rare. Le scénario, labyrinthique à souhait, nous plonge dans des niveaux de rêve emboîtés avec une maîtrise impressionnante. Leonardo DiCaprio porte le film avec intensité. La bande originale de Hans Zimmer est inoubliable. Une expérience cinématographique unique.',
        10,
        2,
        1
    ),
    (
        'The Last of Us – La meilleure adaptation de jeu vidéo ?',
        'HBO réussit là où beaucoup ont échoué : adapter un jeu vidéo en série sans trahir son âme. Pedro Pascal et Bella Ramsey forment un duo inoubliable. Les émotions sont au rendez-vous, le scénario est fidèle tout en apportant de nouvelles couches. Épisode 3 parmi les meilleurs de la télévision moderne.',
        9,
        2,
        0
    ),
    (
        'Elden Ring – Un monument du jeu de rôle',
        'FromSoftware et George R.R. Martin ont créé un monde ouvert d\'une richesse inégalée. La difficulté est présente mais jamais injuste. Chaque zone est une découverte, chaque boss un défi mémorable. Le lore est profond pour qui veut bien creuser. Un jeu qui redéfinit le genre.',
        10,
        2,
        0
    );

-- ─────────────────────────────────────────────────────────────
-- Liaison critiques ↔ catégories
-- ─────────────────────────────────────────────────────────────
INSERT INTO critique_categorie (id_critique, id_categorie) VALUES
    (1, 1),  -- Inception → Film
    (2, 2),  -- Last of Us → Série
    (3, 3);  -- Elden Ring → Jeu vidéo

-- ─────────────────────────────────────────────────────────────
-- Quelques likes initiaux
-- ─────────────────────────────────────────────────────────────
INSERT INTO likes (id_user, id_critique) VALUES
    (3, 1),
    (3, 2),
    (1, 1),
    (1, 3);
