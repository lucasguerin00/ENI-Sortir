-- ========================
-- TABLE : VILLE
-- ========================
INSERT INTO ville (id, nom, code_postal) VALUES
 (1, 'Saint-Herblain', '44800'),
 (2, 'Rennes', '35000'),
 (3, 'Niort', '79000'),
 (4, 'Laval', '53000');

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
(8, 'Stade Francis Le Basser', 'Rue Paul Lintier', 48.0831, -0.7603, 4);

-- ========================
-- TABLE : SITE
-- ========================
INSERT INTO site (id, nom) VALUES
(1, 'Site Saint-Herblain'),
(2, 'Site Rennes'),
(3, 'Site Niort'),
(4, 'Site Laval');

-- ========================
-- TABLE : ETAT
-- ========================
INSERT INTO etat (id, libelle) VALUES
(1, 'Créée'),
(2, 'Ouverte'),
(3, 'Clôturée'),
(4, 'Activité en cours'),
(5, 'Terminée'),
(6, 'Annulée'),
(7, 'Archivée');

-- ========================
-- TABLE : PARTICIPANT
-- ========================
INSERT INTO participant (id, mail, roles, password, nom, prenom, telephone, is_administrateur, is_actif, id_site_id, pseudo) VALUES
(11, 'alice.herblain@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Dupont', 'Alice', '0611111111', false, true, 1, 'Ali44'),
(12, 'benoit.herblain@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Martin', 'Benoît', '0622222222', false, true, 1, 'Ben44'),
(13, 'chloe.rennes@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Durand', 'Chloé', '0633333333', false, true, 2, 'Chlo35'),
(4, 'david.rennes@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Lemoine', 'David', '0644444444', false, true, 2, 'Dave35'),
(5, 'emma.niort@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Bernard', 'Emma', '0655555555', false, true, 3, 'Emma79'),
(6, 'francois.niort@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Moreau', 'François', '0666666666', false, true, 3, 'Fran79'),
(7, 'isabelle.laval@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Petit', 'Isabelle', '0677777777', false, true, 4, 'Isa53'),
(8, 'julien.laval@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Dubois', 'Julien', '0688888888', false, true, 4, 'Juju53'),
(9, 'karim.rennes@mail.com', '["ROLE_ADMIN"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C', 'Ahmed', 'Karim', '0699999999', true, true, 2, 'Admin35'),
(10,'luc.herblain@mail.com', '["ROLE_USER"]', '$2y$13$0WF4KSBahl.MreCj.ihXIe8USt/j1X3ArmoafV4bR1WafWZ5Mbw0C','Lucas', 'Luc', '0600000000', false, false, 1, 'Luc44');

-- ========================
-- TABLE : SORTIE
-- ========================
INSERT INTO sortie (id, nom, date_heure_debut, duree, date_limite_inscription, nb_inscription_max, infos_sortie, id_site_id, id_lieu_id, etat_id, id_organisateur_id, is_archived, archived_at) VALUES
(1, 'Concert au Zénith', '2025-06-20 20:00:00', 180, '2025-06-15 23:59:59', 100, 'Concert rock à Saint-Herblain', 1, 1, 2, 1, false, NULL),
(2, 'Shopping Atlantis', '2025-05-12 14:00:00', 120, '2025-05-10 23:59:59', 30, 'Après-midi shopping au centre Atlantis', 1, 2, 1, 2, false, NULL),
(3, 'Balade au Thabor', '2025-05-18 10:00:00', 90, '2025-05-15 23:59:59', 20, 'Promenade printanière', 2, 3, 2, 3, false, NULL),
(4, 'Match au Roazhon Park', '2025-07-05 21:00:00', 120, '2025-07-01 23:59:59', 50, 'Match de Ligue 1', 2, 4, 2, 4, false, NULL),
(5, 'Visite du Donjon', '2025-06-10 15:00:00', 60, '2025-06-08 23:59:59', 15, 'Découverte du patrimoine historique', 3, 5, 1, 5, false, NULL),
(6, 'Foot au Stade René Gaillard', '2025-06-25 19:00:00', 90, '2025-06-20 23:59:59', 22, 'Petit match amical entre amis', 3, 6, 2, 6, false, NULL),
(7, 'Visite du Château de Laval', '2025-07-12 14:00:00', 120, '2025-07-08 23:59:59', 25, 'Visite guidée du château', 4, 7, 2, 7, false, NULL),
(8, 'Match au Stade Francis Le Basser', '2025-08-01 20:00:00', 120, '2025-07-25 23:59:59', 40, 'Match de Ligue 2', 4, 8, 2, 8, false, NULL);

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
