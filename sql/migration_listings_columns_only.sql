-- Alleen ontbrekende kolommen op `listings` + tabel `users` (zonder foreign keys).
-- Gebruik dit als migration_auth_moderation.sql faalt op FOREIGN KEY, of als je
-- fout #1054 krijgt bij seed_demo_products.sql.
--
-- Volgorde: 1) dit bestand  2) daarna seed_demo_products.sql
-- Pas FK later handmatig toe via migration_auth_moderation.sql (alleen de
-- ALTER TABLE ... ADD CONSTRAINT regels) als je dat wilt.

SET NAMES utf8mb4;

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

-- Als een kolom al bestaat, krijg je een fout op die regel — die ene regel overslaan.
ALTER TABLE listings
  ADD COLUMN user_id INT UNSIGNED NULL DEFAULT NULL AFTER id;

ALTER TABLE listings
  ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'approved' AFTER image;

ALTER TABLE listings
  ADD COLUMN moderated_at DATETIME NULL DEFAULT NULL AFTER status;

ALTER TABLE listings
  ADD COLUMN moderated_by INT UNSIGNED NULL DEFAULT NULL AFTER moderated_at;

ALTER TABLE listings
  ADD COLUMN reject_reason VARCHAR(500) NULL DEFAULT NULL AFTER moderated_by;
