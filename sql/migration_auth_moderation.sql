-- Houdayfa Marktplaats — accounts + moderatie (uitvoeren op bestaande DB marktmaroc)
-- Voegt gebruikers toe, koppelt advertenties, alle bestaande advertenties blijven zichtbaar (approved).

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

-- Kolommen op listings (negeer fout als ze al bestaan — dan handmatig controleren)
ALTER TABLE listings
  ADD COLUMN user_id INT UNSIGNED NULL DEFAULT NULL AFTER id,
  ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'approved' AFTER image,
  ADD COLUMN moderated_at DATETIME NULL DEFAULT NULL AFTER status,
  ADD COLUMN moderated_by INT UNSIGNED NULL DEFAULT NULL AFTER moderated_at,
  ADD COLUMN reject_reason VARCHAR(500) NULL DEFAULT NULL AFTER moderated_by;

-- Indexen + FK (kan falen als al aanwezig)
ALTER TABLE listings
  ADD INDEX idx_listings_status_created (status, created_at),
  ADD INDEX idx_listings_user (user_id);

ALTER TABLE listings
  ADD CONSTRAINT fk_listings_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;

ALTER TABLE listings
  ADD CONSTRAINT fk_listings_moderator FOREIGN KEY (moderated_by) REFERENCES users(id) ON DELETE SET NULL;

-- Bestaande rijen expliciet goedkeuren (voor het geval default anders was)
UPDATE listings SET status = 'approved' WHERE status IS NOT NULL;

-- Standaard-admin (wachtwoord: AdminWijzigDit123) — DIRECT WIJZIGEN na eerste login!
INSERT INTO users (email, display_name, password_hash, role) VALUES
  ('admin@localhost', 'Beheerder', '$2y$10$6XE.Y/dnJcXM4WvhBXPKXe8nvXjxGhPsMQ6C5yUAwC1apLvQoA/4a', 'admin')
ON DUPLICATE KEY UPDATE email = email;
