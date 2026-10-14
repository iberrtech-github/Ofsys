<?php
/**
 * OFSYS Education - Placements.
 * Every student shown here is managed from the admin panel (admin/placements.php).
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Placements';
$pageDesc  = 'Students of OFSYS Education who completed their training and moved into professional roles.';
$pageStyles = ['placements.css'];
$hideFooterCta = true;

$placements = ofsy_get_placements();

include __DIR__ . '/includes/header.php';
?>

<?php
$banner = [
  'title' => 'Our<span class="pbx-ind"><em>Placements</em></span>',
  'image' => '',
  'crumb' => 'Placements',
  'giant' => 'CAREERS',
  'meta'  => false,
  'class' => 'pbx-center-sm pbx-title-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<section class="pl-section">
  <div class="container">

    <div class="pl-header" data-aos="fade-up">
      <span class="section-kicker">Student Success</span>
    </div>

    <?php if (!$placements): ?>

      <div class="pl-empty" data-aos="fade-up">
        <span class="pl-empty-icon"><i class="fa-solid fa-user-graduate"></i></span>
        <h3>Placement updates coming soon</h3>
        <p>Our latest placement records are being updated. Talk to our admissions team to know more about career support at OFSYS Education.</p>
        <a href="<?php echo BASE_PATH; ?>/contact" class="theme-btn">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
      </div>

    <?php else: ?>

      <div class="pl-grid">
        <?php $plDelay = 0; foreach ($placements as $st): ?>
          <?php $imageOnly = empty($st['name']) && empty($st['role']) && empty($st['company'])
              && empty($st['course']) && empty($st['package']) && empty($st['quote']); ?>
          <article class="pl-card<?php echo $imageOnly ? ' pl-image-only' : ''; ?>"
                   data-aos="fade-up" data-aos-delay="<?php echo $plDelay; ?>">

            <div class="pl-card-top">
              <?php $img = ofsy_image_src($st['image']); ?>
              <?php if ($img): ?>
                <img class="pl-photo" src="<?php echo htmlspecialchars($img); ?>"
                     alt="<?php echo htmlspecialchars($st['name'] ?: 'Placement image'); ?>" loading="lazy">
              <?php else: ?>
                <span class="pl-photo pl-photo-empty"><i class="fa-solid fa-image"></i></span>
              <?php endif; ?>

              <?php if (!$imageOnly): ?>
                <div class="pl-identity">
                  <?php if (!empty($st['name'])): ?>
                    <h3><?php echo htmlspecialchars($st['name']); ?></h3>
                  <?php endif; ?>
                  <?php if (!empty($st['role'])): ?>
                    <p class="pl-role"><?php echo htmlspecialchars($st['role']); ?></p>
                  <?php endif; ?>
                  <?php if (!empty($st['company'])): ?>
                    <span class="pl-company"><i class="fa-solid fa-building"></i> <?php echo htmlspecialchars($st['company']); ?></span>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>

            <?php if (!$imageOnly && (!empty($st['course']) || !empty($st['package']))): ?>
              <div class="pl-meta">
                <?php if (!empty($st['course'])): ?>
                  <div class="pl-meta-row">
                    <span>Course</span>
                    <strong><?php echo htmlspecialchars($st['course']); ?></strong>
                  </div>
                <?php endif; ?>
                <?php if (!empty($st['package'])): ?>
                  <div class="pl-meta-row">
                    <span>Package</span>
                    <strong class="pl-package"><?php echo htmlspecialchars($st['package']); ?></strong>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>

            <?php if (!empty($st['quote'])): ?>
              <blockquote class="pl-quote"><?php echo htmlspecialchars($st['quote']); ?></blockquote>
            <?php endif; ?>

          </article>
        <?php $plDelay = ($plDelay + 100) % 300; endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
