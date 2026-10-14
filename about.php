<?php
/**
 * OFSYS Education - About Us
 * Content sourced from the provided About Us document.
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'About Us';
$pageDesc = 'OFSYS Education is a career-focused training institute helping learners build practical skills and prepare for professional opportunities.';
$pageStyles = ['about.css'];
$hideFooterCta = true;

include __DIR__ . '/includes/header.php';
?>

<?php
$banner = [
  'title' => 'About<span class="pbx-ind"><em>OFSYS</em> Education</span>',
  'image' => $page_banners['about'],
  'crumb' => 'About Us',
  'giant' => 'ABOUT',
  'meta'  => false,
  'class' => 'pbx-center-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<section class="about-story">
  <div class="about-doodle about-doodle-one" aria-hidden="true">
    <i class="fa-solid fa-paper-plane"></i>
  </div>
  <div class="about-doodle about-doodle-two" aria-hidden="true">
    <i class="fa-solid fa-lightbulb"></i>
  </div>

  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-aos="fade-right">
        <div class="about-visual">
          <div class="about-main-img">
            <img src="assets/images/about us.webp" alt="Students learning in a professional classroom" loading="lazy" decoding="async">
          </div>
          <div class="about-note">
            <span><i class="fa-solid fa-user-graduate"></i></span>
         
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left">
        <span class="section-kicker">About Us</span>
        <h2 class="about-title">Building Skills for a <em>Successful Career</em></h2>
        <div class="about-copy">
          <p class="about-copy-primary"><strong>OFSYS Education</strong> is a career-focused training institute committed to helping learners build practical skills and prepare for professional opportunities. We provide job-oriented professional courses designed to support students, graduates, job seekers, and working professionals in developing the knowledge and skills required for today's workplace.</p>
          <div class="about-copy-more">
            <p>Our approach combines practical learning with experienced trainers, industry-relevant training, hands-on practice, and real-world projects. We focus on creating a learning environment where students can develop technical knowledge, improve professional skills, build confidence, and take meaningful steps towards their career goals.</p>
            <p>At OFSYS Education, we believe that quality education should go beyond completing a syllabus. Learning should help individuals understand their field, apply their knowledge in practical situations, and become better prepared for the professional environment.</p>
          </div>
          <button class="about-read-toggle" type="button" aria-expanded="false">Read more</button>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="about-belief">
  <div class="container">
    <div class="belief-panel" data-aos="fade-up">
      <div>
        <span class="section-kicker light">At OFSYS Education, we believe</span>
        <h2>Quality education should go beyond completing a syllabus.</h2>
      </div>
      <div class="belief-copy">
        <p class="belief-lead">Learning should help individuals:</p>
        <ol class="belief-steps">
          <li><span>01</span> Understand their field</li>
          <li><span>02</span> Apply their knowledge in practical situations</li>
          <li><span>03</span> Become better prepared for the professional environment</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="about-vision-mission">
  <div class="container">
    <div class="vm-grid">
      <div class="vm-frame" data-aos="fade-up">
        <article class="vm-board vm-panel mission-panel">
          <div class="vm-head">
            <span class="vm-icon"><i class="fa-solid fa-bullseye"></i></span>
            <h3>Mission</h3>
          </div>
          <div class="vm-body">
            <p>At OFSYS Education, our mission is to provide quality, industry-relevant education through experienced faculty and practical learning. We aim to help learners build the skills, confidence, and adaptability needed to grow professionally and pursue meaningful career opportunities.</p>
          </div>
          <div class="vm-rule" aria-hidden="true"></div>
        </article>
      </div>

      <div class="vm-frame" data-aos="fade-up" data-aos-delay="150">
        <article class="vm-board vm-panel vision-panel">
          <div class="vm-head">
            <span class="vm-icon"><i class="fa-solid fa-binoculars"></i></span>
            <h3>Vision</h3>
          </div>
          <div class="vm-body">
            <p>To be a globally respected education leader, preparing skilled and career-ready professionals who create meaningful value for organizations and communities worldwide.</p>
          </div>
          <div class="vm-rule" aria-hidden="true"></div>
        </article>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const aboutCopy = document.querySelector('.about-copy');
    const toggle = document.querySelector('.about-read-toggle');

    if (!aboutCopy || !toggle) return;

    const mediaQuery = window.matchMedia('(max-width: 767px)');

    function syncToggle() {
      if (!mediaQuery.matches) {
        aboutCopy.classList.remove('is-expanded');
        toggle.textContent = 'Read more';
        toggle.setAttribute('aria-expanded', 'false');
        toggle.style.display = 'none';
        return;
      }

      toggle.style.display = '';
    }

    toggle.addEventListener('click', function () {
      if (!mediaQuery.matches) return;

      const expanded = aboutCopy.classList.toggle('is-expanded');
      toggle.textContent = expanded ? 'Read less' : 'Read more';
      toggle.setAttribute('aria-expanded', String(expanded));
    });

    syncToggle();
    window.addEventListener('resize', syncToggle);
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
