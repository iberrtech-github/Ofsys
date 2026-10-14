<?php
/**
 * OFSYS Admin - dashboard with counts and quick links.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();

$adminTitle = 'Dashboard';
$db = ofsy_db();
$countError = false;

$count = function ($sql) use ($db, &$countError) {
    if (!$db) {
        return null;
    }
    try {
        return (int) $db->query($sql)->fetchColumn();
    } catch (PDOException $e) {
        error_log('OFSYS admin dashboard count failed: ' . $e->getMessage());
        $countError = true;
        return null;
    }
};

$placementCount = $count('SELECT COUNT(*) FROM placements WHERE is_active = 1');
$galleryCount   = $count('SELECT COUNT(*) FROM gallery_items WHERE is_active = 1');
$hiddenPlacementCount = $count('SELECT COUNT(*) FROM placements WHERE is_active = 0');
$hiddenGalleryCount   = $count('SELECT COUNT(*) FROM gallery_items WHERE is_active = 0');

$stats = [
    [
        'label' => 'Placements',
        'sub'   => 'shown on the website',
        'value' => $placementCount,
        'icon'  => 'fa-user-graduate',
        'link'  => 'placements.php',
    ],
    [
        'label' => 'Gallery Photos',
        'sub'   => 'shown on the website',
        'value' => $galleryCount,
        'icon'  => 'fa-images',
        'link'  => 'gallery.php',
    ],
    [
        'label' => 'Hidden Entries',
        'sub'   => 'saved but not published',
        'value' => $hiddenPlacementCount === null || $hiddenGalleryCount === null
            ? null
            : $hiddenPlacementCount + $hiddenGalleryCount,
        'icon'  => 'fa-eye-slash',
        'link'  => 'placements.php',
    ],
];

include __DIR__ . '/includes/header.php';
?>

<?php if (!$db): ?>
  <div class="ad-alert ad-alert-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    <span>The database is not reachable. Start MySQL in the XAMPP Control Panel, then reload this page.</span>
  </div>
<?php elseif ($countError): ?>
  <div class="ad-alert ad-alert-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    <span>Dashboard data could not be loaded. Import the database schema into <strong>ofsy_db</strong> and reload this page.</span>
  </div>
<?php endif; ?>

<div class="ad-stat-grid">
  <?php foreach ($stats as $stat): ?>
    <a class="ad-stat" href="<?php echo ADMIN_PATH . '/' . $stat['link']; ?>">
      <span class="ad-stat-icon"><i class="fa-solid <?php echo $stat['icon']; ?>"></i></span>
      <span class="ad-stat-value"><?php echo $stat['value'] === null ? '&mdash;' : (int) $stat['value']; ?></span>
      <span class="ad-stat-label"><?php echo e($stat['label']); ?></span>
      <span class="ad-stat-sub"><?php echo e($stat['sub']); ?></span>
    </a>
  <?php endforeach; ?>
</div>

<div class="ad-card">
  <h2 class="ad-card-title">Quick Actions</h2>
  <div class="ad-quick">
    <a class="ad-btn ad-btn-primary" href="<?php echo ADMIN_PATH; ?>/placements.php?action=new">
      <i class="fa-solid fa-plus"></i> Add a Placement
    </a>
    <a class="ad-btn ad-btn-primary" href="<?php echo ADMIN_PATH; ?>/gallery.php?action=new">
      <i class="fa-solid fa-plus"></i> Add a Gallery Photo
    </a>
    <a class="ad-btn ad-btn-line" href="<?php echo BASE_PATH; ?>/placements" target="_blank" rel="noopener">
      <i class="fa-solid fa-arrow-up-right-from-square"></i> View Placements Page
    </a>
    <a class="ad-btn ad-btn-line" href="<?php echo BASE_PATH; ?>/gallery" target="_blank" rel="noopener">
      <i class="fa-solid fa-arrow-up-right-from-square"></i> View Gallery Page
    </a>
  </div>
</div>

<div class="ad-card">
  <h2 class="ad-card-title">How it works</h2>
  <ul class="ad-help">
    <li><strong>Placements</strong> and <strong>Gallery</strong> entries you add here appear on the website straight away.</li>
    <li>Set an entry to <strong>Hidden</strong> to take it off the website without deleting it.</li>
    <li><strong>Order</strong> decides the position: a smaller number comes first.</li>
    <li>Change the login password from the <strong>Password</strong> page.</li>
  </ul>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
