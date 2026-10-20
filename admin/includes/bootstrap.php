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

// The admin cannot fall back to static content, so it always waits for a
// real connection attempt instead of the public pages' down-cooldown.
define('OFSY_DB_MUST_CONNECT', true);

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

// Nothing on the site displays an uploaded image wider than this.
define('ADMIN_IMAGE_MAX_WIDTH', 1280);
define('ADMIN_IMAGE_QUALITY', 82);

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

    // A photo straight off a phone or a stock site can be several megabytes
    // and thousands of pixels wide, while the gallery and placement cards
    // never show one wider than ~700px. Shrink it once, here, so visitors
    // are never made to download the original.
    admin_shrink_image($dir . '/' . $name, $info[2]);

    return 'uploads/' . $folder . '/' . $name;
}

/**
 * Resizes a stored upload down to ADMIN_IMAGE_MAX_WIDTH and re-encodes it at a
 * sane quality. Failures are ignored on purpose: a slightly heavy image is a
 * far better outcome than a failed save, and GD may be missing a codec.
 */
function admin_shrink_image($path, $type, $maxWidth = ADMIN_IMAGE_MAX_WIDTH)
{
    if (!function_exists('imagecreatetruecolor')) {
        return;
    }

    $loaders = [
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG  => 'imagecreatefrompng',
        IMAGETYPE_GIF  => 'imagecreatefromgif',
        IMAGETYPE_WEBP => 'imagecreatefromwebp',
    ];
    if (!isset($loaders[$type]) || !function_exists($loaders[$type])) {
        return;
    }

    $src = @$loaders[$type]($path);
    if (!$src) {
        return;
    }

    $width  = imagesx($src);
    $height = imagesy($src);

    if ($width > $maxWidth) {
        $newHeight = (int) round($height * $maxWidth / $width);
        $resized   = imagecreatetruecolor($maxWidth, $newHeight);
        // Keep PNG/GIF/WEBP transparency through the resample.
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $src, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
        imagedestroy($src);
        $src = $resized;
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            @imagejpeg($src, $path, ADMIN_IMAGE_QUALITY);
            break;
        case IMAGETYPE_WEBP:
            @imagewebp($src, $path, ADMIN_IMAGE_QUALITY);
            break;
        case IMAGETYPE_PNG:
            // A photo saved as PNG stays huge however hard it is compressed,
            // so anything without transparency is reduced to a palette.
            if (!admin_png_has_alpha($src)) {
                @imagetruecolortopalette($src, true, 255);
            }
            @imagepng($src, $path, 9);
            break;
        case IMAGETYPE_GIF:
            @imagegif($src, $path);
            break;
    }

    imagedestroy($src);
    clearstatcache(true, $path);
}

/** True when any pixel of the image is not fully opaque. */
function admin_png_has_alpha($img)
{
    $w = imagesx($img);
    $h = imagesy($img);
    // Sampling a grid is enough: a transparent PNG is transparent in patches,
    // not in single stray pixels, and this keeps large uploads quick to check.
    $stepX = max(1, (int) ($w / 60));
    $stepY = max(1, (int) ($h / 60));

    for ($x = 0; $x < $w; $x += $stepX) {
        for ($y = 0; $y < $h; $y += $stepY) {
            if (((imagecolorat($img, $x, $y) >> 24) & 0x7F) > 0) {
                return true;
            }
        }
    }

    return false;
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
