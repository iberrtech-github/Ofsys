<?php
/**
 * OFSYS Admin - ends the session and returns to the login screen.
 */
require_once __DIR__ . '/includes/bootstrap.php';

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}
session_destroy();

header('Location: ' . ADMIN_PATH . '/login.php');
exit;
