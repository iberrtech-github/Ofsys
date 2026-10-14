<?php
/**
 * Enroll Now & 20% Discount Popup Modal
 * Designed specifically according to user reference screenshot media_1791354365593.png
 */
?>
<div class="modal fade modal-discount" id="enrollDiscountModal" tabindex="-1" aria-labelledby="enrollDiscountModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="w-100">
          <h3 class="modal-title" id="enrollDiscountModalLabel">
            Enroll Now &amp; Get <span style="color: #E11D2A;">20% Off!</span>
          </h3>
          <p class="modal-subtitle">Fill the form below to claim your discount</p>
        </div>
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="modal-body">
        <form action="<?php echo BASE_PATH; ?>/form-handler.php" method="POST" class="ajax-enquiry-form">
          <input type="hidden" name="form_type" value="discount_modal">
          
          <div class="mb-3">
            <label class="form-label-custom" for="modalFullName">Full Name</label>
            <input type="text" class="form-control-custom" id="modalFullName" name="full_name" placeholder="Enter your name" required>
          </div>

          <div class="mb-3">
            <label class="form-label-custom" for="modalPhone">Phone Number</label>
            <input type="tel" class="form-control-custom" id="modalPhone" name="phone_number" placeholder="Enter your mobile number" required>
          </div>

          <div class="mb-3">
            <label class="form-label-custom" for="modalCourse">Interested Course</label>
            <select class="form-select-custom" id="modalCourse" name="interested_course" required>
              <option value="" selected disabled>Select Course</option>
              <?php foreach ($courses as $cKey => $c): ?>
                <option value="<?php echo htmlspecialchars($c['title']); ?>"><?php echo htmlspecialchars($c['title']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label-custom" for="modalBranch">Preferred Branch</label>
            <select class="form-select-custom" id="modalBranch" name="preferred_branch" required>
              <option value="" selected disabled>Select Branch</option>
              <?php foreach ($branches as $bItem): ?>
                <option value="<?php echo htmlspecialchars($bItem['name']); ?>"><?php echo htmlspecialchars($bItem['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label-custom" for="modalMessage">Message (Optional)</label>
            <textarea class="form-control-custom" id="modalMessage" name="message" rows="2" placeholder="How can we help you?"></textarea>
          </div>

          <div class="form-response-alert" style="display: none;"></div>

          <button type="submit" class="btn-modal-submit">
            Send Enquiry
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
