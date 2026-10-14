<?php
/**
 * OFSYS Admin - shared bootstrap: session, paths, database and form helpers.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// The admin lives one folder below the site root, so work the paths out here
// and define BASE_PATH before data.php gets a chance to guess it.
$adminPath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/admin/index.php')), '/');
$sitePath  = rtrim(dirname($adminPath), '/');
if ($sitePath === '.' || $sitePath === '/') {
    $sitePath = '';
}

define('ADMIN_PATH', $adminPath);
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $sitePath);
}

define('PROJECT_ROOT', dirname(__DIR__, 2));
define('UPLOAD_ROOT', PROJECT_ROOT . '/uploads');

require_once PROJECT_ROOT . '/includes/data.php';

/* --------------------------------------------------------------------------
   Authentication
   -------------------------------------------------------------------------- */

function admin_is_logged_in()
{
    return !empty($_SESSION['admin_id']);
}

/** Every admin page except the login screen calls this first. */
function admin_require_login()
{
    if (!admin_is_logged_in()) {
        header('Location: ' . ADMIN_PATH . '/login.php');
        exit;
    }
}

/* --------------------------------------------------------------------------
   CSRF tokens
   -------------------------------------------------------------------------- */

function admin_csrf_token()
{
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf'];
}

function admin_csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(admin_csrf_token()) . '">';
}

/** Stops a POST that did not come from one of our own forms. */
function admin_verify_csrf()
{
    $sent = $_POST['csrf_token'] ?? '';
    if (!is_string($sent) || !hash_equals(admin_csrf_token(), $sent)) {
        http_response_code(400);
        exit('Invalid form token. Please go back, reload the page and try again.');
    }
}

/* --------------------------------------------------------------------------
   Flash messages
   -------------------------------------------------------------------------- */

function admin_flash($message, $type = 'success')
{
    $_SESSION['admin_flash'] = ['message' => $message, 'type' => $type];
}

function admin_take_flash()
{
    if (empty($_SESSION['admin_flash'])) {
        return null;
    }
    $flash = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);

    return $flash;
}

/* --------------------------------------------------------------------------
   Image uploads
   -------------------------------------------------------------------------- */

/**
 * Validates and stores one uploaded image inside uploads/<folder>/.
 * Returns the stored path relative to the project root, or null on failure
 * (the reason is written into $error).
 */
function admin_store_image($field, $folder, &$error = null)
{
    $error = null;

    if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'The image could not be uploaded. It may be larger than the server limit.';
        return null;
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        $error = 'Please choose an image smaller than 5 MB.';
        return null;
    }

    $info = @getimagesize($file['tmp_name']);
    if ($info === false) {
        $error = 'That file is not a valid image.';
        return null;
    }

    $allowed = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG  => 'png',
        IMAGETYPE_GIF  => 'gif',
        IMAGETYPE_WEBP => 'webp',
    ];
    if (!isset($allowed[$info[2]])) {
        $error = 'Allowed image types are JPG, PNG, GIF and WEBP.';
        return null;
    }

    $dir = UPLOAD_ROOT . '/' . $folder;
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        $error = 'The upload folder could not be created.';
        return null;
    }

    $name = $folder . '-' . date('Ymd') . '-' . bin2hex(random_bytes(6)) . '.' . $allowed[$info[2]];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
        $error = 'The image could not be saved to the uploads folder.';
        return null;
    }

    return 'uploads/' . $folder . '/' . $name;
}

/** Deletes a previously uploaded file; external URLs are left alone. */
function admin_delete_image($path)
{
    $path = trim((string) $path);
    if ($path === '' || preg_match('#^(https?:)?//#i', $path)) {
        return;
    }

    $full = PROJECT_ROOT . '/' . ltrim($path, '/');
    if (strpos(realpath(dirname($full)) ?: '', realpath(UPLOAD_ROOT) ?: "\0") === 0 && is_file($full)) {
        @unlink($full);
    }
}

function admin_slugify($text)
{
    $text = strtolower(trim((string) $text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-');
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
