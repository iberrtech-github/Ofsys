<?php
/**
 * OFSYS Education - Single Blog Article Page
 */
require_once __DIR__ . '/includes/data.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : 'why-ai-is-transforming-business-and-education-2026';

// Find matching blog
$currentBlog = null;
foreach ($blogs as $b) {
    if ($b['slug'] === $slug) {
        $currentBlog = $b;
        break;
    }
}

if (!$currentBlog) {
    $currentBlog = $blogs[0];
}

$pageTitle = $currentBlog['title'];
$pageDesc = $currentBlog['excerpt'];
$hideFooterCta = true;

include __DIR__ . '/includes/header.php';
?>

<?php
// ---- Poster banner (shared component) ------------------------------------
require_once __DIR__ . '/includes/page-banner-helpers.php';

$banner = [
    // Plain headline (no stepped stagger), no photo and no diagonal slashes
    'title'  => htmlspecialchars($currentBlog['title']),
    'image'  => '',
    'crumb'  => $currentBlog['category'],
    'crumbs' => [['label' => 'Blog', 'url' => BASE_PATH . '/blog']],
    'giant'  => 'ARTICLE',
    'meta'   => false,
    'class'  => 'pbx-plain'
];
include __DIR__ . '/includes/page-banner.php';
?>

<!-- Article Content -->
<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8" data-aos="fade-up">
        <div class="rounded-4 overflow-hidden shadow-sm mb-5" style="max-height: 440px;">
          <img src="<?php echo $currentBlog['image']; ?>" alt="<?php echo htmlspecialchars($currentBlog['title']); ?>" class="w-100 h-100 object-fit-cover" loading="lazy">
        </div>

        <div class="article-body fs-5 text-secondary lh-lg mb-5">
          <?php echo $currentBlog['content']; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
