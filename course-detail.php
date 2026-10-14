<?php
/**
 * OFSYS Education - Legacy course detail entry point.
 *
 * Every course now has its own page under /course-pages/<slug>.php, served at
 * the clean URL /courses/<slug>. This file only keeps old links working.
 */
require_once __DIR__ . '/includes/data.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

if ($slug !== '' && isset($courses[$slug])) {
    header('Location: ' . BASE_PATH . '/courses/' . $slug, true, 301);
    exit;
}

header('Location: ' . BASE_PATH . '/courses', true, 302);
exit;
