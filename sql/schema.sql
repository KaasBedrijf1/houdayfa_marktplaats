-- =============================================================================
-- HOUDAYFA MARKTPLAATS — volledig schema
-- =============================================================================
-- Import op een EIGEN database, aanbevolen: marktmaroc (zie config.php → DB_NAME).
--
-- FOUT #1005 / Errcode 150 ("Foreign key incorrectly formed")?
-- • Je moet categories → users → listings IN DEZELFDE database uitvoeren.
-- • Database `marketplace` (winkelwagen-demo) heeft GEEN tabel `users` — alleen
--   `accounts`. Plak daar niet alleen het CREATE listings-blok.
-- • Oplossing: maak marktmaroc aan en importeer dit HELE bestand, of voer in
--   jouw gekozen DB eerst CREATE categories + CREATE users uit vóór listings.
-- =============================================================================
--
-- Maak database: CREATE DATABASE marktmaroc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Import: mysql -u root marktmaroc < sql/schema.sql
-- phpMyAdmin: database marktmaroc selecteren → Importeren → dit bestand

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  slug VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(160) NOT NULL,
  display_name VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email),
  INDEX idx_users_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS listings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  category_id INT UNSIGNED NOT NULL,
  title VARCHAR(160) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  city VARCHAR(100) NOT NULL DEFAULT '',
  seller_name VARCHAR(100) NOT NULL,
  seller_email VARCHAR(160) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  moderated_at DATETIME NULL DEFAULT NULL,
  moderated_by INT UNSIGNED NULL DEFAULT NULL,
  reject_reason VARCHAR(500) NULL DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_cat_created (category_id, created_at),
  INDEX idx_listings_status_created (status, created_at),
  INDEX idx_listings_user (user_id),
  FULLTEXT KEY ft_search (title, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO categories (name, slug) VALUES
  ('Auto''s', 'autos'),
  ('Elektronica', 'elektronica'),
  ('Huis & Inrichting', 'huis-inrichting'),
  ('Kleding', 'kleding'),
  ('Sport & Hobby', 'sport-hobby'),
  ('Zakelijke goederen', 'zakelijk');

-- Standaard beheerder — wachtwoord: AdminWijzigDit123 (direct wijzigen!)
INSERT INTO users (email, display_name, password_hash, role) VALUES
  ('admin@localhost', 'Beheerder', '$2y$10$6XE.Y/dnJcXM4WvhBXPKXe8nvXjxGhPsMQ6C5yUAwC1apLvQoA/4a', 'admin')
ON DUPLICATE KEY UPDATE email = email;

-- Demo-producten: sql/seed_demo_products.sql
