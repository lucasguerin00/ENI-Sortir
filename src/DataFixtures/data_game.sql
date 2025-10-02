-- ========================
-- TABLE : VILLE
-- ========================
TRUNCATE  TABLE sortie_participant;
TRUNCATE  TABLE sortie;
TRUNCATE  TABLE lieu;
TRUNCATE  TABLE ville;
TRUNCATE  TABLE etat;
TRUNCATE  TABLE participant;
TRUNCATE  TABLE site;

-- ========================
-- TABLE : VILLE
-- ========================
INSERT INTO ville (id, nom, code_postal) VALUES
(1, 'Saint-Herblain', '44800'),
(2, 'Rennes', '35000'),
(3, 'Niort', '79000'),
(4, 'Laval', '53000'),
(5, 'Nantes', '44000');

-- ========================
-- TABLE : LIEU
-- ========================
INSERT INTO lieu (id, nom, rue, latitude, longitude, id_ville_id) VALUES
-- Saint-Herblain
(1, 'Zénith de Nantes Métropole', 'Boulevard du Zénith', 47.2175, -1.6514, 1),
(2, 'Atlantis Le Centre', 'Boulevard Salvador Allende', 47.2212, -1.6305, 1),

-- Rennes
(3, 'Parc du Thabor', 'Place Saint-Mélaine', 48.119, -1.671, 2),
(4, 'Roazhon Park', '111 Rue de Lorient', 48.1033, -1.7125, 2),

-- Niort
(5, 'Donjon de Niort', 'Place du Donjon', 46.3246, -0.4631, 3),
(6, 'Stade René Gaillard', '105 Avenue de la Venise Verte', 46.3327, -0.5062, 3),

-- Laval
(7, 'Château de Laval', 'Place de la Trémoille', 48.0717, -0.7716, 4),
(8, 'Stade Francis Le Basser', 'Rue Paul Lintier', 48.0831, -0.7603, 4),

-- Nantes
(9, 'Stade de la Beaujoire', 'Avenue de la gare Saint Joseph', 46.3246, -0.4631, 5);

-- ========================
-- TABLE : SITE
-- ========================
INSERT INTO site (id, nom) VALUES
(1, 'Site Saint-Herblain'),
(2, 'Site Rennes'),
(3, 'Site Niort'),
(4, 'Site Laval'),
(5, 'Site Nantes');

-- ========================
-- TABLE : ETAT
-- ========================
INSERT INTO etat (id, libelle) VALUES
(1, 'Ouvert'),
(2, 'En cours'),
(3, 'En création'),
(4, 'Fermée'),
(5, 'Terminée');

-- ========================
-- TABLE : PARTICIPANT
-- ========================
INSERT INTO participant (id, mail, roles, password, nom, prenom, telephone, is_administrateur, is_actif, id_site_id, pseudo) VALUES
(11, 'jean.bon@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Bon', 'Jean', '0611111111', false, true, 1, 'JeanBon'),
(12, 'alain.terior@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Térieur', 'Alain', '0622222222', false, true, 1, 'Intérieur44'),
(13, 'claire.ring@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Ring', 'Claire', '0633333333', false, true, 2, 'ClairObscur35'),
(4, 'marc.assin@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Assin', 'Marc', '0644444444', false, true, 2, 'Marcassin35'),
(1, 'anna.gramme@gmail.com', '["ADMIN"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Gramme', 'Anna', '0644444444', true, true, 1, 'Anagramme'),
(2, 'luc.belle@gmail.com', '["ADMIN"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Belle', 'Luc', '0644444444', true, true, 1, 'LucBelle'),
(3, 'will.o.sophe@gmail.com', '["ADMIN"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Sophe', 'Will O.', '0644444444', true, true, 1, 'PhiloWill'),
(5, 'syl.vain@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Vain', 'Syl', '0655555555', false, true, 3, 'Sylvain79'),
(6, 'guy.tar@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Tar', 'Guy', '0666666666', false, true, 3, 'Guitare79'),
(7, 'ella.fontaine@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Fontaine', 'Ella', '0677777777', false, true, 4, 'EauClaire53'),
(8, 'pierre.tombal@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Tombal', 'Pierre', '0688888888', false, true, 4, 'PierreGrave53'),
(9, 'sal.lemandre@mail.com', '["ROLE_ADMIN"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Lemandre', 'Sal', '0699999999', true, true, 2, 'SelEtPoivre35'),
(10,'luc.ifer@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C','Ifer', 'Luc', '0600000000', false, false, 1, 'Diable44');

-- ========================
-- TABLE : SORTIE
-- ========================
INSERT INTO sortie (id, nom, date_heure_debut, duree, date_limite_inscription, nb_inscription_max, infos_sortie, id_site_id, id_lieu_id, etat_id, id_organisateur_id, is_archived, archived_at) VALUES
(1, 'Concert au Zénith', '2025-06-20 20:00:00', 180, '2025-06-15 23:59:59', 100, 'Concert rock à Saint-Herblain', 1, 1, 5, 1, false, NULL), -- Terminée mais non archivée
(2, 'Shopping Atlantis', '2025-05-12 14:00:00', 120, '2025-05-10 23:59:59', 30, 'Après-midi shopping au centre Atlantis', 1, 2, 5, 2, false, NULL), -- Terminée mais non archivée
(3, 'Balade au Thabor', '2025-05-18 10:00:00', 90, '2025-05-15 23:59:59', 20, 'Promenade printanière', 2, 3, 5, 3, false, NULL), -- Terminée mais non archivée
(4, 'Match au Roazhon Park', '2025-07-05 21:00:00', 120, '2025-07-01 23:59:59', 50, 'Match de Ligue 1', 2, 4, 5, 4, true, '2025-07-06 00:00:00'), -- Terminée et archivée (exemple)
(5, 'Visite du Donjon', '2025-06-10 15:00:00', 60, '2025-06-08 23:59:59', 15, 'Découverte du patrimoine historique', 3, 5, 5, 5, false, NULL), -- Terminée mais non archivée
(6, 'Foot au Stade René Gaillard', '2025-06-25 19:00:00', 90, '2025-06-20 23:59:59', 22, 'Petit match amical entre amis', 3, 6, 5, 6, false, NULL), -- Terminée mais non archivée
(7, 'Visite du Château de Laval', '2025-07-12 14:00:00', 120, '2025-07-08 23:59:59', 25, 'Visite guidée du château', 4, 7, 4, 7, false, NULL), -- Fermée
(8, 'Match au Stade Francis Le Basser', '2025-08-01 20:00:00', 120, '2025-07-25 23:59:59', 40, 'Match de Ligue 2', 4, 8, 2, 8, false, NULL), -- Ouvert
(9, 'Match au Stade de la Beaujoire', '2025-09-20 17:00:00', 120, '2025-09-01 23:59:59', 40, 'Match de Ligue 1 Nantes-Rennes', 5, 9, 1, 2, false, NULL), -- Ouvert
(10, 'Soirée jeux de société', '2025-10-15 18:30:00', 180, '2025-10-10 23:59:59', 12, 'Soirée conviviale avec jeux de société', 1, 1, 1, 1, false, NULL), -- Ouvert
(11, 'Randonnée en forêt', '2025-10-22 09:00:00', 240, '2025-10-18 23:59:59', 15, 'Randonnée en forêt de Rennes', 2, 3, 1, 3, false, NULL), -- Ouvert
(12, 'Atelier cuisine', '2025-11-05 17:00:00', 180, '2025-10-30 23:59:59', 8, 'Atelier cuisine italienne', 3, 5, 1, 5, false, NULL), -- Ouvert
(13, 'Concert jazz', '2025-11-10 20:30:00', 120, '2025-11-05 23:59:59', 30, 'Concert jazz au Zénith', 1, 1, 1, 1, false, NULL), -- Ouvert
(14, 'Tournoi de tennis', '2025-11-20 14:00:00', 240, '2025-11-15 23:59:59', 16, 'Tournoi amical de tennis', 4, 8, 1, 7, false, NULL), -- Ouvert
(15, 'Soirée cinéma', '2025-12-05 20:00:00', 180, '2025-11-30 23:59:59', 20, 'Projection de films indépendants', 2, 3, 1, 3, false, NULL), -- Ouvert
(16, 'Festival des lumières', '2025-12-20 18:00:00', 360, '2025-12-15 23:59:59', 100, 'Festival hivernal avec illuminations', 5, 9, 3, 2, false, NULL); -- En création

-- ========================
-- TABLE : SORTIE_PARTICIPANT (ManyToMany)
-- ========================
INSERT INTO sortie_participant (sortie_id, participant_id) VALUES
-- Concert au Zénith
(1, 1), (1, 2), (1, 3), (1, 4),

-- Shopping Atlantis
(2, 1), (2, 10),

-- Balade au Thabor
(3, 3), (3, 4), (3, 9),

-- Match Roazhon Park
(4, 3), (4, 4), (4, 9), (4, 2),

-- Visite du Donjon
(5, 5), (5, 6),

-- Foot René Gaillard
(6, 5), (6, 6), (6, 2), (6, 8),

-- Château de Laval
(7, 7), (7, 8), (7, 3),

-- Match Laval
(8, 7), (8, 8), (8, 4), (8, 6);
