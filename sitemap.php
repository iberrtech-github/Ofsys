<?php
/**
 * OFSYS Education - XML sitemap
 * Served at /sitemap.xml (see .htaccess). URLs are built from site_origin(),
 * so filling in SITE_URL in includes/data.php is all that is needed at launch.
 */
require_once __DIR__ . '/includes/data.php';

$origin = site_origin() . BASE_PATH;

/** @var array<int, array{loc:string, priority:string, changefreq:string}> $urls */
$urls = [];
$add = static function (string $path, string $priority, string $changefreq) use (&$urls, $origin): void {
    $urls[] = [
        'loc'        => $origin . $path,
        'priority'   => $priority,
        'changefreq' => $changefreq,
    ];
};

$add('/', '1.0', 'weekly');
$add('/about', '0.8', 'monthly');
$add('/courses', '0.9', 'weekly');
$add('/placements', '0.8', 'weekly');
$add('/gallery', '0.6', 'monthly');
$add('/blog', '0.7', 'weekly');
$add('/contact', '0.8', 'monthly');

foreach ($courses as $course) {
    if (!empty($course['slug'])) {
        $add('/courses/' . $course['slug'], '0.9', 'monthly');
    }
}

foreach ($blogs as $post) {
    if (!empty($post['slug'])) {
        $add('/blog/' . $post['slug'], '0.6', 'monthly');
    }
}

// One timestamp for the whole file: the newest page source in the project.
$lastmod = date('Y-m-d');
$newest  = 0;
foreach (array_merge(glob(__DIR__ . '/*.php') ?: [], glob(__DIR__ . '/course-pages/*.php') ?: []) as $file) {
    $newest = max($newest, (int) filemtime($file));
}
if ($newest > 0) {
    $lastmod = date('Y-m-d', $newest);
}

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?php echo htmlspecialchars($url['loc'], ENT_XML1); ?></loc>
    <lastmod><?php echo $lastmod; ?></lastmod>
    <changefreq><?php echo $url['changefreq']; ?></changefreq>
    <priority><?php echo $url['priority']; ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
