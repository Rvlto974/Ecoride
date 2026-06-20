-- ============================================================
-- EcoRide - Schema de la base relationnelle (MySQL 8)
-- Genere a partir du MCD (Looping), nettoye et adapte a MySQL.
-- Ce fichier est execute automatiquement au 1er demarrage
-- du conteneur MySQL (docker-entrypoint-initdb.d).
-- ============================================================

-- Encodage de la base : utf8mb4 (accents + emojis), collation moderne
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Table : utilisateur
-- Pierre angulaire : comptes, credits, roles.
-- ------------------------------------------------------------
CREATE TABLE utilisateur (
    id_utilisateur   INT          NOT NULL AUTO_INCREMENT,
    pseudo           VARCHAR(50)  NOT NULL,
    email            VARCHAR(100) NOT NULL,
    mot_de_passe     VARCHAR(255) NOT NULL,           -- stocke un hash (password_hash)
    credits          INT          NOT NULL DEFAULT 20, -- 20 credits offerts a l'inscription
    role             VARCHAR(50)  NOT NULL DEFAULT 'utilisateur', -- utilisateur / employe / administrateur
    est_chauffeur    BOOLEAN      NOT NULL DEFAULT FALSE,
    est_passager     BOOLEAN      NOT NULL DEFAULT FALSE,
    statut           VARCHAR(50)  NOT NULL DEFAULT 'actif',        -- actif / suspendu
    PRIMARY KEY (id_utilisateur),
    UNIQUE KEY uq_utilisateur_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : marque
-- ------------------------------------------------------------
CREATE TABLE marque (
    id_marque   INT         NOT NULL AUTO_INCREMENT,
    libelle     VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_marque)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : vehicule
-- Appartient a un utilisateur (chauffeur) et a une marque.
-- energie = electrique / essence / diesel / hybride
--   -> determine si un trajet est ecologique.
-- ------------------------------------------------------------
CREATE TABLE vehicule (
    id_vehicule          INT         NOT NULL AUTO_INCREMENT,
    immatriculation      VARCHAR(20) NOT NULL,
    date_premiere_immat  DATE        NULL,
    modele               VARCHAR(50) NOT NULL,
    couleur              VARCHAR(30) NULL,
    nb_places            INT         NOT NULL,
    energie              VARCHAR(30) NOT NULL,
    id_marque            INT         NOT NULL,
    id_utilisateur       INT         NOT NULL,
    PRIMARY KEY (id_vehicule),
    CONSTRAINT fk_vehicule_marque
        FOREIGN KEY (id_marque) REFERENCES marque (id_marque),
    CONSTRAINT fk_vehicule_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : covoiturage
-- Propose par un chauffeur (utilisateur), utilise un vehicule.
-- statut = en_attente / demarre / termine / annule
-- ------------------------------------------------------------
CREATE TABLE covoiturage (
    id_covoiturage   INT          NOT NULL AUTO_INCREMENT,
    ville_depart     VARCHAR(100) NOT NULL,
    ville_arrivee    VARCHAR(100) NOT NULL,
    depart           DATETIME     NOT NULL,
    arrivee          DATETIME     NOT NULL,
    prix             INT          NOT NULL,
    nb_places        INT          NOT NULL,
    statut           VARCHAR(30)  NOT NULL DEFAULT 'en_attente',
    id_vehicule      INT          NOT NULL,
    id_utilisateur   INT          NOT NULL,
    PRIMARY KEY (id_covoiturage),
    CONSTRAINT fk_covoiturage_vehicule
        FOREIGN KEY (id_vehicule) REFERENCES vehicule (id_vehicule),
    CONSTRAINT fk_covoiturage_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : preference
-- Preferences du chauffeur (fumeur / animal).
-- Un utilisateur a au plus un jeu de preferences.
-- ------------------------------------------------------------
CREATE TABLE preference (
    id_preference   INT     NOT NULL AUTO_INCREMENT,
    fumeur          BOOLEAN NOT NULL DEFAULT FALSE,
    animal          BOOLEAN NOT NULL DEFAULT FALSE,
    id_utilisateur  INT     NOT NULL,
    PRIMARY KEY (id_preference),
    UNIQUE KEY uq_preference_utilisateur (id_utilisateur),
    CONSTRAINT fk_preference_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table : participation
-- Table d'association passager <-> covoiturage (cardinalite 0,n / 0,n).
-- Porte les donnees propres a chaque reservation.
-- statut     = confirme / annule
-- validation = en_attente / ok / probleme
-- ------------------------------------------------------------
CREATE TABLE participation (
    id_utilisateur     INT         NOT NULL,
    id_covoiturage     INT         NOT NULL,
    date_confirmation  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    credits_utilises   INT         NOT NULL,
    statut             VARCHAR(30) NOT NULL DEFAULT 'confirme',
    validation         VARCHAR(30) NOT NULL DEFAULT 'en_attente',
    PRIMARY KEY (id_utilisateur, id_covoiturage),
    CONSTRAINT fk_participation_utilisateur
        FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id_utilisateur),
    CONSTRAINT fk_participation_covoiturage
        FOREIGN KEY (id_covoiturage) REFERENCES covoiturage (id_covoiturage)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
