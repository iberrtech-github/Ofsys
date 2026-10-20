<?php
/**
 * OFSYS Education - Premium Master Header
 * High-Converting Creative Educational Architecture
 */
if (!defined('BASE_PATH')) {
    require_once __DIR__ . '/data.php';
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$pageTitle = isset($pageTitle) ? $pageTitle . ' | ' . SITE_NAME : SITE_NAME . ' | ' . SITE_TAGLINE;
$pageDesc = isset($pageDesc) ? $pageDesc : 'OFSYS Education provides practical, job-oriented professional courses in SAP, Data Analytics, Python Full Stack, and Office Administration with 100% placement support.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <script>
    /* Reveal elements start hidden so they do not flash in before
       animations.js takes them over. The timer is the safety net: if
       that script never runs, nothing stays invisible. */
    document.documentElement.classList.add('js');
    setTimeout(function () { document.documentElement.classList.remove('js'); }, 3000);
  </script>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  
  <title><?php echo htmlspecialchars($pageTitle); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($pageDesc); ?>">
  <meta name="keywords" content="OFSYS Education, SAP FICO, SAP MM, Data Analytics Gen AI, Data Science, Python Full Stack, Office Administration, Perinthalmanna, Calicut, Kochi, Kerala">
  <meta name="author" content="OFSYS Education">

  <!-- Open Graph / Social Meta -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($pageDesc); ?>">
  <meta property="og:image" content="<?php echo BASE_PATH; ?>/assets/images/logo.png">

  <?php if (!empty($preloadImage)): ?>
  <!-- The page's largest image, requested before the stylesheets resolve -->
  <link rel="preload" as="image" href="<?php echo BASE_PATH . '/' . ltrim($preloadImage, '/'); ?>" fetchpriority="high">
  <?php endif; ?>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?php echo BASE_PATH; ?>/assets/images/logo.png">

  <!-- Only two third-party origins remain, so the browser opens both sockets early -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

  <!-- Google Fonts: Poppins - only the weights the stylesheets use -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">

  <!-- Bootstrap 5.3.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome: loaded without blocking the first paint. Icons are purely
       decorative here, so a few frames without them beats a blank page. -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css"
        media="print" onload="this.media='all';this.onload=null">
  <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css"></noscript>

  <!-- Custom Creative Master Styles -->
  <?php
  // Stamp each stylesheet with its last-modified time so browsers pick up edits
  // immediately instead of serving a stale cached copy.
  $cssVersion = static function (string $file): string {
      $path = dirname(__DIR__) . '/assets/css/' . $file;
      return is_file($path) ? '?v=' . filemtime($path) : '';
  };
  ?>
  <?php foreach (array_merge(['style.css', 'site.css'], $pageStyles ?? []) as $styleFile): ?>
  <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/<?php echo $styleFile . $cssVersion($styleFile); ?>">
  <?php endforeach; ?>
</head>
<body<?php echo !empty($autoEnquiryPopup) ? ' data-auto-enquiry="1"' : ''; ?>>

  <!-- Main Sticky Header -->
  <header class="site-header">
    <div class="container">
      <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand me-4" href="<?php echo BASE_PATH; ?>/">
          <img src="<?php echo BASE_PATH; ?>/assets/images/logo.png" alt="OFSYS Education" width="400" height="154" fetchpriority="high">
        </a>

        <button class="nav-burger d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#ofsysNavbar" aria-controls="ofsysNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fa-solid fa-bars-staggered"></i>
        </button>

        <div class="collapse navbar-collapse" id="ofsysNavbar">
          <ul class="navbar-nav site-nav mx-auto">
            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'index' || $currentPage === '') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'about') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/about">About</a>
            </li>

            <!-- Courses Mega Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?php echo (strpos($currentPage, 'course') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/courses" id="coursesNavDrop">
                Courses <i class="fa-solid fa-angle-down ms-1 d-none d-lg-inline" style="font-size: 0.75rem;"></i>
              </a>
              <!-- Small screens: tapping the arrow opens the list, the label still links to /courses -->
              <button type="button" class="nav-drop-toggle d-lg-none" aria-expanded="false" aria-controls="coursesNavList" aria-label="Show courses">
                <i class="fa-solid fa-angle-down"></i>
              </button>
              <div class="dropdown-menu mega-courses" id="coursesNavList" aria-labelledby="coursesNavDrop">
                <div class="row g-1">
                  <?php foreach ($courses as $cSlug => $cItem): ?>
                    <div class="col-12">
                      <a class="mega-course-link" href="<?php echo BASE_PATH; ?>/courses/<?php echo $cSlug; ?>">
                        <i class="fa-solid fa-angle-right"></i>
                        <span class="t"><?php echo htmlspecialchars($cItem['short_title']); ?></span>
                      </a>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'placements') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/placements">Placements</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'gallery') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/gallery">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'blog' || $currentPage === 'blog-detail') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/blog">Blog</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?php echo ($currentPage === 'contact') ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/contact">Contact Us</a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-4 pb-3 pb-lg-0">
            <a href="tel:<?php echo HF_PHONE; ?>" class="header-call d-none d-xl-flex">
              <span class="call-icon"><i class="fa-solid fa-phone-volume"></i></span>
              <span><small>Admissions</small><strong><?php echo HF_PHONE_DISPLAY; ?></strong></span>
            </a>
            <button type="button" class="btn-arrow trigger-discount-modal">
              Enroll Now <span class="arrow-circle"><i class="fa-solid fa-arrow-right"></i></span>
            </button>
          </div>
        </div>
      </nav>
    </div>
  </header>
