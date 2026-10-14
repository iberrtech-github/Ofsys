<?php
/**
 * OFSYS Education - Shared Course Page Layout
 *
 * Each file in /courses/<slug>.php sets:
 *   $slug        - key into $courses (includes/data.php)
 *   $coursePage  - page specific content, all keys optional:
 *       'hero_minimal' bool     banner shows only the title; tagline + intro
 *                               render at the top of the body instead
 *       'tagline'      string   gold line under the H1
 *       'intro'        string   hero paragraph
 *       'about'        string   "About the Course" paragraph
 *       'learn_groups' array    [['title','icon','items'=>[]], ...]
 *       'curriculum'   array    plain curriculum items for the themed list
 *       'careers'      array    role names (defaults to $course['careers'])
 *       'audience'     array    [['label','icon'], ...]
 *       'closing'      array    ['title','text']
 *
 * Then: include __DIR__ . '/../includes/course-layout.php';
 */

require_once __DIR__ . '/data.php';

if (!isset($slug) || !isset($courses[$slug])) {
    header('Location: ' . BASE_PATH . '/courses');
    exit;
}

$course     = $courses[$slug];
$coursePage = $coursePage ?? [];

$pageTitle   = $pageTitle ?? ($course['title'] . ' Training & Certification');
$pageDesc    = $pageDesc ?? ($course['summary'] . ' Learn with experienced faculty and 100% placement support at OFSYS Education.');
$pageStyles  = ['course-detail.css'];
$hideFooterCta = true;

// ---- Content with sensible fallbacks to the shared course data ------------
$cdTagline  = $coursePage['tagline'] ?? $course['summary'];
$cdTaglineHighlight = $coursePage['tagline_highlight'] ?? '';
$cdIntro    = $coursePage['intro']   ?? $course['description'];
$cdAbout    = $coursePage['about']   ?? $course['description'];
$cdGroups   = $coursePage['learn_groups'] ?? [];
$cdCurriculum = $coursePage['curriculum'] ?? $course['curriculum'];
$cdCareers  = $coursePage['careers'] ?? $course['careers'];
// Set 'tools' => [] in a page file to hide the Tools & Platforms strip there.
$cdTools    = $coursePage['tools'] ?? $course['tools'] ?? [];
$cdAudience = $coursePage['audience'] ?? [
    ['label' => 'Students', 'icon' => 'fa-solid fa-user-graduate'],
    ['label' => 'Graduates', 'icon' => 'fa-solid fa-scroll'],
    ['label' => 'Job Seekers', 'icon' => 'fa-solid fa-magnifying-glass'],
    ['label' => 'Working Professionals', 'icon' => 'fa-solid fa-user-tie']
];
$cdClosing  = $coursePage['closing'] ?? [
    'title' => 'Start Your ' . $course['title'] . ' Journey',
    'text'  => 'Learn practical, job-ready skills with experienced trainers and dedicated placement support.'
];

include __DIR__ . '/header.php';
?>

<?php
// ---- Poster banner (shared component) ------------------------------------
require_once __DIR__ . '/page-banner-helpers.php';

$banner = [
    'title'  => pbx_stagger($course['title']),
    'image'  => $course['image'],
    'crumb'  => $course['short_title'],
    'crumbs' => [['label' => 'Courses', 'url' => BASE_PATH . '/courses']],
    'giant'  => 'COURSE',
    'meta'   => false,
    'class'  => 'pbx-course pbx-center-sm'
];
include __DIR__ . '/page-banner.php';
?>

<!-- ==========================================================================
     Body: content + sticky enquiry
     ========================================================================== -->
<section class="cd-body">
  <div class="container">
    <div class="row g-5">

      <div class="col-lg-8">

        <!-- Course Intro (the banner carries only the title) -->
        <div class="cd-block cd-intro-block" data-aos="fade-up">
          <h2 class="cd-intro-title"><?php
            $highlightPosition = $cdTaglineHighlight !== '' ? strpos($cdTagline, $cdTaglineHighlight) : false;
            if ($highlightPosition !== false) {
                echo htmlspecialchars(substr($cdTagline, 0, $highlightPosition));
                echo '<span class="cd-intro-highlight">' . htmlspecialchars($cdTaglineHighlight) . '</span>';
                echo htmlspecialchars(substr($cdTagline, $highlightPosition + strlen($cdTaglineHighlight)));
            } else {
                echo htmlspecialchars($cdTagline);
            }
          ?></h2>
          <p class="cd-text cd-text-last cd-intro-text"><?php echo htmlspecialchars($cdIntro); ?></p>
          <div class="cd-intro-cta">
            <a href="<?php echo BASE_PATH; ?>/contact" class="pbx-btn">
              Enquire Now <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <!-- About the Course -->
        <div class="cd-block" id="about" data-aos="fade-up">
          <h2 class="cd-h2">About the <em>Course</em></h2>
          <p class="cd-text cd-text-last"><?php echo htmlspecialchars($cdAbout); ?></p>
        </div>

        <!-- What You Will Learn -->
        <div class="cd-block" id="curriculum" data-aos="fade-up">
          <span class="section-kicker">What You Will Learn</span>
          <h2 class="cd-h2">A complete, practical <em>learning path</em></h2>

          <?php if ($cdGroups): ?>
            <div class="cd-modules">
              <?php foreach ($cdGroups as $gi => $grp): ?>
                <article class="cd-module" data-aos="fade-up" data-aos-delay="<?php echo ($gi % 2) * 80; ?>">
                  <header class="cd-module-head">
                    <span class="cd-module-ico"><i class="<?php echo htmlspecialchars($grp['icon']); ?>"></i></span>
                    <div class="cd-module-heading">
                      <span class="cd-module-no"><?php echo str_pad($gi + 1, 2, '0', STR_PAD_LEFT); ?></span>
                      <h3 class="cd-module-title"><?php echo htmlspecialchars($grp['title']); ?></h3>
                    </div>
                    <span class="cd-module-chev" aria-hidden="true"><i class="fa-solid fa-chevron-down"></i></span>
                  </header>
                  <ul class="cd-module-list" id="cd-module-<?php echo $gi + 1; ?>">
                    <?php foreach ($grp['items'] as $item): ?>
                      <li><i class="fa-solid fa-check"></i><span><?php echo htmlspecialchars($item); ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </article>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <ol class="cd-steps<?php echo !empty($coursePage['curriculum_two_columns']) ? ' cd-steps-two-columns' : ''; ?><?php echo !empty($coursePage['curriculum_three_columns']) ? ' cd-steps-three-columns' : ''; ?>">
              <?php foreach ($cdCurriculum as $mod): ?>
                <li><span><?php echo htmlspecialchars($mod); ?></span></li>
              <?php endforeach; ?>
            </ol>
          <?php endif; ?>

          <?php if (!empty($cdTools)): ?>
            <div class="cd-tools">
              <span class="cd-tools-label"><i class="fa-solid fa-toolbox"></i> Tools &amp; Platforms</span>
              <div class="cd-tools-list">
                <?php foreach ($cdTools as $t): ?>
                  <span class="cd-tool"><?php echo htmlspecialchars($t); ?></span>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- Career Opportunities -->
        <div class="cd-block" id="careers" data-aos="fade-up">
          <span class="section-kicker">Career Opportunities</span>
          <h2 class="cd-h2">Roles you can <em>step into</em></h2>
          <p class="cd-text">After completing the course, learners can explore roles such as:</p>

          <div class="cd-roles">
            <?php foreach ($cdCareers as $role): ?>
              <span class="cd-role">
                <i class="fa-solid fa-circle-check"></i>
                <?php echo htmlspecialchars($role); ?>
              </span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Who Can Join -->
        <div class="cd-block" id="who" data-aos="fade-up">
          <span class="section-kicker">Who Can Join?</span>
          <h2 class="cd-h2">Built for <em>every starting point</em></h2>

          <div class="cd-audience">
            <?php foreach ($cdAudience as $aud): ?>
              <div class="cd-aud">
                <span class="cd-aud-ico"><i class="<?php echo htmlspecialchars($aud['icon']); ?>"></i></span>
                <span class="cd-aud-label"><?php echo htmlspecialchars($aud['label']); ?></span>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Closing CTA -->
        <div class="cd-final" data-aos="fade-up">
          <span class="cd-final-doodle cd-final-doodle-l" aria-hidden="true"><i class="fa-solid fa-graduation-cap"></i></span>
          <span class="cd-final-doodle cd-final-doodle-r" aria-hidden="true"><i class="fa-solid fa-book-open"></i></span>

          <div class="cd-final-copy">
            <h2><?php echo htmlspecialchars($cdClosing['title']); ?></h2>
            <p><?php echo htmlspecialchars($cdClosing['text']); ?></p>
            <a href="<?php echo BASE_PATH; ?>/contact" class="cd-final-btn">
              Enquire Now <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>

      </div>

      <!-- Right Sticky Enquiry Form Col -->
      <div class="col-lg-4" data-aos="fade-left">
        <div class="p-4 rounded-4 border border-danger border-opacity-25 bg-white shadow-sm sticky-top" style="top: 110px;">
          <h4 class="fw-bold text-dark mb-1 font-edu">Quick Enquiry</h4>
          <p class="text-muted small mb-3 font-edu">Fill this form to check seat availability &amp; fee structure.</p>

          <form action="<?php echo BASE_PATH; ?>/form-handler.php" method="POST" class="ajax-enquiry-form">
            <input type="hidden" name="form_type" value="course_sidebar">
            <input type="hidden" name="interested_course" value="<?php echo htmlspecialchars($course['title']); ?>">

            <div class="mb-3">
              <label class="form-label-custom">Full Name</label>
              <input type="text" name="full_name" class="form-control-custom" placeholder="Your name" autocomplete="name" autocapitalize="words" required>
            </div>

            <div class="mb-3">
              <label class="form-label-custom">Phone Number</label>
              <input type="tel" name="phone_number" class="form-control-custom" placeholder="Mobile number" inputmode="tel" autocomplete="tel" required>
            </div>

            <div class="mb-3">
              <label class="form-label-custom">Preferred Campus</label>
              <select name="preferred_branch" class="form-select-custom" required>
                <option value="" selected disabled>Select Campus</option>
                <?php foreach ($branches as $bItem): ?>
                  <option value="<?php echo htmlspecialchars($bItem['name']); ?>"><?php echo htmlspecialchars($bItem['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label-custom">Your Message</label>
              <textarea name="message" class="form-control-custom" rows="2" placeholder="Any questions?"></textarea>
            </div>

            <div class="form-response-alert" style="display: none;"></div>

            <button type="submit" class="btn-ofsys-primary w-100 py-2">
              Submit &amp; Claim 20% Discount
            </button>
          </form>

          <div class="mt-4 pt-3 border-top text-center">
            <small class="text-muted d-block mb-1 font-edu">Prefer to speak directly?</small>
            <a href="tel:<?php echo PHONE_PRIMARY; ?>" class="fw-bold text-danger text-decoration-none font-edu">
              <i class="fa-solid fa-phone me-1"></i> <?php echo PHONE_DISPLAY; ?>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>
