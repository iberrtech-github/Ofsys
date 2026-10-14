-- OFSYS Education - Database schema for the dynamic Placements & Gallery pages.
-- Import this file in phpMyAdmin (or run it with the mysql client) on a new server.

CREATE DATABASE IF NOT EXISTS `ofsy_db`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `ofsy_db`;

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(60) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `placements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `course` VARCHAR(180) NOT NULL DEFAULT '',
  `company` VARCHAR(180) NOT NULL DEFAULT '',
  `role` VARCHAR(180) NOT NULL DEFAULT '',
  `package` VARCHAR(60) NOT NULL DEFAULT '',
  `quote` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `sort_order` INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `gallery_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(180) NOT NULL DEFAULT '',
  `category_id` INT NULL,
  `image` VARCHAR(255) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_gallery_category` FOREIGN KEY (`category_id`)
    REFERENCES `gallery_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Starter gallery categories. Rename or delete them from the admin panel.
INSERT IGNORE INTO `gallery_categories` (`name`, `slug`, `sort_order`) VALUES
  ('Computer Labs', 'labs', 1),
  ('Campus Life', 'campus', 2),
  ('Seminars & Events', 'events', 3),
  ('Placements & Careers', 'placements', 4);

-- Default admin login: admin / Ofsys@2026  (change the password after the first login)
INSERT IGNORE INTO `admin_users` (`username`, `password_hash`) VALUES
  ('admin', '$2y$10$d5yXw8sXSEobFmhtsNCVQekdAmJqASK3oIPmvgRLh0jxsdZo8o9Ny');
