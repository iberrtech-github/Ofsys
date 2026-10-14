<?php
/**
 * OFSYS Education - Gallery.
 * Every photo shown here is managed from the admin panel (admin/gallery.php).
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Gallery';
$pageDesc  = 'Photos from the classrooms, labs, workshops and events at OFSYS Education.';
$pageStyles = ['gallery.css'];
$hideFooterCta = true;

$galleryItems = ofsy_get_gallery_items();

include __DIR__ . '/includes/header.php';
?>

<?php
$banner = [
  'title' => 'Our<span class="pbx-ind"><em>Gallery</em></span>',
  'image' => '',
  'crumb' => 'Gallery',
  'giant' => 'GALLERY',
  'meta'  => false,
  'class' => 'pbx-center-sm pbx-title-sm'
];
include __DIR__ . '/includes/page-banner.php';
?>

<section class="gl-section">
  <div class="container">

    <div class="gl-header" data-aos="fade-up">
      <span class="section-kicker">Life at OFSYS</span>
    </div>

    <?php if (!$galleryItems): ?>

      <div class="gl-empty" data-aos="fade-up">
        <span class="gl-empty-icon"><i class="fa-solid fa-images"></i></span>
        <h3>Photos coming soon</h3>
        <p>We are updating our gallery with the latest photos from our classrooms, labs and events.</p>
        <a href="<?php echo BASE_PATH; ?>/contact" class="theme-btn">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
      </div>

    <?php else: ?>

      <div class="gl-grid" id="galleryGrid">
        <?php $glDelay = 0; foreach ($galleryItems as $item): ?>
          <?php $src = ofsy_image_src($item['image']); ?>
          <figure class="gl-item"
                  data-aos="fade-up" data-aos-delay="<?php echo $glDelay; ?>">
            <button type="button" class="gl-open"
                    data-src="<?php echo htmlspecialchars($src); ?>"
                    data-title="<?php echo htmlspecialchars($item['title']); ?>"
                    aria-label="<?php echo htmlspecialchars($item['title'] ?: 'View photo'); ?>">
              <img src="<?php echo htmlspecialchars($src); ?>"
                   alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
              <span class="gl-zoom"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
              <?php if (!empty($item['title'])): ?>
                <figcaption><?php echo htmlspecialchars($item['title']); ?></figcaption>
              <?php endif; ?>
            </button>
          </figure>
        <?php $glDelay = ($glDelay + 100) % 300; endforeach; ?>
      </div>

    <?php endif; ?>

  </div>
</section>

<?php if ($galleryItems): ?>
<!-- Lightbox -->
<div class="gl-lightbox" id="glLightbox" role="dialog" aria-modal="true" aria-label="Photo viewer" hidden>
  <button type="button" class="gl-close" id="glClose" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
  <button type="button" class="gl-nav gl-prev" id="glPrev" aria-label="Previous photo"><i class="fa-solid fa-chevron-left"></i></button>
  <figure class="gl-lightbox-inner">
    <img id="glImage" src="" alt="">
    <figcaption>
      <strong id="glLbTitle"></strong>
    </figcaption>
  </figure>
  <button type="button" class="gl-nav gl-next" id="glNext" aria-label="Next photo"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<script>
(function () {
  var grid = document.getElementById('galleryGrid');
  if (!grid) return;

  var items = Array.prototype.slice.call(grid.querySelectorAll('.gl-item'));

  // Lightbox
  var box = document.getElementById('glLightbox');
  var image = document.getElementById('glImage');
  var lbTitle = document.getElementById('glLbTitle');
  var current = 0;

  function visibleTriggers() {
    return items.map(function (i) { return i.querySelector('.gl-open'); });
  }

  function show(index) {
    var list = visibleTriggers();
    if (!list.length) return;
    current = (index + list.length) % list.length;
    var trigger = list[current];
    image.src = trigger.getAttribute('data-src');
    image.alt = trigger.getAttribute('data-title') || '';
    lbTitle.textContent = trigger.getAttribute('data-title') || '';
  }

  function open(index) {
    show(index);
    box.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function close() {
    box.hidden = true;
    image.src = '';
    document.body.style.overflow = '';
  }

  grid.addEventListener('click', function (e) {
    var trigger = e.target.closest('.gl-open');
    if (!trigger) return;
    open(visibleTriggers().indexOf(trigger));
  });

  document.getElementById('glClose').addEventListener('click', close);
  document.getElementById('glPrev').addEventListener('click', function () { show(current - 1); });
  document.getElementById('glNext').addEventListener('click', function () { show(current + 1); });
  box.addEventListener('click', function (e) { if (e.target === box) close(); });

  document.addEventListener('keydown', function (e) {
    if (box.hidden) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowLeft') show(current - 1);
    if (e.key === 'ArrowRight') show(current + 1);
  });
})();
</script>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
