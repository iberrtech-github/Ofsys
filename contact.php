<?php
/**
 * OFSYS Education - Official Contact & Branch Directory (Ultra-Creative Brand Theme)
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Contact Us & Branch Campuses';
$pageDesc = 'Get in touch with OFSYS Education. Visit our branches in Manjeri, Nilambur, Perinthalmanna and Ernakulam. Call +91 99463 30438.';
$pageStyles = ['contact.css'];
$hideFooterCta = true;

$branchCards = $branches;
if (isset($branchCards['perinthalmanna'])) {
  $perinthalmanna = ['perinthalmanna' => $branchCards['perinthalmanna']];
  unset($branchCards['perinthalmanna']);
  $branchCards = $perinthalmanna + $branchCards;
}

include __DIR__ . '/includes/header.php';
?>

<!-- Page Banner -->
<?php
$banner = [
  'title' => 'Get in<span class="pbx-ind"><em>Touch</em></span>',
  'image' => '',
  'crumb' => 'Contact Us',
  'giant' => 'CONTACT',
  'meta'  => false,
  'class' => 'pbx-center-sm pbx-title-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<!-- Contact: help panel + form -->
<section class="ct-main">
  <div class="container">
    <div class="ct-wrap">

      <div class="ct-help" data-aos="fade-right">
        <span class="section-kicker">Contact Us</span>
        <h2>How can we <em>help?</em></h2>
        <p class="ct-help-lead">Have questions about admissions, fees, syllabus, or branch locations? Speak with our admissions advisors today.</p>

        <div class="ct-help-list">
          <a href="tel:<?php echo HF_PHONE; ?>" class="ct-help-item">
            <span class="ct-help-icon"><i class="fa-solid fa-phone"></i></span>
            <span><strong>Call Us</strong><em><?php echo HF_PHONE_DISPLAY; ?></em></span>
          </a>
          <a href="mailto:<?php echo EMAIL_PRIMARY; ?>" class="ct-help-item">
            <span class="ct-help-icon"><i class="fa-solid fa-envelope"></i></span>
            <span><strong>Email Us</strong><em><?php echo EMAIL_PRIMARY; ?></em></span>
          </a>
          <div class="ct-help-item">
            <span class="ct-help-icon"><i class="fa-solid fa-clock"></i></span>
            <span><strong>Working Hours</strong><em class="muted">Mon - Sat: 9:00 AM - 5:00 PM</em></span>
          </div>
        </div>
      </div>

      <div class="ct-form-card" data-aos="fade-left">
        <h2>Send us a Message</h2>
        <form action="<?php echo BASE_PATH; ?>/form-handler.php" method="POST" class="ajax-enquiry-form ct-form">
          <input type="hidden" name="form_type" value="contact_page">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="ct-label" for="ctName">Your Name</label>
              <input type="text" id="ctName" name="full_name" class="ct-input" placeholder="Enter your name" autocomplete="name" autocapitalize="words" required>
            </div>
            <div class="col-md-6">
              <label class="ct-label" for="ctPhone">Phone Number</label>
              <input type="tel" id="ctPhone" name="phone_number" class="ct-input" placeholder="Enter your mobile number" inputmode="tel" autocomplete="tel" required>
            </div>
            <div class="col-md-6">
              <label class="ct-label" for="ctEmail">Email Address</label>
              <input type="email" id="ctEmail" name="email" class="ct-input" placeholder="name@example.com" inputmode="email" autocomplete="email" autocapitalize="off" spellcheck="false">
            </div>
            <div class="col-md-6">
              <label class="ct-label" for="ctBranch">Nearest Branch</label>
              <select id="ctBranch" name="preferred_branch" class="ct-input" required>
                <option value="" selected disabled>Select Branch</option>
                <?php foreach ($branches as $bItem): ?>
                  <option value="<?php echo htmlspecialchars($bItem['name']); ?>"><?php echo htmlspecialchars($bItem['name']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="ct-label" for="ctCourse">Interested Course</label>
              <select id="ctCourse" name="interested_course" class="ct-input" required>
                <option value="" selected disabled>Select Course</option>
                <?php foreach ($courses as $cItem): ?>
                  <option value="<?php echo htmlspecialchars($cItem['title']); ?>"><?php echo htmlspecialchars($cItem['title']); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="ct-label" for="ctMessage">Message</label>
              <textarea id="ctMessage" name="message" class="ct-input" rows="4" placeholder="Type your query here..."></textarea>
            </div>
            <div class="col-12">
              <div class="form-response-alert" style="display: none;"></div>
              <button type="submit" class="ct-submit">Send Message <i class="fa-solid fa-paper-plane"></i></button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</section>

<!-- Our Branches -->
<section class="branch-section">
  <div class="container">
    <div class="branch-header" data-aos="fade-up">
      <span class="section-kicker">Visit Us</span>
      <h2 class="branch-title">Our <em>Branches</em></h2>
      <div class="branch-divider"></div>
    </div>

    <div class="branch-grid">
      <?php $bDelay = 0; foreach ($branchCards as $bItem): ?>
        <article class="branch-card" data-aos="fade-up" data-aos-delay="<?php echo $bDelay; ?>">
          <?php if (!empty($bItem['badge'])): ?>
            <span class="branch-badge"><?php echo htmlspecialchars($bItem['badge']); ?></span>
          <?php endif; ?>
          <h3 class="branch-name"><?php echo htmlspecialchars($bItem['name']); ?></h3>
          <div class="branch-details">
            <div class="branch-item">
              <span class="branch-icon"><i class="fa-solid fa-location-dot"></i></span>
              <span><?php echo implode('<br>', array_map('htmlspecialchars', $bItem['address'])); ?></span>
            </div>
          </div>
          <a href="<?php echo !empty($bItem['map_url']) ? htmlspecialchars($bItem['map_url']) : 'https://www.google.com/maps/search/?api=1&amp;query=' . urlencode($bItem['map_query']); ?>" target="_blank" rel="noopener" class="branch-action">View on Map <i class="fa-solid fa-arrow-right"></i></a>
        </article>
      <?php $bDelay += 100; endforeach; ?>
    </div>
  </div>
</section>



<?php include __DIR__ . '/includes/footer.php'; ?>
