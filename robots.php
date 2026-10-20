<?php
/**
 * OFSYS Education - robots.txt
 * Served at /robots.txt (see .htaccess) so the sitemap line always points at
 * the host the site is actually running on.
 */
require_once __DIR__ . '/includes/data.php';

header('Content-Type: text/plain; charset=UTF-8');
$origin = site_origin() . BASE_PATH;
?>
User-agent: *
Allow: /

Disallow: /admin/
Disallow: /includes/
Disallow: /uploads/temp/
Disallow: /form-handler.php
Disallow: /mail-enquiry.php
Disallow: /*?slug=

Sitemap: <?php echo $origin; ?>/sitemap.xml
