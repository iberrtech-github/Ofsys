<?php
/**
 * OFSYS Education - Career Insights & AI Trends Blog
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Career Insights & Tech Blog';
$pageDesc = 'Stay updated with career guides, Generative AI breakthroughs, SAP ERP hiring trends, and software engineering career advice from OFSYS mentors.';
$hideFooterCta = true;

include __DIR__ . '/includes/header.php';
?>

<!-- Page Banner -->
<?php
$banner = [
  'title' => 'Our<span class="pbx-ind"><em>Blog</em></span>',
  'image' => '',
  'crumb' => 'Blog',
  'giant' => 'BLOG',
  'meta'  => false,
  'class' => 'pbx-center-sm pbx-title-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<!-- Blog Cards Grid -->
<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="row g-4">
      <?php foreach ($blogs as $bIdx => $b): ?>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $bIdx * 100; ?>">
          <div class="card border rounded-4 overflow-hidden shadow-sm h-100 transition-all hover-translate-y">
            <div class="position-relative" style="height: 220px;">
              <img src="<?php echo $b['image']; ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($b['title']); ?>" loading="lazy" decoding="async">
              <span class="badge bg-danger text-white position-absolute top-0 end-0 m-3 px-3 py-2 rounded-pill">
                <?php echo htmlspecialchars($b['category']); ?>
              </span>
            </div>
            <div class="card-body p-4 d-flex flex-column">
              <div class="d-flex align-items-center gap-3 text-muted small mb-2">
                <span><i class="fa-regular fa-calendar me-1"></i><?php echo $b['date']; ?></span>
                <span>&bull;</span>
                <span><i class="fa-regular fa-clock me-1"></i><?php echo $b['read_time']; ?></span>
              </div>

              <h4 class="card-title fs-5 fw-bold mb-3">
                <a href="<?php echo BASE_PATH; ?>/blog/<?php echo $b['slug']; ?>" class="text-dark text-decoration-none">
                  <?php echo htmlspecialchars($b['title']); ?>
                </a>
              </h4>

              <p class="card-text text-secondary small mb-4 flex-grow-1">
                <?php echo htmlspecialchars($b['excerpt']); ?>
              </p>

              <div class="mt-auto pt-3 border-top">
                <a href="<?php echo BASE_PATH; ?>/blog/<?php echo $b['slug']; ?>" class="fw-bold text-danger text-decoration-none d-inline-flex align-items-center gap-1">
                  Read Article <i class="fa-solid fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
