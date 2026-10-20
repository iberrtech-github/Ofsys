<?php
/**
 * OFSYS Education - Courses Landing Page (Ultra-Creative Brand Theme)
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Career Programs & Certification Courses';
$pageDesc = 'Explore 8 job-oriented certification courses in SAP S/4HANA, Gen AI, Python Full Stack, Data Science, and Office Administration at OFSYS Education.';
$pageStyles = ['home.css', 'courses.css']; // featured course slider + courses page styles
$hideFooterCta = true; // page ends with its own CTA

include __DIR__ . '/includes/header.php';
?>

<!-- Page Banner -->
<?php
$banner = [
  'title' => 'Our<span class="pbx-ind"><em>Courses</em></span>',
  'image' => $page_banners['courses'],
  'crumb' => 'Courses',
  'giant' => 'COURSES',
  'meta'  => false,
  'class' => 'pbx-center-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<!-- ==========================================================================
     Our Courses intro (from the Our Courses document)
     ========================================================================== -->
<section class="oc-intro">
  <span class="oc-orb oc-orb-1" aria-hidden="true"></span>
  <span class="oc-orb oc-orb-2" aria-hidden="true"></span>

  <div class="container">
    <div class="oc-hero" data-aos="fade-up">
      <span class="section-kicker">Our Courses</span>
      <h2 class="oc-title">Choose a program that meets your <em>career goals</em></h2>
      <p class="oc-lead">Discover career focused professional courses that will equip you with practical knowledge, relevant skills and the confidence required in today’s work environment.</p>

      <ul class="oc-stats" aria-label="Programme highlights">
        <li><strong><?php echo count($courses); ?></strong><span>Career Programs</span></li>
        <li><strong>100%</strong><span>Practical Training</span></li>
        <li><strong>1:1</strong><span>Placement Support</span></li>
      </ul>

      <div class="oc-hero-actions">
        <a href="#courses-section" class="theme-btn">Explore Courses <i class="fa-solid fa-arrow-right"></i></a>
        <a href="<?php echo BASE_PATH; ?>/contact" class="oc-ghost-link">
          <i class="fa-solid fa-headset"></i> Talk to a counsellor
        </a>
      </div>
    </div>
  </div>

  <!-- Course name ticker -->
  <div class="oc-ticker" aria-hidden="true">
    <div class="oc-ticker-track">
      <?php for ($pass = 0; $pass < 2; $pass++): ?>
        <?php foreach ($courses as $c): ?>
          <span class="oc-ticker-item">
            <i class="<?php echo $c['icon']; ?>"></i>
            <?php echo htmlspecialchars($c['short_title']); ?>
          </span>
          <span class="oc-ticker-dot"></span>
        <?php endforeach; ?>
      <?php endfor; ?>
    </div>
  </div>
</section>

<!-- ==========================================================================
     The 8 courses as layered cards
     ========================================================================== -->
<section class="cg-section" id="courses-section">
  <div class="container">

    <div class="cg-header" data-aos="fade-up">
      <span class="section-kicker">Our Programs</span>
      <h2 class="cg-title">Eight Career - Building <em>Courses</em></h2>
      <div class="cg-divider"></div>
    </div>

    <div class="cg-grid">
      <?php $cgNo = 0; foreach ($courses as $cSlug => $c): $cgNo++; ?>
        <article class="cg-item cg-tone-<?php echo (($cgNo - 1) % 4) + 1; ?>"
                 data-aos="fade-up" data-aos-delay="<?php echo (($cgNo - 1) % 4) * 100; ?>">
          <a class="cg-card" href="<?php echo BASE_PATH; ?>/courses/<?php echo $cSlug; ?>">
            <span class="cg-icon"><i class="<?php echo $c['icon']; ?>"></i></span>
            <h3 class="cg-name"><?php echo htmlspecialchars($c['short_title']); ?></h3>
            <p class="cg-desc"><?php echo htmlspecialchars($c['description']); ?></p>
            <span class="cg-cta">
              <span class="cg-cta-label">Explore Course</span>
              <i class="fa-solid fa-arrow-right"></i>
            </span>
            <span class="cg-sr">Explore <?php echo htmlspecialchars($c['title']); ?></span>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

  </div>
</section>



<!-- ==========================================================================
     Find the Right Course CTA
     ========================================================================== -->
<section class="oc-cta bg-theme-blush">
  <div class="container">
    <div class="oc-cta-panel" data-aos="fade-up">
      <div class="oc-cta-icon" aria-hidden="true"><i class="fa-solid fa-compass"></i></div>
      <div class="oc-cta-copy">
        <h2>Find the Right Course for <em>Your Career</em></h2>
        <p>Not sure which course is right for you? Explore the course details and choose a program that matches your interests, existing skills, and career goals.</p>
      </div>
      <div class="oc-cta-actions">
        <a href="#coursesGrid" class="oc-btn oc-btn-gold">Explore Courses <i class="fa-solid fa-arrow-right"></i></a>
        <a href="<?php echo BASE_PATH; ?>/contact" class="oc-btn oc-btn-line">Contact Us <i class="fa-solid fa-phone"></i></a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
