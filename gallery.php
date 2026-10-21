<?php
/**
 * OFSYS Education - Gallery.
 * Photos come from admin/gallery.php, videos from admin/gallery-videos.php.
 * Both are shown in one grid, with filter buttons to narrow it down.
 */
require_once __DIR__ . '/includes/data.php';

$pageTitle = 'Gallery';
$pageDesc  = 'Photos and videos from the classrooms, labs, workshops and events at OFSYS Education.';
$pageStyles = ['gallery.css'];
$hideFooterCta = true;

$galleryItems  = ofsy_get_gallery_items();
$galleryVideos = ofsy_get_gallery_videos();

/* One list of tiles, so a photo and a video can sit side by side. Each entry
   keeps only what the tile needs: the poster to show, what opening it does,
   and the order the admin gave it. */
$galleryTiles = [];

foreach ($galleryItems as $item) {
    $full = ofsy_image_src($item['image']);
    if ($full === '') {
        continue;
    }
    $thumb = ofsy_image_src($item['thumbnail'] ?? '');
    $alt   = trim((string) ($item['alt_text'] ?? '')) !== '' ? $item['alt_text'] : $item['title'];

    $galleryTiles[] = [
        'type'   => 'photo',
        'title'  => (string) $item['title'],
        'alt'    => (string) $alt,
        'poster' => $thumb !== '' ? $thumb : $full,
        'src'    => $full,
        'embed'  => '',
        'order'  => (int) $item['sort_order'],
        'id'     => (int) $item['id'],
    ];
}

foreach ($galleryVideos as $video) {
    $embed = ofsy_video_embed_url($video);
    if ($embed === '') {
        continue;
    }

    $galleryTiles[] = [
        'type'   => 'video',
        'title'  => (string) $video['title'],
        'alt'    => (string) $video['title'],
        'poster' => ofsy_video_thumb($video),
        'src'    => '',
        'embed'  => $embed,
        'order'  => (int) $video['sort_order'],
        'id'     => (int) $video['id'],
    ];
}

// Smaller order first, and the newest of an equal order ahead of the rest -
// the same rule the two admin lists use.
usort($galleryTiles, function ($a, $b) {
    return $a['order'] === $b['order']
        ? $b['id'] <=> $a['id']
        : $a['order'] <=> $b['order'];
});

$photoCount = count(array_filter($galleryTiles, function ($tile) { return $tile['type'] === 'photo'; }));
$videoCount = count($galleryTiles) - $photoCount;

// With only one kind on the page the filters would do nothing, so they are
// left out and the grid is shown on its own.
$showFilters = $photoCount > 0 && $videoCount > 0;

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

    <?php if ($showFilters): ?>
      <div class="gl-filters" data-aos="fade-up">
        <button type="button" class="gl-filter is-active" data-filter="all" aria-pressed="true">
          <i class="fa-solid fa-border-all"></i> All
          <span class="gl-filter-count"><?php echo count($galleryTiles); ?></span>
        </button>
        <button type="button" class="gl-filter" data-filter="photo" aria-pressed="false">
          <i class="fa-solid fa-images"></i> Photos
          <span class="gl-filter-count"><?php echo $photoCount; ?></span>
        </button>
        <button type="button" class="gl-filter" data-filter="video" aria-pressed="false">
          <i class="fa-solid fa-circle-play"></i> Videos
          <span class="gl-filter-count"><?php echo $videoCount; ?></span>
        </button>
      </div>
    <?php endif; ?>

    <?php if (!$galleryTiles): ?>

      <div class="gl-empty" data-aos="fade-up">
        <span class="gl-empty-icon"><i class="fa-solid fa-images"></i></span>
        <h3>Photos coming soon</h3>
        <p>We are updating our gallery with the latest photos from our classrooms, labs and events.</p>
        <a href="<?php echo BASE_PATH; ?>/contact" class="theme-btn">Contact Us <i class="fa-solid fa-arrow-right"></i></a>
      </div>

    <?php else: ?>

      <div class="gl-grid" id="galleryGrid">
        <?php $glDelay = 0; foreach ($galleryTiles as $tile): ?>
          <figure class="gl-item <?php echo $tile['type'] === 'video' ? 'gl-item-video' : ''; ?>"
                  data-type="<?php echo $tile['type']; ?>"
                  data-aos="fade-up" data-aos-delay="<?php echo $glDelay; ?>">
            <button type="button" class="gl-open"
                    data-type="<?php echo $tile['type']; ?>"
                    data-src="<?php echo htmlspecialchars($tile['src']); ?>"
                    data-embed="<?php echo htmlspecialchars($tile['embed']); ?>"
                    data-title="<?php echo htmlspecialchars($tile['title']); ?>"
                    data-alt="<?php echo htmlspecialchars($tile['alt']); ?>"
                    aria-label="<?php
                      echo htmlspecialchars(($tile['type'] === 'video' ? 'Play ' : 'View ')
                        . ($tile['title'] !== '' ? $tile['title'] : $tile['type']));
                    ?>">
              <?php if ($tile['poster'] !== ''): ?>
                <!-- The grid loads the small copy; the full image or the
                     player is only fetched once a tile is opened. -->
                <img src="<?php echo htmlspecialchars($tile['poster']); ?>"
                     alt="<?php echo htmlspecialchars($tile['alt']); ?>" loading="lazy">
              <?php endif; ?>
              <span class="gl-zoom">
                <i class="fa-solid <?php echo $tile['type'] === 'video' ? 'fa-play' : 'fa-magnifying-glass-plus'; ?>"></i>
              </span>
              <?php if ($tile['type'] === 'video'): ?>
                <span class="gl-badge"><i class="fa-solid fa-circle-play"></i> Video</span>
              <?php endif; ?>
              <?php if ($tile['title'] !== ''): ?>
                <figcaption><?php echo htmlspecialchars($tile['title']); ?></figcaption>
              <?php endif; ?>
            </button>
          </figure>
        <?php $glDelay = ($glDelay + 100) % 300; endforeach; ?>
      </div>

      <p class="gl-none" id="glNone" hidden>Nothing to show in this section yet.</p>

    <?php endif; ?>

  </div>
</section>

<?php if ($galleryTiles): ?>
<!-- Lightbox: shows a photo, or the video player for a video tile -->
<div class="gl-lightbox" id="glLightbox" role="dialog" aria-modal="true" aria-label="Gallery viewer" hidden>
  <button type="button" class="gl-close" id="glClose" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
  <button type="button" class="gl-nav gl-prev" id="glPrev" aria-label="Previous"><i class="fa-solid fa-chevron-left"></i></button>
  <figure class="gl-lightbox-inner">
    <img id="glImage" src="" alt="">
    <div class="gl-lightbox-video" id="glVideoWrap" hidden>
      <!-- No src until a video is opened: an empty one would make the
           browser load this page inside the frame. -->
      <iframe id="glFrame" title="" frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
              allowfullscreen></iframe>
    </div>
    <figcaption>
      <strong id="glLbTitle"></strong>
    </figcaption>
  </figure>
  <button type="button" class="gl-nav gl-next" id="glNext" aria-label="Next"><i class="fa-solid fa-chevron-right"></i></button>
</div>

<script>
(function () {
  var grid = document.getElementById('galleryGrid');
  if (!grid) return;

  var items = Array.prototype.slice.call(grid.querySelectorAll('.gl-item'));
  var filters = Array.prototype.slice.call(document.querySelectorAll('.gl-filter'));
  var none = document.getElementById('glNone');

  var box = document.getElementById('glLightbox');
  var image = document.getElementById('glImage');
  var videoWrap = document.getElementById('glVideoWrap');
  var frame = document.getElementById('glFrame');
  var lbTitle = document.getElementById('glLbTitle');
  var current = 0;

  /* ------------------------------------------------------------- Filtering */
  function applyFilter(type) {
    var shown = 0;
    items.forEach(function (item) {
      var show = type === 'all' || item.getAttribute('data-type') === type;
      item.hidden = !show;
      if (show) shown++;
    });
    if (none) none.hidden = shown > 0;
  }

  filters.forEach(function (button) {
    button.addEventListener('click', function () {
      filters.forEach(function (other) {
        var on = other === button;
        other.classList.toggle('is-active', on);
        other.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      applyFilter(button.getAttribute('data-filter'));
    });
  });

  /* -------------------------------------------------------------- Lightbox */
  // Only what the current filter leaves on screen, so the arrows stay
  // inside the section the visitor is looking at.
  function visibleTriggers() {
    return items
      .filter(function (item) { return !item.hidden; })
      .map(function (item) { return item.querySelector('.gl-open'); });
  }

  function stopVideo() {
    frame.removeAttribute('src');
    videoWrap.hidden = true;
  }

  function show(index) {
    var list = visibleTriggers();
    if (!list.length) return;
    current = (index + list.length) % list.length;

    var trigger = list[current];
    var title = trigger.getAttribute('data-title') || '';
    lbTitle.textContent = title;

    if (trigger.getAttribute('data-type') === 'video') {
      image.hidden = true;
      image.removeAttribute('src');
      videoWrap.hidden = false;
      frame.setAttribute('title', title || 'Video');
      frame.setAttribute('src', trigger.getAttribute('data-embed'));
    } else {
      stopVideo();
      image.hidden = false;
      image.src = trigger.getAttribute('data-src');
      image.alt = trigger.getAttribute('data-alt') || title;
    }
  }

  function open(index) {
    show(index);
    box.hidden = false;
    document.body.style.overflow = 'hidden';
  }

  function close() {
    box.hidden = true;
    image.removeAttribute('src');
    stopVideo();
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
