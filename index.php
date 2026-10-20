<?php
/**
 * OFSYS Education - Official Homepage
 * Content: "OFSYS Education – Home Page Content" (client PDF)
 * Editorial layout with doodle hero, curved triptych, journey-path counters,
 * course slider and GSAP scroll animations.
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Ready to Transform Your Career';
$pageDesc = 'Receive practical, job-oriented training tailored to today’s career opportunities. Join OFSYS Education to get trained by experienced trainers, work on modern tools, develop real time skills and get prepared for the work place.';
$pageStyles = ['home.css'];
$hideFooterCta = true; // the homepage ends with its own final CTA
$autoEnquiryPopup = true; // enquiry popup opens on every homepage visit/refresh
$preloadImage = "assets/images/hero-img-cutout.webp";

include __DIR__ . '/includes/header.php';

// Counter positions on the journey path (x in %, y in px on a 200px tall path)
$counterPoints = [
    ['x' => 16.667, 'y' => 150, 'icon' => 'fa-solid fa-calendar-check', 'gold' => false],
    ['x' => 50, 'y' => 60, 'icon' => 'fa-solid fa-user-graduate', 'gold' => true],
    ['x' => 83.333, 'y' => 150, 'icon' => 'fa-solid fa-chalkboard-user', 'gold' => false]
];
$journeyPath = 'M0,100 C80,108 120,157 240,150 C360,143 560,60 720,60 C880,60 1080,143 1200,150 C1320,157 1360,108 1440,100';
?>

<!-- ==========================================================================
     1. HERO
     ========================================================================== -->
<section class="ins-hero mouse-parallax">
  <div class="hero-video" aria-hidden="true">
    <video class="hero-video-el" data-src="<?php echo BASE_PATH; ?>/assets/video/hero%20bg%20video.mp4"
           muted loop playsinline preload="none" tabindex="-1"></video>
    <span class="hero-video-wash"></span>
  </div>

  <div class="hero-sketch sketch-dash">
    <svg viewBox="0 0 300 150" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
      <path d="M2 4 C40 70 120 30 130 80 C138 120 90 130 100 100 C112 70 180 120 220 110 C260 100 280 130 298 146" stroke-dasharray="5 6"/>
    </svg>
  </div>

  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 order-2 order-lg-1">
        <h1 class="ins-hero-title hero-title-split">
          Ready to
          <em class="no-split">Transform<svg class="title-underline" viewBox="0 0 300 20" preserveAspectRatio="none" fill="none"><path d="M3 14 C70 4 160 4 297 12" stroke="currentColor" stroke-width="5" stroke-linecap="round"/></svg></em>
          Your Career
        </h1>
        <p class="hero-tagline hero-intro">Build Skills. Shape Your Future.</p>
        <div class="d-flex flex-wrap align-items-center gap-4 hero-intro">
          <a href="<?php echo BASE_PATH; ?>/courses" class="theme-btn">Explore Our Courses <i class="fa-solid fa-arrow-right"></i></a>
          <button type="button" class="hero-call trigger-discount-modal" data-course="">
            <span class="ring"><i class="fa-solid fa-headset"></i></span>
            <span class="text-start"><small><?php echo PHONE_DISPLAY; ?></small><strong>Talk to an Expert</strong></span>
          </button>
        </div>

        <div class="hero-m-stats">
          <div class="hms-card">
            <span class="hms-ico gold"><i class="fa-solid fa-award"></i></span>
            <span class="hms-copy"><strong><?php echo htmlspecialchars($metrics[0]['number']); ?> Years</strong><small>Active Training</small></span>
          </div>
          <div class="hms-card">
            <span class="hms-ico"><i class="fa-solid fa-chalkboard-user"></i></span>
            <span class="hms-copy"><strong><?php echo htmlspecialchars($metrics[2]['number']); ?> Trainers</strong><small>Industry Experts</small></span>
          </div>
          <div class="hms-card">
            <span class="hms-ico gold"><i class="fa-solid fa-user-graduate"></i></span>
            <span class="hms-copy"><strong><?php echo htmlspecialchars($metrics[1]['number']); ?> Graduates</strong><small>Trained &amp; Supported</small></span>
          </div>
          <a class="hms-card" href="<?php echo htmlspecialchars($branches['manjeri']['map_url']); ?>" target="_blank" rel="noopener" aria-label="OFSYS Education is rated 4.9 out of 5 on Google reviews">
            <span class="hms-ico hms-ico-google" aria-hidden="true">
              <svg viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
              </svg>
            </span>
            <span class="hms-copy"><strong>4.9 <i class="fa-solid fa-star hms-star" aria-hidden="true"></i></strong><small>Google Reviews</small></span>
          </a>
        </div>
      </div>

      <div class="col-lg-6 order-1 order-lg-2">
        <div class="hero-ai">
          <span class="hai-orb" aria-hidden="true"></span>
          <span class="hai-orb-ring" aria-hidden="true"></span>
          <span class="hai-braces" aria-hidden="true">{ }</span>
          <svg class="hai-mesh" viewBox="0 0 520 520" fill="none" aria-hidden="true">
            <g class="hai-links" stroke="currentColor" stroke-width="1">
              <path d="M70 150 L200 90 L380 130 L450 250 L360 410 L170 420 L60 300 Z"/>
              <path d="M200 90 L260 260 L360 410"/>
              <path d="M70 150 L260 260 L450 250"/>
              <path d="M170 420 L260 260 L380 130"/>
            </g>
            <g class="hai-nodes" fill="currentColor">
              <circle cx="70" cy="150" r="5"/><circle cx="200" cy="90" r="4"/>
              <circle cx="380" cy="130" r="5"/><circle cx="450" cy="250" r="4"/>
              <circle cx="360" cy="410" r="5"/><circle cx="170" cy="420" r="4"/>
              <circle cx="60" cy="300" r="5"/><circle cx="260" cy="260" r="7"/>
            </g>
          </svg>
          <div class="hai-photo hai-photo-cut">
            <div class="hai-photo-inner">
              <span class="hai-stage" aria-hidden="true">
                <span class="hai-stage-grid"></span>
                <span class="hai-scan"></span>
              </span>
              <svg class="hai-circuit" viewBox="0 0 300 420" fill="none" aria-hidden="true">
                <g class="hai-trace" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M296 70 H236 L212 94 V150 H176"/>
                  <path d="M296 128 H262 L240 150 V214"/>
                  <path d="M4 112 H52 L72 132 V188 H104"/>
                  <path d="M4 196 H40 L62 218 V268"/>
                  <path d="M296 248 H250 L228 270 V322 H196"/>
                  <path d="M4 300 H46 L68 322 V360"/>
                </g>
                <g class="hai-dot" fill="currentColor">
                  <circle cx="212" cy="94" r="3.4"/><circle cx="176" cy="150" r="3"/>
                  <circle cx="240" cy="214" r="3.4"/><circle cx="72" cy="132" r="3"/>
                  <circle cx="104" cy="188" r="3.4"/><circle cx="62" cy="268" r="3"/>
                  <circle cx="228" cy="270" r="3.4"/><circle cx="68" cy="360" r="3"/>
                </g>
              </svg>
              <img src="assets/images/hero-img-cutout.webp" alt="OFSYS graduate in cap and gown" width="487" height="699" fetchpriority="high" decoding="async">
              <span class="hai-pulse" aria-hidden="true"></span>
            </div>
          </div>
          <span class="hai-tile hai-tile-1"><i class="fa-solid fa-chart-column"></i></span>
          <span class="hai-tile hai-tile-2 gold"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
          <span class="hai-tile hai-tile-3"><i class="fa-solid fa-code"></i></span>
          <span class="hai-tile hai-tile-4"><i class="fa-solid fa-comments"></i></span>
          <div class="hai-card hai-card-years">
            <span class="hai-card-ico"><i class="fa-solid fa-award"></i></span>
            <strong><span class="counter-val" data-target="<?php echo intval($metrics[0]['number']); ?>" data-suffix="+"><?php echo htmlspecialchars($metrics[0]['number']); ?></span></strong>
            <small><?php echo htmlspecialchars($metrics[0]['short']); ?></small>
          </div>
          <div class="hai-card hai-card-trainers">
            <span class="hai-card-ico gold"><i class="fa-solid fa-chalkboard-user"></i></span>
            <strong><span class="counter-val" data-target="<?php echo intval($metrics[2]['number']); ?>" data-suffix="+"><?php echo htmlspecialchars($metrics[2]['number']); ?></span></strong>
            <small><?php echo htmlspecialchars($metrics[2]['short']); ?></small>
          </div>
          <a class="hai-review" href="<?php echo htmlspecialchars($branches['manjeri']['map_url']); ?>" target="_blank" rel="noopener" aria-label="OFSYS Education is rated 4.9 out of 5 on Google reviews">
            <span class="hai-review-logo" aria-hidden="true">
              <svg viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
              </svg>
            </span>
            <span class="hai-review-text"><strong>Google Reviews</strong><span>4.9 <i class="fa-solid fa-star hai-star" aria-hidden="true"></i></span></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     3. ABOUT OFSYS
     ========================================================================== -->
<section class="ins-about" id="home-about">
  <div class="container">
    <div class="home-about-layout">
      <div class="home-about-visual" aria-label="OFSYS students learning and graduates">
        <span class="home-about-frame home-about-frame-one"></span>
        <span class="home-about-frame home-about-frame-two"></span>
        <div class="home-about-photo home-about-photo-one img-reveal" data-reveal="left">
          <img src="<?php echo BASE_PATH; ?>/assets/images/about%20us.webp" alt="OFSYS Education office" loading="lazy" decoding="async">
        </div>
        <div class="home-about-photo home-about-photo-two img-reveal" data-reveal="right">
          <img src="<?php echo BASE_PATH; ?>/assets/images/img1.jpg" alt="OFSYS students developing practical computer skills" loading="lazy" decoding="async">
        </div>
      </div>
      <div class="home-about-copy">
        <h2 class="ins-title split-anim">Build the Skills That Move Your <em>Career Forward</em></h2>
        <div class="about-copy">
          <p class="fade-anim" data-delay=".2"><strong>OFSYS Education</strong> is a career oriented training institute that provides career oriented professional courses for students, graduates &amp; job aspirants and professionals.</p>
          <p class="fade-anim" data-delay=".3">The training programs are designed to help learners build the technical knowledge, practical skills, communication abilities, and professional confidence they need to move toward their career goals.</p>
        </div>
        <div class="fade-anim" data-delay=".6">
          <a href="<?php echo BASE_PATH; ?>/about" class="theme-btn">Know More About OFSYS <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     3. TRUST / ACHIEVEMENTS (counters on a journey path)
     ========================================================================== -->
<section class="ins-counters">
  <div class="container text-center">
    <h2 class="ins-title split-anim">Learn. Practice. <em>Grow.</em></h2>
  </div>
  <div class="journey-path">
    <svg class="path-svg draw-on-scroll" data-scrub viewBox="0 0 1440 200" preserveAspectRatio="none" aria-hidden="true">
      <defs>
        <mask id="journeyMask" maskUnits="userSpaceOnUse" x="-20" y="-20" width="1480" height="240">
          <path class="mask-stroke draw" d="<?php echo $journeyPath; ?>"/>
        </mask>
      </defs>
      <path class="dash" mask="url(#journeyMask)" d="<?php echo $journeyPath; ?>"/>
    </svg>
    <div class="jp-grid">
      <?php foreach ($metrics as $mIdx => $m): $pt = $counterPoints[$mIdx]; ?>
        <div class="jp-point<?php echo $pt['gold'] ? ' gold' : ''; ?>" style="left: <?php echo $pt['x']; ?>%; top: <?php echo $pt['y']; ?>px;">
          <div class="jp-label fade-anim" data-delay="<?php echo 0.2 + $mIdx * 0.15; ?>">
            <div class="jp-num"><span class="counter-val" data-target="<?php echo intval($m['number']); ?>" data-suffix="<?php echo str_replace(intval($m['number']), '', $m['number']); ?>"><?php echo $m['number']; ?></span></div>
            <div class="jp-cap">
              <span class="jp-cap-full"><?php echo htmlspecialchars($m['label']); ?></span>
              <span class="jp-cap-short"><?php echo htmlspecialchars($m['short']); ?></span>
            </div>
          </div>
          <div class="jp-icon"><i class="<?php echo $pt['icon']; ?>"></i></div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="jp-plane"><i class="fa-solid fa-paper-plane"></i></div>
  </div>
</section>

<!-- ==========================================================================
     5. WHY CHOOSE OFSYS
     ========================================================================== -->
<section class="ins-offer">
  <svg class="offer-shape" viewBox="0 0 280 270" fill="none" stroke="currentColor" stroke-width="2"><path d="M218 1 L1 59 L56 265 L273 206 Z"/><path d="M188 30 L30 72 L72 230 L232 188 Z"/></svg>
  <svg class="offer-arrow draw-on-scroll" viewBox="0 0 170 110" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
    <path class="draw" d="M160 100 C150 40 110 10 80 40 C60 60 90 80 100 56 C112 26 60 4 12 22"/>
    <path class="draw" d="M24 10 L10 22 L26 32"/>
  </svg>
  <div class="flying-plane" aria-hidden="true"><i class="fa-solid fa-paper-plane plane"></i></div>

  <div class="container">
    <div class="why-head">
      <h2 class="ins-title split-anim">Why Choose <em>OFSYS Education?</em></h2>
      <p class="ins-text fade-anim" data-delay=".25">Selecting the right training institute is the first step toward building your career. OFSYS provides practical training, experienced trainers, modern equipment and career oriented help to prepare learners for job openings.</p>
    </div>

    <div class="why-hang">
      <svg class="why-wire draw-on-scroll" viewBox="0 0 1200 90" preserveAspectRatio="none" aria-hidden="true">
        <path class="draw" d="M0 10 C 240 86, 960 86, 1200 10" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
      <div class="why-row">
        <?php foreach ($why_choose as $wIdx => $why): ?>
          <div class="hang-slot fade-anim" data-delay="<?php echo 0.1 + $wIdx * 0.08; ?>">
            <article class="hang-card">
              <span class="hang-pin" aria-hidden="true"></span>
              <div class="hang-art">
                <img src="<?php echo $why['img']; ?>" alt="<?php echo htmlspecialchars($why['title']); ?>" loading="lazy">
                <span class="hang-num"><?php echo str_pad($wIdx + 1, 2, '0', STR_PAD_LEFT); ?></span>
              </div>
              <div class="hang-body">
                <h3><?php echo htmlspecialchars($why['title']); ?></h3>
                <p><?php echo htmlspecialchars($why['desc']); ?></p>
              </div>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     6. WHAT YOU GET
     ========================================================================== -->
<section class="ins-value">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5">
        <div class="value-collage">
          <div class="vc-main img-reveal" data-reveal="bottom">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=900&auto=format&fit=crop&q=72&fm=webp" alt="Learners on a practical project" loading="lazy">
          </div>
          <div class="vc-small img-reveal" data-reveal="left">
            <img src="assets/images/mtc.jpg" alt="Hands-on tool practice" loading="lazy">
          </div>
          <div class="vc-badge float-bob-y">
            <strong>6</strong>
            <span>Month Career-Focused Programs</span>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <h2 class="ins-title split-anim">More Than Just <em>a Course</em></h2>
        <p class="ins-text fade-anim" data-delay=".2">At OFSYS we do not aim to complete a syllabus in isolation. We provide programs that will give you hands-on knowledge and skills for the workplace.</p>
        <div class="row g-3 mt-2 stagger-anim">
          <?php foreach ($what_you_get as $gIdx => $item): ?>
            <div class="col-6">
              <div class="value-tile">
                <span class="vt-icon"><i class="<?php echo $item['icon']; ?>"></i></span>
                <div>
                  <h5><?php echo htmlspecialchars($item['title']); ?></h5>
                  <p><?php echo htmlspecialchars($item['desc']); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     7. CAREER SUPPORT
     ========================================================================== -->
<section class="ins-career">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5">
        <h2 class="ins-title split-anim">Prepare for Your <em>Next Career</em> Opportunity</h2>
        <p class="ins-text fade-anim" data-delay=".2">Career development is more than simply skills acquisition. The OFSYS supports learners with key career-readiness activities.</p>
        <div class="fade-anim" data-delay=".35">
          <a href="<?php echo BASE_PATH; ?>/placements" class="theme-btn">Explore Career Support <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="career-img img-reveal mt-5" data-reveal="left">
          <img src="assets/images/career img.jpg" alt="Getting ready for the workplace" loading="lazy">
        </div>
      </div>
      <div class="col-lg-7">
        <div class="career-steps">
          <svg class="career-line draw-on-scroll" data-scrub viewBox="0 0 4 600" preserveAspectRatio="none" aria-hidden="true">
            <path class="draw" d="M2 0 V600"/>
          </svg>
          <?php foreach ($career_support as $sIdx => $step): ?>
            <div class="career-step fade-anim" data-fade-from="right" data-delay="<?php echo 0.1 + $sIdx * 0.12; ?>">
              <span class="cs-num"><?php echo str_pad($sIdx + 1, 2, '0', STR_PAD_LEFT); ?></span>
              <div class="cs-card">
                <span class="cs-icon"><i class="<?php echo $step['icon']; ?>"></i></span>
                <div>
                  <h4><?php echo htmlspecialchars($step['title']); ?></h4>
                  <p><?php echo htmlspecialchars($step['desc']); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     AFFILIATIONS & CERTIFICATIONS
     ========================================================================== -->
<section class="section-pad-sm bg-white">
  <div class="container text-center mb-4">
    <h2 class="ins-title split-anim">Affiliations &amp; <em>Certifications</em></h2>
  </div>
  <div class="logo-marquee fade-anim">
    <div class="marquee-track">
      <?php $allLogos = array_merge($affiliations, $certifications); for ($r = 0; $r < 2; $r++): foreach ($allLogos as $logo): ?>
        <div class="logo-tile"><img src="<?php echo BASE_PATH . '/' . $logo['image']; ?>" alt="<?php echo htmlspecialchars($logo['name']); ?>" title="<?php echo htmlspecialchars($logo['name']); ?>" loading="lazy"></div>
      <?php endforeach; endfor; ?>
    </div>
  </div>
</section>

<!-- ==========================================================================
     CUSTOMER REVIEWS
     ========================================================================== -->
<section class="home-reviews-section" aria-labelledby="home-reviews-title">
  <div class="container">
    <div class="home-reviews-heading">
      <h2 class="home-reviews-hashtag" id="home-reviews-title"><span>Google</span> Reviews</h2>
    </div>

    <div class="home-reviews-stage">
      <div class="home-review-frame">
        <span class="home-review-google" aria-hidden="true">
          <svg viewBox="0 0 48 48" role="img" focusable="false">
            <path fill="#4285F4" d="M45.12 24.5c0-1.56-.14-3.06-.4-4.5H24v8.51h11.84c-.51 2.75-2.06 5.08-4.39 6.64v5.52h7.11c4.16-3.83 6.56-9.47 6.56-16.17z"/>
            <path fill="#34A853" d="M24 46c5.94 0 10.92-1.97 14.56-5.33l-7.11-5.52c-1.97 1.32-4.49 2.1-7.45 2.1-5.73 0-10.58-3.87-12.31-9.07H4.34v5.7C7.96 41.07 15.4 46 24 46z"/>
            <path fill="#FBBC05" d="M11.69 28.18A13.2 13.2 0 0 1 11 24c0-1.45.25-2.86.69-4.18v-5.7H4.34A21.99 21.99 0 0 0 2 24c0 3.55.85 6.91 2.34 9.88l7.35-5.7z"/>
            <path fill="#EA4335" d="M24 10.75c3.23 0 6.13 1.11 8.41 3.29l6.31-6.31C34.91 4.18 29.93 2 24 2 15.4 2 7.96 6.93 4.34 14.12l7.35 5.7C13.42 14.62 18.27 10.75 24 10.75z"/>
          </svg>
        </span>
        <div class="swiper home-reviews-swiper" aria-label="Student reviews">
          <div class="swiper-wrapper">
            <?php
            $homeReviews = [
                ['name' => 'Hasna Fahim', 'reviews' => '2 reviews', 'initial' => 'H', 'date' => 'a month ago', 'text' => 'I am currently doing data science course in OFSYS institute Perinthalmanna. Its a really good experience studying here. The faculties are really great here. They make us study very well.'],
                ['name' => 'Muhammed Sayeed', 'reviews' => '1 review', 'initial' => 'M', 'date' => 'a month ago', 'text' => 'I had a good learning experience at OFSYS Education.The Data Analytics training is practical and easy to understand.The mentors are supportive and explain concepts clearly.'],
                ['name' => 'Rinshana Rinu', 'reviews' => '1 review', 'initial' => 'R', 'date' => '2 months ago', 'text' => "I'm very happy to be a student at OFSYS Education. The teaching methods are easy to understand, and the trainers focus on practical learning. The institute offers good support throughout the course and motivates students to achieve their career goals. Highly recommended."],
                ['name' => 'Hiba A', 'reviews' => '1 review', 'initial' => 'H', 'date' => '4 months ago', 'text' => 'I am a student at OFSYS Education. The training is excellent, the faculty is supportive, and the learning environment is very good. The practical sessions and guidance have helped me improve my skills significantly. Highly recommended.'],
                ['name' => 'Saneesh Sanu', 'reviews' => '1 review', 'initial' => 'S', 'date' => '9 months ago', 'text' => 'I am a student of this institution and my experience has been very good. The teachers are supportive and explain concepts clearly. The learning environment is friendly and motivating. This institution helped me improve my knowledge and confidence.'],
                ['name' => 'Shuhaib vp', 'reviews' => '1 review', 'initial' => 'S', 'date' => '4 days ago', 'text' => 'My institution has provided me with valuable knowledge and practical skills in Data Analytics. The training helped me improve my analytical thinking, problem-solving abilities, and technical skills. I am grateful to my trainers for their guidance and support. Overall, it has been a great learning experience that has helped me build confidence and prepare for my future career as a Data Analyst.']
            ];
            foreach ($homeReviews as $reviewIndex => $review): ?>
              <article class="swiper-slide home-review-slide" aria-label="Review <?php echo $reviewIndex + 1; ?> of <?php echo count($homeReviews); ?>">
                <div class="home-review-profile">
                  <span class="home-review-avatar"><?php echo htmlspecialchars($review['initial']); ?></span>
                  <span class="home-review-author">
                    <strong><?php echo htmlspecialchars($review['name']); ?></strong>
                  </span>
                  <i class="fa-solid fa-ellipsis-vertical home-review-menu" aria-hidden="true"></i>
                </div>
                <div class="home-review-rating">
                  <span class="home-review-stars" aria-label="5 out of 5 stars">★★★★★</span>
                  <time><?php echo htmlspecialchars($review['date']); ?></time>
                </div>
                <p><?php echo htmlspecialchars($review['text']); ?></p>
                <div class="home-review-bottom-stars" aria-hidden="true">★★★★★</div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="home-review-controls" aria-label="Review slideshow controls">
          <button type="button" class="home-review-control home-review-prev" aria-label="Previous review">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
          </button>
          <div class="home-review-pagination" aria-label="Choose a review"></div>
          <button type="button" class="home-review-control home-review-next" aria-label="Next review">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
          </button>
          <span class="home-review-control-divider" aria-hidden="true"></span>
          <button type="button" class="home-review-control home-review-toggle" aria-label="Pause review slideshow" title="Pause slideshow">
            <i class="fa-solid fa-pause" aria-hidden="true"></i>
          </button>
        </div>
        <span class="home-review-quote" aria-hidden="true">“</span>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     8. FAQ
     ========================================================================== -->
<section class="faq-section section-pad">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <h2 class="ins-title split-anim">Frequently Asked <em>Questions</em></h2>
        <div id="homeFaq" class="mt-3 stagger-anim">
          <?php foreach ($faqs as $fIdx => $faq): ?>
            <div class="faq-item">
              <button class="faq-q collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $fIdx; ?>" aria-expanded="false" aria-controls="faq<?php echo $fIdx; ?>">
                <?php echo htmlspecialchars($faq['q']); ?>
                <span class="plus"><i class="fa-solid fa-plus"></i></span>
              </button>
              <div id="faq<?php echo $fIdx; ?>" class="collapse" data-bs-parent="#homeFaq">
                <div class="faq-a"><?php echo htmlspecialchars($faq['a']); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ==========================================================================
     9. FINAL CTA
     ========================================================================== -->
<section class="ins-final">
  <div class="container">
    <div class="final-box fade-anim">
      <svg class="final-doodle d1 float-bob-y" viewBox="0 0 100 80" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 30 L50 12 L94 30 L50 48 Z"/><path d="M24 38 V56 C24 66 76 66 76 56 V38"/><path d="M94 30 V52"/></svg>
      <svg class="final-doodle d2 float-bob-x" viewBox="0 0 60 80" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M30 14 C16 14 8 26 14 38 C17 44 21 46 21 54 H39 C39 46 43 44 46 38 C52 26 44 14 30 14 Z"/><path d="M23 62 H37 M25 69 H35"/></svg>
      <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
        <div class="col-lg-8">
          <h2 class="final-title split-anim">Ready to Build Skills for Your <em>Future?</em></h2>
          <p class="final-text">Choose your career path, learn practical skills, gain hands-on experience and prepare for the workplace with OFSYS Education.</p>
          <p class="final-script">Your Career Starts With the Right Skills.</p>
        </div>
        <div class="col-lg-4 d-flex flex-wrap gap-3 justify-content-lg-end">
          <a href="<?php echo BASE_PATH; ?>/courses" class="theme-btn white">Explore Courses <i class="fa-solid fa-arrow-right"></i></a>
          <a href="<?php echo BASE_PATH; ?>/contact" class="theme-btn dark">Contact OFSYS <i class="fa-solid fa-location-dot"></i></a>
        </div>
      </div>
      <div class="promo-clouds">
        <svg viewBox="0 0 1200 90" preserveAspectRatio="none"><path fill="rgba(255,255,255,0.35)" d="M0,90 L0,50 Q30,20 70,34 Q100,6 150,26 Q190,0 230,30 Q270,12 300,40 Q340,10 390,30 Q430,4 470,32 Q520,14 560,40 Q600,8 650,28 Q690,2 740,30 Q780,10 820,38 Q860,8 910,28 Q950,0 990,30 Q1040,12 1080,40 Q1120,10 1160,30 Q1185,20 1200,32 L1200,90 Z"/><path fill="#FFFDFB" d="M0,90 L0,62 Q8,17 42,21 Q76,17 85,63 Q93,25 124,29 Q155,25 163,64 Q173,16 213,20 Q253,16 263,64 Q270,8 300,12 Q330,8 337,55 Q350,16 401,20 Q452,16 465,59 Q476,23 518,27 Q560,23 570,58 Q582,20 628,24 Q674,20 685,63 Q697,17 746,21 Q796,17 808,62 Q818,20 856,24 Q894,20 903,68 Q911,30 942,34 Q974,30 982,65 Q990,17 1022,21 Q1053,17 1061,61 Q1073,37 1120,41 Q1166,37 1178,65 Q1186,40 1200,44 L1200,90 Z"/></svg>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
