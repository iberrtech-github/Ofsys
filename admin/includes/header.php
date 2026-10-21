<?php
/**
 * OFSYS Admin - page chrome (sidebar + topbar). Pages set $adminTitle first.
 */
admin_require_login();

$adminTitle = $adminTitle ?? 'Dashboard';
$current    = basename($_SERVER['PHP_SELF'], '.php');
$flash      = admin_take_flash();

// A group ('children') draws a heading with its pages indented under it; a
// plain entry stays the single link it has always been.
$navItems = [
    'index'      => ['label' => 'Dashboard',  'icon' => 'fa-gauge-high'],
    'leads'      => ['label' => 'Leads',      'icon' => 'fa-address-book'],
    'placements' => ['label' => 'Placements', 'icon' => 'fa-user-graduate'],
    'gallery'    => [
        'label'    => 'Gallery',
        'icon'     => 'fa-images',
        'children' => [
            'gallery'        => ['label' => 'Photos', 'icon' => 'fa-image'],
            'gallery-videos' => ['label' => 'Videos', 'icon' => 'fa-video'],
        ],
    ],
    'password'   => ['label' => 'Password',   'icon' => 'fa-key'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?php echo e($adminTitle); ?> | OFSYS Admin</title>
  <link rel="icon" type="image/png" href="<?php echo BASE_PATH; ?>/assets/images/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/admin.css">
</head>
<body>
<div class="ad-shell">

  <aside class="ad-sidebar" id="adSidebar">
    <a class="ad-brand" href="<?php echo BASE_PATH; ?>/" target="_blank" rel="noopener">
      <img src="<?php echo BASE_PATH; ?>/assets/images/logo.png" alt="OFSYS Education">
    </a>

    <nav class="ad-nav">
      <?php foreach ($navItems as $file => $item): ?>
        <?php if (!empty($item['children'])): ?>
          <div class="ad-nav-group <?php echo isset($item['children'][$current]) ? 'is-open' : ''; ?>">
            <span class="ad-nav-head">
              <i class="fa-solid <?php echo $item['icon']; ?>"></i>
              <span><?php echo e($item['label']); ?></span>
            </span>
            <div class="ad-nav-sub">
              <?php foreach ($item['children'] as $childFile => $child): ?>
                <a href="<?php echo ADMIN_PATH . '/' . $childFile; ?>.php"
                   class="<?php echo $current === $childFile ? 'is-active' : ''; ?>">
                  <i class="fa-solid <?php echo $child['icon']; ?>"></i>
                  <span><?php echo e($child['label']); ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php else: ?>
          <a href="<?php echo ADMIN_PATH . '/' . $file; ?>.php"
             class="<?php echo $current === $file ? 'is-active' : ''; ?>">
            <i class="fa-solid <?php echo $item['icon']; ?>"></i>
            <span><?php echo e($item['label']); ?></span>
          </a>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <div class="ad-nav ad-nav-foot">
      <a href="<?php echo BASE_PATH; ?>/" target="_blank" rel="noopener">
        <i class="fa-solid fa-arrow-up-right-from-square"></i><span>View Website</span>
      </a>
      <a href="<?php echo ADMIN_PATH; ?>/logout.php" class="is-danger">
        <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
      </a>
    </div>
  </aside>

  <div class="ad-main">
    <header class="ad-topbar">
      <button type="button" class="ad-burger" id="adBurger" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>
      <h1><?php echo e($adminTitle); ?></h1>
      <span class="ad-user"><i class="fa-solid fa-user-shield"></i> <?php echo e($_SESSION['admin_user'] ?? 'admin'); ?></span>
    </header>

    <main class="ad-content">
      <?php if ($flash): ?>
        <div class="ad-alert ad-alert-<?php echo e($flash['type']); ?>">
          <i class="fa-solid <?php echo $flash['type'] === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
          <span><?php echo e($flash['message']); ?></span>
        </div>
      <?php endif; ?>
