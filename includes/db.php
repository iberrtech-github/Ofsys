<?php
/**
 * OFSYS Education - Database connection (PDO).
 * Edit the four settings below when moving the site to live hosting.
 */

// 127.0.0.1 rather than 'localhost': on Windows the name resolves to ::1
// first, and MySQL usually listens only on IPv4, so every page paid for a
// failed IPv6 attempt before the real connection was made.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'ofsy_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Seconds to stop retrying after a failed connection. Short enough that the
// site picks the database back up on its own once MySQL is started again.
define('OFSY_DB_DOWN_TTL', 10);

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

    // While MySQL is down every page that needs it pays the full connect
    // timeout, so a failure is remembered for a few seconds and the pages
    // that follow render straight away from their fallbacks. The admin
    // defines OFSY_DB_MUST_CONNECT and always tries for real.
    $downFlag = sys_get_temp_dir() . '/ofsys-db-down';
    $skipWhileDown = !defined('OFSY_DB_MUST_CONNECT');
    if ($skipWhileDown && is_file($downFlag) && (time() - filemtime($downFlag)) < OFSY_DB_DOWN_TTL) {
        return null;
    }

    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                // A database that is down must not hold a page open for the
                // full system timeout; the pages render without it.
                PDO::ATTR_TIMEOUT            => 1,
            ]
        );
        // Reachable again: drop the marker so the cooldown ends at once.
        if (is_file($downFlag)) {
            @unlink($downFlag);
        }
    } catch (PDOException $e) {
        error_log('OFSYS DB connection failed: ' . $e->getMessage());
        @touch($downFlag);
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

/**
 * Every gallery category, used by the admin dropdown. Unlike
 * ofsy_get_gallery_categories() this one also returns the empty ones.
 */
function ofsy_get_gallery_categories_all()
{
    $db = ofsy_db();
    if (!$db) {
        return [];
    }

    try {
        return $db->query(
            'SELECT * FROM gallery_categories ORDER BY sort_order ASC, id ASC'
        )->fetchAll();
    } catch (PDOException $e) {
        error_log('OFSYS gallery category list failed: ' . $e->getMessage());
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

/** Active gallery videos, ordered the way the admin arranged them. */
function ofsy_get_gallery_videos()
{
    $db = ofsy_db();
    if (!$db) {
        return [];
    }

    try {
        return $db->query(
            'SELECT * FROM gallery_videos WHERE is_active = 1
              ORDER BY sort_order ASC, id DESC'
        )->fetchAll();
    } catch (PDOException $e) {
        // Also the path taken before db-upgrade-gallery.sql has been run: the
        // video gallery simply stays empty instead of breaking the page.
        error_log('OFSYS gallery video query failed: ' . $e->getMessage());
        return [];
    }
}

/**
 * Reads a pasted YouTube or Vimeo link and returns ['provider' => ..., 'id' => ...],
 * or null when the URL is not a video link we can embed. Shared by the admin
 * (which stores the result) and by the frontend fallback for older rows.
 */
function ofsy_parse_video_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }

    // youtu.be/<id>, youtube.com/watch?v=<id>, /embed/<id>, /shorts/<id>, /live/<id>
    if (preg_match('#youtu\.be/([A-Za-z0-9_-]{6,20})#i', $url, $m)
        || preg_match('#youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/|v/)([A-Za-z0-9_-]{6,20})#i', $url, $m)) {
        return ['provider' => 'youtube', 'id' => $m[1]];
    }

    // vimeo.com/<id>, vimeo.com/channels/x/<id>, player.vimeo.com/video/<id>
    if (preg_match('#vimeo\.com/(?:video/|channels/[A-Za-z0-9_-]+/|groups/[A-Za-z0-9_-]+/videos/)?(\d{6,12})#i', $url, $m)) {
        return ['provider' => 'vimeo', 'id' => $m[1]];
    }

    return null;
}

/** The privacy-friendly embed URL for one stored video row. */
function ofsy_video_embed_url(array $video)
{
    $id       = trim((string) ($video['video_id'] ?? ''));
    $provider = strtolower((string) ($video['provider'] ?? ''));

    // Rows saved before provider/video_id existed still hold the pasted link.
    if ($id === '' || $provider === '') {
        $parsed = ofsy_parse_video_url($video['video_url'] ?? '');
        if (!$parsed) {
            return '';
        }
        $provider = $parsed['provider'];
        $id       = $parsed['id'];
    }

    if ($provider === 'youtube') {
        return 'https://www.youtube-nocookie.com/embed/' . rawurlencode($id)
             . '?rel=0&autoplay=1';
    }
    if ($provider === 'vimeo') {
        return 'https://player.vimeo.com/video/' . rawurlencode($id) . '?autoplay=1';
    }

    return '';
}

/**
 * Poster image for one video: the thumbnail the admin uploaded or the one
 * fetched when it was saved, otherwise YouTube's own still. Returns '' when
 * there is nothing to show, and the frontend draws a plain play card.
 */
function ofsy_video_thumb(array $video)
{
    $thumb = trim((string) ($video['thumbnail'] ?? ''));
    if ($thumb !== '') {
        return ofsy_image_src($thumb);
    }

    $id       = trim((string) ($video['video_id'] ?? ''));
    $provider = strtolower((string) ($video['provider'] ?? ''));
    if ($id === '' || $provider === '') {
        $parsed = ofsy_parse_video_url($video['video_url'] ?? '');
        if ($parsed) {
            $provider = $parsed['provider'];
            $id       = $parsed['id'];
        }
    }

    if ($provider === 'youtube' && $id !== '') {
        return 'https://i.ytimg.com/vi/' . rawurlencode($id) . '/hqdefault.jpg';
    }

    return '';
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
