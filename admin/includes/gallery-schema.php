<?php
/**
 * OFSYS Admin - makes sure the gallery tables carry the columns the Photos and
 * Videos pages need.
 *
 * A site whose database was imported from an older db-schema.sql has no
 * alt_text, thumbnail or gallery_videos yet. Rather than let a query fail, the
 * gallery pages ask here what is missing, show the admin a short notice and
 * offer to apply db-upgrade-gallery.sql in one click.
 */

/** Names of the gallery columns/tables that are not in the database yet. */
function admin_gallery_schema_missing(PDO $db)
{
    $missing = [];

    try {
        $stmt = $db->prepare(
            'SELECT COLUMN_NAME FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
        );

        $stmt->execute(['gallery_items']);
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!$columns) {
            // No gallery_items at all: the schema was never imported.
            return ['gallery_items'];
        }
        foreach (['alt_text', 'thumbnail'] as $column) {
            if (!in_array($column, $columns, true)) {
                $missing[] = 'gallery_items.' . $column;
            }
        }

        $stmt->execute(['gallery_videos']);
        if (!$stmt->fetchAll(PDO::FETCH_COLUMN)) {
            $missing[] = 'gallery_videos';
        }
    } catch (PDOException $e) {
        error_log('OFSYS gallery schema check failed: ' . $e->getMessage());
        return [];
    }

    return $missing;
}

/**
 * Adds whatever admin_gallery_schema_missing() reported. Only ever adds:
 * no existing column, row or table is changed or dropped.
 */
function admin_gallery_schema_upgrade(PDO $db, &$error = null)
{
    $error = null;
    $missing = admin_gallery_schema_missing($db);

    if (in_array('gallery_items', $missing, true)) {
        $error = 'The gallery tables are missing entirely. Import includes/db-schema.sql in phpMyAdmin first.';
        return false;
    }

    $steps = [
        'gallery_items.alt_text'  => "ALTER TABLE `gallery_items`
             ADD COLUMN `alt_text` VARCHAR(255) NOT NULL DEFAULT '' AFTER `title`",
        'gallery_items.thumbnail' => 'ALTER TABLE `gallery_items`
             ADD COLUMN `thumbnail` VARCHAR(255) NULL AFTER `image`',
        'gallery_videos'          => "CREATE TABLE IF NOT EXISTS `gallery_videos` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `title` VARCHAR(180) NOT NULL DEFAULT '',
              `video_url` VARCHAR(500) NOT NULL,
              `provider` VARCHAR(20) NOT NULL DEFAULT 'youtube',
              `video_id` VARCHAR(80) NOT NULL DEFAULT '',
              `thumbnail` VARCHAR(500) NULL,
              `sort_order` INT NOT NULL DEFAULT 0,
              `is_active` TINYINT(1) NOT NULL DEFAULT 1,
              `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    ];

    try {
        foreach ($steps as $name => $sql) {
            if (in_array($name, $missing, true)) {
                $db->exec($sql);
            }
        }
    } catch (PDOException $e) {
        error_log('OFSYS gallery schema upgrade failed: ' . $e->getMessage());
        $error = 'The database could not be upgraded automatically. Import includes/db-upgrade-gallery.sql in phpMyAdmin instead.';
        return false;
    }

    return true;
}

/** The notice shown above a gallery page while the upgrade is still needed. */
function admin_gallery_schema_notice(array $missing, $self)
{
    if (!$missing) {
        return '';
    }

    $names = '<strong>' . implode('</strong>, <strong>', array_map('e', $missing)) . '</strong>';

    $html  = '<div class="ad-alert ad-alert-error">'
           . '<i class="fa-solid fa-circle-exclamation"></i><span>'
           . 'This database is missing ' . $names . ', '
           . 'which the gallery needs. Click <strong>Upgrade database</strong> to add it '
           . '(nothing already saved is changed), or import '
           . '<strong>includes/db-upgrade-gallery.sql</strong> in phpMyAdmin.'
           . '</span></div>';

    $html .= '<form method="POST" action="' . e($self) . '" class="ad-card">'
           . admin_csrf_field()
           . '<input type="hidden" name="do" value="upgrade-schema">'
           . '<button type="submit" class="ad-btn ad-btn-primary">'
           . '<i class="fa-solid fa-database"></i> Upgrade database</button>'
           . '</form>';

    return $html;
}
