-- =====================================================
-- seed.sql - données initiales customotor
-- =====================================================

-- -----------------------------------------------------
-- admin
-- mot de passe : admin.1234!
-- -----------------------------------------------------
INSERT INTO users (role, firstname, lastname, email, password_hash)
VALUES
('admin', 'admin', 'customotor', 'admin@customotor.local',
 '$2y$10$3h.ZB.3zKRKuDeiLS7P9QO1IDEvzPrx0wLdtVOQtsWi4nQH7cJygy');

-- -----------------------------------------------------
-- utilisateurs clients 
-- mot de passe : password.123!
-- -----------------------------------------------------
INSERT INTO users (role, firstname, lastname, email, password_hash)
VALUES
('client', 'alex', 'dupont', 'alex@test.local',
 '$2y$10$X70muLRm2mJWrzuhp5aaju3ylH0bO3tVXMgVZPv.NhTJEv1bFPcEW'),
('client', 'luc', 'debry', 'luc@test.local',
 '$2y$10$X70muLRm2mJWrzuhp5aaju3ylH0bO3tVXMgVZPv.NhTJEv1bFPcEW');

-- -----------------------------------------------------
-- lookbook (projets)
-- -----------------------------------------------------
INSERT INTO projects (title, subtitle, description) VALUES
('Ferrari F8 Tributo', 'stage 1 — optimisation couple',
 'Optimisation moteur orientée reprises et agrément de conduite.'),
('Ford Mustang S550', 'stage 2 — préparation sur mesure',
 'Préparation complète avec réglages personnalisés et validation.'),
('Nissan 350Z', 'stage 2 — préparation sur mesure',
 'Préparation complète avec réglages personnalisés et validation.'),
('Ford Mustang Mach 1', 'préparation performance',
 'Configuration orientée performance avec tests et contrôles.');

-- -----------------------------------------------------
-- images lookbook
-- -----------------------------------------------------
INSERT INTO project_images (project_id, image_path, alt_text, sort_order) VALUES
-- Projet 1 (Ferrari F8 Tributo)
(1, '/assets/uploads/lookbook/p1-01.jpg', 'Ferrari F8 Tributo — vue 1', 1),
(1, '/assets/uploads/lookbook/p1-02.jpg', 'Ferrari F8 Tributo — vue 2', 2),
(1, '/assets/uploads/lookbook/p1-03.jpg', 'Ferrari F8 Tributo — vue 3', 3),
(1, '/assets/uploads/lookbook/p1-04.jpg', 'Ferrari F8 Tributo — vue 4', 4),
(1, '/assets/uploads/lookbook/p1-05.jpg', 'Ferrari F8 Tributo — vue 5', 5),
(1, '/assets/uploads/lookbook/p1-06.jpg', 'Ferrari F8 Tributo — vue 6', 6),

-- Projet 2 (Mustang S550)
(2, '/assets/uploads/lookbook/p2-01.jpg', 'Ford Mustang S550 — vue 1', 1),
(2, '/assets/uploads/lookbook/p2-02.jpg', 'Ford Mustang S550 — vue 2', 2),
(2, '/assets/uploads/lookbook/p2-03.jpg', 'Ford Mustang S550 — vue 3', 3),
(2, '/assets/uploads/lookbook/p2-04.jpg', 'Ford Mustang S550 — vue 4', 4),
(2, '/assets/uploads/lookbook/p2-05.jpg', 'Ford Mustang S550 — vue 5', 5),
(2, '/assets/uploads/lookbook/p2-06.jpg', 'Ford Mustang S550 — vue 6', 6),

-- Projet 3 (Nissan 350Z)
(3, '/assets/uploads/lookbook/p3-01.jpg', 'Nissan 350Z — vue 1', 1),
(3, '/assets/uploads/lookbook/p3-02.jpg', 'Nissan 350Z — vue 2', 2),
(3, '/assets/uploads/lookbook/p3-03.jpg', 'Nissan 350Z — vue 3', 3),
(3, '/assets/uploads/lookbook/p3-04.jpg', 'Nissan 350Z — vue 4', 4),
(3, '/assets/uploads/lookbook/p3-05.jpg', 'Nissan 350Z — vue 5', 5),
(3, '/assets/uploads/lookbook/p3-06.jpg', 'Nissan 350Z — vue 6', 6),

-- Projet 4 (Mustang Mach 1)
(4, '/assets/uploads/lookbook/p4-01.jpg', 'Ford Mustang Mach 1 — vue 1', 1),
(4, '/assets/uploads/lookbook/p4-02.jpg', 'Ford Mustang Mach 1 — vue 2', 2),
(4, '/assets/uploads/lookbook/p4-03.jpg', 'Ford Mustang Mach 1 — vue 3', 3),
(4, '/assets/uploads/lookbook/p4-04.jpg', 'Ford Mustang Mach 1 — vue 4', 4),
(4, '/assets/uploads/lookbook/p4-05.jpg', 'Ford Mustang Mach 1 — vue 5', 5),
(4, '/assets/uploads/lookbook/p4-06.jpg', 'Ford Mustang Mach 1 — vue 6', 6);

-- -----------------------------------------------------
-- catégories services
-- -----------------------------------------------------
INSERT INTO service_categories (name, sort_order) VALUES
('optimisation moteur', 1),
('diagnostic & fiabilité', 2),
('châssis & freinage', 3);

-- -----------------------------------------------------
-- services
-- -----------------------------------------------------
INSERT INTO services (category_id, name, description, price_from, sort_order) VALUES
(1, 'stage 1', 'optimisation moteur sans modification mécanique', 490, 1),
(1, 'stage 2', 'optimisation avec pièces spécifiques', 890, 2),
(2, 'diagnostic complet', 'lecture défauts et contrôles moteur', 80, 1),
(3, 'upgrade freinage', 'plaquettes, disques, liquide haute performance', 350, 1);
