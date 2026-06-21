-- ============================================================
-- EcoRide - Jeu de donnees de test (MySQL 8)
-- Execute automatiquement apres 01_schema.sql
-- (ordre alphabetique dans docker-entrypoint-initdb.d).
--
-- Mot de passe en clair de TOUS les comptes de test : Test1234!
-- (stocke sous forme de hash bcrypt, compatible password_verify).
-- ============================================================

SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Utilisateurs
-- Roles : administrateur, employe, utilisateur (chauffeur/passager)
-- ------------------------------------------------------------
INSERT INTO utilisateur
    (pseudo, email, mot_de_passe, credits, role, est_chauffeur, est_passager, statut)
VALUES
    ('Admin',     'admin@ecoride.fr',     '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 100, 'administrateur', FALSE, FALSE, 'actif'),
    ('Emma',      'employe@ecoride.fr',   '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 20,  'employe',        FALSE, FALSE, 'actif'),
    ('Lucas',     'lucas@mail.fr',        '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 35,  'utilisateur',    TRUE,  TRUE,  'actif'),
    ('Sophie',    'sophie@mail.fr',       '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 50,  'utilisateur',    TRUE,  FALSE, 'actif'),
    ('Mathieu',   'mathieu@mail.fr',      '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 60,  'utilisateur',    FALSE, TRUE,  'actif'),
    ('Nadia',     'nadia@mail.fr',        '$2y$10$Fnq6h7Of5TtCLTbasOxX3u/e7o2IORbjyd1e1IEojkb3ZiilIWpXS', 40,  'utilisateur',    FALSE, TRUE,  'actif');

-- ------------------------------------------------------------
-- Marques
-- ------------------------------------------------------------
INSERT INTO marque (libelle) VALUES
    ('Renault'),
    ('Peugeot'),
    ('Tesla'),
    ('Toyota'),
    ('Citroen');

-- ------------------------------------------------------------
-- Vehicules
-- Rappel ordre des utilisateurs : 3 = Lucas (chauffeur), 4 = Sophie (chauffeur)
-- energie : 'electrique' => trajet ecologique
-- ------------------------------------------------------------
INSERT INTO vehicule
    (immatriculation, date_premiere_immat, modele, couleur, nb_places, energie, id_marque, id_utilisateur)
VALUES
    ('AB-123-CD', '2022-03-15', 'Zoe',     'Blanc', 4, 'electrique', 1, 3),  -- Lucas, Renault Zoe (eco)
    ('EF-456-GH', '2019-06-01', 'Model 3', 'Noir',  4, 'electrique', 3, 4),  -- Sophie, Tesla (eco)
    ('IJ-789-KL', '2018-09-20', '308',     'Gris',  5, 'diesel',     2, 3);  -- Lucas, Peugeot 308 (non eco)

-- ------------------------------------------------------------
-- Preferences (du chauffeur)
-- ------------------------------------------------------------
INSERT INTO preference (fumeur, animal, id_utilisateur) VALUES
    (FALSE, TRUE,  3),   -- Lucas : non-fumeur, animaux ok
    (FALSE, FALSE, 4);   -- Sophie : non-fumeur, pas d'animaux

-- ------------------------------------------------------------
-- Covoiturages
-- Rappel vehicules : 1 = Zoe (Lucas), 2 = Model 3 (Sophie), 3 = 308 (Lucas)
-- statut : en_attente / demarre / termine / annule
-- ------------------------------------------------------------
INSERT INTO covoiturage
    (ville_depart, ville_arrivee, depart, arrivee, prix, nb_places, statut, id_vehicule, id_utilisateur)
VALUES
    ('Lyon',       'Paris',       '2026-07-01 08:00:00', '2026-07-01 12:30:00', 25, 4, 'en_attente', 1, 3), -- Lucas, Zoe (eco)
    ('Marseille',  'Montpellier', '2026-07-02 17:30:00', '2026-07-02 19:15:00', 12, 4, 'en_attente', 2, 4), -- Sophie, Tesla (eco)
    ('Bordeaux',   'Toulouse',    '2026-07-03 07:15:00', '2026-07-03 09:30:00', 15, 5, 'en_attente', 3, 3); -- Lucas, 308

-- ------------------------------------------------------------
-- Participations (passager <-> covoiturage)
-- Rappel passagers : 5 = Mathieu, 6 = Nadia, 3 = Lucas (aussi passager)
-- ------------------------------------------------------------
INSERT INTO participation
    (id_utilisateur, id_covoiturage, date_confirmation, credits_utilises, statut, validation)
VALUES
    (5, 1, '2026-06-20 10:00:00', 25, 'confirme', 'en_attente'),  -- Mathieu participe au trajet 1 (Lyon-Paris)
    (6, 1, '2026-06-20 11:30:00', 25, 'confirme', 'en_attente'),  -- Nadia participe au trajet 1
    (5, 2, '2026-06-21 09:00:00', 12, 'confirme', 'en_attente');  -- Mathieu participe au trajet 2 (Marseille-Montpellier)
