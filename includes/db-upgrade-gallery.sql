-- OFSYS Education - Gallery upgrade: photo details + the new videos gallery.
--
-- Run this ONCE on a database that was created with an older copy of
-- db-schema.sql (import it in phpMyAdmin, or let the admin panel apply it with
-- the "Upgrade database" button on the Gallery pages).
--
-- It is safe to run again: every step checks first, so nothing is duplicated
-- and no existing photo, placement or category is touched.

-- --------------------------------------------------------------- gallery_items
-- Adds the alt text and thumbnail columns the gallery admin now manages.
-- Written as prepared statements because `ADD COLUMN IF NOT EXISTS` works on
-- MariaDB but not on MySQL 5.7/8, and the live host may be either.

SET @add_alt = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'gallery_items'
       AND COLUMN_NAME = 'alt_text') = 0,
  'ALTER TABLE `gallery_items` ADD COLUMN `alt_text` VARCHAR(255) NOT NULL DEFAULT '''' AFTER `title`',
  'DO 0'));
PREPARE stmt FROM @add_alt; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @add_thumb = (SELECT IF(
  (SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'gallery_items'
       AND COLUMN_NAME = 'thumbnail') = 0,
  'ALTER TABLE `gallery_items` ADD COLUMN `thumbnail` VARCHAR(255) NULL AFTER `image`',
  'DO 0'));
PREPARE stmt FROM @add_thumb; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- -------------------------------------------------------------- gallery_videos
-- YouTube / Vimeo links shown in the video gallery. `provider` and `video_id`
-- are worked out from the pasted URL when the video is saved, so the frontend
-- never has to parse a URL while rendering.

CREATE TABLE IF NOT EXISTS `gallery_videos` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(180) NOT NULL DEFAULT '',
  `video_url` VARCHAR(500) NOT NULL,
  `provider` VARCHAR(20) NOT NULL DEFAULT 'youtube',
  `video_id` VARCHAR(80) NOT NULL DEFAULT '',
  `thumbnail` VARCHAR(500) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
