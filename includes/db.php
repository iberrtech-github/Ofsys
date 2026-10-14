<?php
/**
 * OFSYS Education - Database connection (PDO).
 * Edit the four settings below when moving the site to live hosting.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'ofsy_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Shared PDO handle. Returns null when the database is unreachable so that the
 * public pages can still render instead of showing a fatal error.
 */
function ofsy_db()
{
    static $pdo = null;
    static $tried = false;

    if ($tried) {
        return $pdo;
    }
    $tried = true;

    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('OFSYS DB connection failed: ' . $e->getMessage());
        $pdo = null;
    }

    return $pdo;
}

/** Active placements, ordered the way the admin arranged them. */
function ofsy_get_placements()
{
    $db = ofsy_db();
    if (!$db) {
        return [];
    }

    try {
        return $db->query(
            'SELECT * FROM placements WHERE is_active = 1 ORDER BY sort_order ASC, id DESC'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('OFSYS placements query failed: ' . $e->getMessage());
        return [];
    }
}

/** Gallery categories that currently hold at least one active image. */
function ofsy_get_gallery_categories()
{
    $db = ofsy_db();
    if (!$db) {
        return [];
    }

    try {
        return $db->query(
            'SELECT c.*, COUNT(g.id) AS item_count
               FROM gallery_categories c
               JOIN gallery_items g ON g.category_id = c.id AND g.is_active = 1
              GROUP BY c.id
              ORDER BY c.sort_order ASC, c.id ASC'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('OFSYS gallery category query failed: ' . $e->getMessage());
        return [];
    }
}

/** Active gallery images with their category slug and name. */
function ofsy_get_gallery_items()
{
    $db = ofsy_db();
    if (!$db) {
        return [];
    }

    try {
        return $db->query(
            'SELECT g.*, c.slug AS category_slug, c.name AS category_name
               FROM gallery_items g
          LEFT JOIN gallery_categories c ON c.id = g.category_id
              WHERE g.is_active = 1
              ORDER BY g.sort_order ASC, g.id DESC'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('OFSYS gallery query failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Turns a stored image value into a usable src: uploaded files are relative to
 * the project root, while older entries may already hold a full URL.
 */
function ofsy_image_src($image)
{
    $image = trim((string) $image);
    if ($image === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $image)) {
        return $image;
    }

    return BASE_PATH . '/' . ltrim($image, '/');
}
