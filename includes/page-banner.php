<?php
/**
 * Reusable Inner Page Banner  —  "admission poster" layout
 *
 * Editorial poster banner: heavy stacked headline on the left, red pill badge,
 * quote line, CTA + contact strip, Memphis-style doodles, bold diagonal slashes
 * through the middle and a photo bleeding off the right edge.
 *
 * Expects $banner with any of:
 *   'tag'    string   red pill badge under the headline
 *   'title'  string   headline HTML (<em> renders in brand red)
 *   'desc'   string   quote line under the badge
 *   'image'  string   right-hand photo; pass '' or false for the text-only
 *                     variant used by Placements / Gallery / Contact
 *   'crumb'  string   last breadcrumb label
 *   'crumbs' array    [['label' => '', 'url' => ''], ...] middle levels
 *   'chips'  array    [['icon' => '', 'text' => '', 'style' => 'solid|gold|'], ...]
 *   'cta'    array    ['label' => '', 'href' => ''] or
 *                     ['label' => '', 'modal' => 'Course Name'] for the enquiry modal
 *   'link'   array    ['label' => '', 'href' => '', 'icon' => ''] secondary ghost link
 *   'meta'   bool     show the phone + website strip (default true)
 *   'giant'  string   outlined watermark word
 *   'extra'  string   raw HTML appended under the actions
 *   'class'  string   optional banner modifier class
 */
require_once __DIR__ . '/page-banner-helpers.php';

$banner = array_merge([
    'tag'    => '',
    'title'  => '',
    'desc'   => '',
    'image'  => $page_banners['about'],
    'crumb'  => '',
    'crumbs' => [],
    'chips'  => [],
    'cta'    => [],
    'link'   => [],
    'meta'   => true,
    'giant'  => 'OFSYS',
    'extra'  => '',
    'class'  => ''
], $banner ?? []);

$pbxHasImage = !empty($banner['image']);
?>
<section class="pbx<?php echo $pbxHasImage ? '' : ' pbx-noimg'; ?><?php echo $banner['class'] ? ' ' . htmlspecialchars($banner['class']) : ''; ?>">

  <!-- Diagonal brand slashes ---------------------------------------------- -->
  <div class="pbx-slashes" aria-hidden="true">
    <span class="pbx-slash pbx-slash-ink"></span>
    <span class="pbx-slash pbx-slash-red"></span>
    <span class="pbx-slash pbx-slash-thin"></span>
    <span class="pbx-slash pbx-slash-gold"></span>
  </div>

  <div class="bg-giant-text" aria-hidden="true"><?php echo htmlspecialchars($banner['giant']); ?></div>

  <div class="container">
    <div class="pbx-grid">

      <div class="pbx-copy" data-aos="fade-up">

      

        <h1 class="pbx-title"><?php echo $banner['title']; ?></h1>

        <?php if ($banner['tag']): ?>
          <span class="pbx-badge"><?php echo htmlspecialchars($banner['tag']); ?></span>
        <?php endif; ?>

        <?php if ($banner['chips']): ?>
          <div class="pbx-chips">
            <?php foreach ($banner['chips'] as $chip): ?>
              <span class="pbx-chip<?php echo !empty($chip['style']) ? ' pbx-chip-' . htmlspecialchars($chip['style']) : ''; ?>">
                <?php if (!empty($chip['icon'])): ?><i class="<?php echo htmlspecialchars($chip['icon']); ?>" aria-hidden="true"></i><?php endif; ?>
                <?php echo htmlspecialchars($chip['text']); ?>
              </span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($banner['desc']): ?>
          <p class="pbx-quote"><?php echo htmlspecialchars($banner['desc']); ?></p>
        <?php endif; ?>

        <?php if ($banner['cta'] || $banner['link']): ?>
          <div class="pbx-actions">
            <?php if ($banner['cta']): ?>
              <?php if (isset($banner['cta']['modal'])): ?>
                <button type="button" class="pbx-btn trigger-discount-modal" data-course="<?php echo htmlspecialchars($banner['cta']['modal']); ?>">
                  <?php echo htmlspecialchars($banner['cta']['label']); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </button>
              <?php else: ?>
                <a href="<?php echo htmlspecialchars($banner['cta']['href']); ?>" class="pbx-btn">
                  <?php echo htmlspecialchars($banner['cta']['label']); ?> <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>
              <?php endif; ?>
            <?php endif; ?>
            <?php if ($banner['link']): ?>
              <a href="<?php echo htmlspecialchars($banner['link']['href']); ?>" class="pbx-ghost">
                <i class="<?php echo htmlspecialchars($banner['link']['icon'] ?? 'fa-solid fa-circle-info'); ?>" aria-hidden="true"></i>
                <?php echo htmlspecialchars($banner['link']['label']); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if ($banner['meta']): ?>
          <div class="pbx-meta">
            <a href="tel:<?php echo HF_PHONE; ?>"><i class="fa-solid fa-phone" aria-hidden="true"></i> <?php echo HF_PHONE_DISPLAY; ?></a>
            <span class="pbx-meta-sep" aria-hidden="true"></span>
            <a href="mailto:<?php echo EMAIL_PRIMARY; ?>"><i class="fa-solid fa-globe" aria-hidden="true"></i> <?php echo EMAIL_PRIMARY; ?></a>
          </div>
        <?php endif; ?>

        <?php echo $banner['extra']; ?>
      </div>

    </div>
  </div>

  <?php if ($pbxHasImage): ?>
    <!-- Full-bleed photo with a diagonal cut on the same lean as the slashes -->
    <div class="pbx-media">
      <div class="pbx-media-frame">
        <img src="<?php echo htmlspecialchars($banner['image']); ?>" alt="<?php echo htmlspecialchars(strip_tags($banner['title'])); ?> at <?php echo SITE_NAME; ?>" fetchpriority="high" decoding="async">
      </div>
    </div>
  <?php endif; ?>

</section>
