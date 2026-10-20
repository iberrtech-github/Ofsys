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

// Everything else in the list, newest first, for the "keep reading" strip
$relatedBlogs = array_values(array_filter($blogs, static function ($b) use ($currentBlog) {
    return $b['slug'] !== $currentBlog['slug'];
}));
$relatedBlogs = array_slice($relatedBlogs, 0, 3);

$pageTitle  = $currentBlog['title'];
$pageDesc   = $currentBlog['excerpt'];
$pageStyles = ['blog-detail.css'];
$hideFooterCta = true;

// Absolute URL of this article, used by the share links
$articleUrl = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST']
    . BASE_PATH . '/blog/' . $currentBlog['slug'];

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
<section class="bd-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9 col-xl-8" data-aos="fade-up">

        <!-- Date, reading time, category, author -->
        <div class="bd-meta">
          <span class="bd-cat"><?php echo htmlspecialchars($currentBlog['category']); ?></span>
          <span><i class="fa-regular fa-calendar"></i><?php echo htmlspecialchars($currentBlog['date']); ?></span>
          <span><i class="fa-regular fa-clock"></i><?php echo htmlspecialchars($currentBlog['read_time']); ?></span>
          <span><i class="fa-regular fa-user"></i><?php echo htmlspecialchars($currentBlog['author'] ?? SITE_NAME . ' Training Team'); ?></span>
        </div>

        <?php if (!empty($currentBlog['image'])): ?>
          <div class="bd-hero">
            <img src="<?php echo $currentBlog['image']; ?>"
                 alt="<?php echo htmlspecialchars($currentBlog['title']); ?>"
                 loading="lazy" decoding="async">
          </div>
        <?php endif; ?>

        <?php if (!empty($currentBlog['excerpt'])): ?>
          <p class="bd-lead"><?php echo htmlspecialchars($currentBlog['excerpt']); ?></p>
        <?php endif; ?>

        <div class="bd-body">
          <?php echo $currentBlog['content']; ?>
        </div>

        <!-- Mid / end of article call to action -->
        <div class="bd-cta">
          <div>
            <h4>Want the current fees and batch dates?</h4>
            <p>Talk to our admissions team and claim a flat 20% early bird scholarship.</p>
          </div>
          <div class="bd-cta-actions">
            <a class="bd-cta-btn" href="tel:<?php echo HF_PHONE; ?>">
              <i class="fa-solid fa-phone"></i> <?php echo HF_PHONE_DISPLAY; ?>
            </a>
            <a class="bd-cta-btn is-ghost" href="<?php echo WHATSAPP_LINK; ?>" target="_blank" rel="noopener">
              <i class="fa-brands fa-whatsapp"></i> WhatsApp Us
            </a>
          </div>
        </div>

        <!-- Share -->
        <div class="bd-share">
          <span>Share</span>
          <a href="https://api.whatsapp.com/send?text=<?php echo rawurlencode($currentBlog['title'] . ' ' . $articleUrl); ?>"
             target="_blank" rel="noopener" aria-label="Share on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode($articleUrl); ?>"
             target="_blank" rel="noopener" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode($articleUrl); ?>"
             target="_blank" rel="noopener" aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
          <a href="https://twitter.com/intent/tweet?text=<?php echo rawurlencode($currentBlog['title']); ?>&url=<?php echo rawurlencode($articleUrl); ?>"
             target="_blank" rel="noopener" aria-label="Share on X"><i class="fa-brands fa-x-twitter"></i></a>
        </div>

        <?php if (!empty($currentBlog['faqs'])): ?>
          <!-- Frequently asked questions, only rendered when the article supplies them -->
          <div class="bd-faq">
            <h2 class="bd-faq-title">Frequently Asked Questions</h2>
            <?php foreach ($currentBlog['faqs'] as $faq): ?>
              <div class="bd-faq-item">
                <h4><?php echo htmlspecialchars($faq['q']); ?></h4>
                <p><?php echo htmlspecialchars($faq['a']); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($relatedBlogs)): ?>
          <div class="bd-related">
            <h2 class="bd-related-title">Keep Reading</h2>
            <div class="bd-related-grid">
              <?php foreach ($relatedBlogs as $rb): ?>
                <a class="bd-related-card" href="<?php echo BASE_PATH; ?>/blog/<?php echo $rb['slug']; ?>">
                  <img src="<?php echo $rb['image']; ?>" alt="<?php echo htmlspecialchars($rb['title']); ?>" loading="lazy" decoding="async">
                  <div class="bd-rc-body">
                    <span class="bd-rc-cat"><?php echo htmlspecialchars($rb['category']); ?></span>
                    <h4><?php echo htmlspecialchars($rb['title']); ?></h4>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
