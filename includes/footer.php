<?php
/**
 * OFSYS Education - Ultra-Premium Master Footer
 * Perfectly Aligned, Brand-Themed & High-Impact
 */
?>
  <!-- Master Footer -->
  <footer class="site-footer">
    <div class="container pt-5">

      <?php if (empty($hideFooterCta)): ?>
      <!-- Footer CTA -->
      <div class="footer-cta d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4 mb-4" data-aos="fade-up">
        <div style="position: relative; z-index: 1;">
          <h3>Your Career Starts With the Right Skills.</h3>
          <p>Talk to our admissions team today and claim a flat 20% early bird scholarship.</p>
        </div>
        <div class="d-flex flex-wrap gap-3" style="position: relative; z-index: 1;">
          <a href="tel:<?php echo HF_PHONE; ?>" class="btn-arrow dark">
            <?php echo HF_PHONE_DISPLAY; ?> <span class="arrow-circle"><i class="fa-solid fa-phone"></i></span>
          </a>
          <button type="button" class="btn-arrow gold trigger-discount-modal">
            Claim 20% Off <span class="arrow-circle"><i class="fa-solid fa-arrow-right"></i></span>
          </button>
        </div>
      </div>
      <?php endif; ?>

      <div class="row g-4 pb-4">
        <!-- Brand -->
        <div class="col-lg-4 col-md-6">
          <a href="<?php echo BASE_PATH; ?>/" class="d-inline-block mb-3">
            <img src="<?php echo BASE_PATH; ?>/assets/images/logo-white.png" alt="OFSYS Education" width="400" height="154" loading="lazy" decoding="async" style="height: 56px; width: auto; object-fit: contain;">
          </a>
          <p class="mb-3" style="font-size: 0.9rem; line-height: 1.7;">
           OFSYS Education offers job-focused training in SAP, Gen AI, Data, Full Stack Development, Digital Marketing, and Office Administration with placement support.
          <div class="footer-social d-flex gap-2">
            <a href="https://www.instagram.com/ofsyseducations_official" target="_blank" rel="noopener" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.facebook.com/p/OFSYS-Educations-61578847598933/" target="_blank" rel="noopener" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="https://www.linkedin.com/company/ofsys-educations/" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
            <a href="<?php echo WHATSAPP_LINK; ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
          </div>
        </div>
                <!-- Quick Links -->
        <div class="col-lg-2 col-md-6">
          <h5>Quick Links</h5>
          <ul class="footer-links quick-links">
            <li><a href="<?php echo BASE_PATH; ?>/">Home</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/about">About</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/courses">Courses</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/placements">Placements</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/gallery">Gallery</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/blog">Blog</a></li>
            <li><a href="<?php echo BASE_PATH; ?>/contact">Contact Us</a></li>
          </ul>
        </div>

        <!-- Programs -->
        <div class="col-lg-3 col-md-6">
          <h5>Career Programs</h5>
          <ul class="footer-links">
            <?php foreach ($courses as $cSlug => $cItem): ?>
              <li><a href="<?php echo BASE_PATH; ?>/courses/<?php echo $cSlug; ?>"><?php echo htmlspecialchars($cItem['short_title']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>



        <!-- Contact -->
        <div class="col-lg-3 col-md-6">
          <h5>Head Office</h5>
          <ul class="list-unstyled footer-contact mb-0">
            <li><i class="fa-solid fa-location-dot"></i><span>VL Arcade, Bypass road, near Gulf own mobiles, Padippura, Perinthalmanna, Keralam - 679322</span></li>
            <li><i class="fa-solid fa-phone"></i><a href="tel:<?php echo HF_PHONE; ?>"><?php echo HF_PHONE_DISPLAY; ?></a></li>
            <li><i class="fa-solid fa-envelope"></i><a href="mailto:<?php echo EMAIL_PRIMARY; ?>"><?php echo EMAIL_PRIMARY; ?></a></li>
           
          </ul>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div>&copy; <?php echo date('Y'); ?> <strong class="text-white">OFSYS Education</strong>. All Rights Reserved.</div>
        <div>Developed by <a href="https://iberrtech.com" target="_blank" rel="noopener"><strong class="text-white">Iberr Technologies</strong></a></div>
      </div>
    </div>
  </footer>

  <!-- Include Discount Modal (User Reference UI) -->
  <?php include __DIR__ . '/enquiry-modal.php'; ?>

  <!-- Choose how to send the enquiry, shown right after a form is submitted -->
  <div class="modal fade modal-lead-choice" id="leadChoiceModal" tabindex="-1" aria-labelledby="leadChoiceTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="modal-body text-center">
          <span class="lc-tick"><i class="fa-solid fa-check"></i></span>
          <h3 class="lc-title" id="leadChoiceTitle">Enquiry Received!</h3>
          <p class="lc-text">Send it to us directly and get a faster reply.</p>
          <div class="lc-opts">
            <a class="lc-btn lc-wa" id="leadChoiceWa" href="#" target="_blank" rel="noopener">
              <i class="fa-brands fa-whatsapp"></i>
              <span><strong>Through WhatsApp</strong><small><?php echo HF_PHONE_DISPLAY; ?></small></span>
            </a>
            <a class="lc-btn lc-mail" id="leadChoiceMail" href="#">
              <i class="fa-solid fa-envelope"></i>
              <span><strong>Through Mail</strong><small><?php echo EMAIL_PRIMARY; ?></small></span>
            </a>
          </div>
          <p class="lc-status" role="status"></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Include Floating WhatsApp & Phone Bar -->
  <?php include __DIR__ . '/whatsapp-float.php'; ?>

  <!-- Scripts -->
  <!-- Every script is deferred: they download in parallel while the page
       paints, then run in order once parsing is done. -->
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
  <script>
    // Contact details for the post-submit follow-up options
    window.OFSYS_CONTACT = {
      whatsapp: <?php echo json_encode(PHONE_CLEAN); ?>,
      phoneDisplay: <?php echo json_encode(HF_PHONE_DISPLAY); ?>,
      email: <?php echo json_encode(EMAIL_PRIMARY); ?>,
      mailEndpoint: <?php echo json_encode(BASE_PATH . '/mail-enquiry.php'); ?>
    };
  </script>
  <?php
  // Same last-modified stamp as the stylesheets, so script edits are picked up.
  foreach (['main.js', 'animations.js'] as $jsFile):
      $jsPath = dirname(__DIR__) . '/assets/js/' . $jsFile;
      $jsVer  = is_file($jsPath) ? '?v=' . filemtime($jsPath) : '';
  ?>
  <script defer src="<?php echo BASE_PATH; ?>/assets/js/<?php echo $jsFile . $jsVer; ?>"></script>
  <?php endforeach; ?>
</body>
</html>
