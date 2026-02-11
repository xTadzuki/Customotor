-- =====================================================
-- schema.sql - base de données customotor
-- =====================================================

SET NAMES utf8mb4;
SET time_zone = "+00:00";

-- -----------------------------------------------------
-- users : clients + admins
-- -----------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('client','admin') NOT NULL DEFAULT 'client',
    firstname VARCHAR(80) NOT NULL,
    lastname VARCHAR(80) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- contact_requests : devis / messages
-- -----------------------------------------------------
CREATE TABLE contact_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    firstname VARCHAR(80) NOT NULL,
    lastname VARCHAR(80) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(30) NULL,
    brand VARCHAR(80) NULL,
    model VARCHAR(80) NULL,
    year SMALLINT UNSIGNED NULL,
    message TEXT NOT NULL,
    status ENUM('new','in_progress','done') NOT NULL DEFAULT 'new',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_contact_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- projects : lookbook
-- -----------------------------------------------------
CREATE TABLE projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    subtitle VARCHAR(150) NULL,
    description TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- -----------------------------------------------------
-- vehicles : véhicules des clients 
-- -----------------------------------------------------
CREATE TABLE vehicles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  brand VARCHAR(80) NOT NULL,
  model VARCHAR(80) NOT NULL,
  year SMALLINT UNSIGNED NULL,
  plate VARCHAR(20) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_vehicle_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- service_categories : catégories de services
-- -----------------------------------------------------
CREATE TABLE service_categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL UNIQUE,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- services : services 
-- -----------------------------------------------------
CREATE TABLE services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  price_from DECIMAL(10,2) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  CONSTRAINT fk_service_category
    FOREIGN KEY (category_id) REFERENCES service_categories(id)
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- project_images : images lookbook (1 projet -> n images)
-- -----------------------------------------------------
CREATE TABLE project_images (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id INT UNSIGNED NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  alt_text VARCHAR(160) NULL,
  sort_order INT UNSIGNED NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_project_image
    FOREIGN KEY (project_id) REFERENCES projects(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- -----------------------------------------------------
-- appointments : rendez-vous
-- -----------------------------------------------------
CREATE TABLE appointments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    requested_at DATETIME NOT NULL,
    note TEXT NULL,
    status ENUM('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_appointment_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- reviews : avis clients
-- -----------------------------------------------------
CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_review_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;