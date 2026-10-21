<?php
/**
 * OFSYS Admin - dashboard with counts and quick links.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();
require_once __DIR__ . '/includes/leads-store.php';

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
$videoCount     = $count('SELECT COUNT(*) FROM gallery_videos WHERE is_active = 1');
$hiddenPlacementCount = $count('SELECT COUNT(*) FROM placements WHERE is_active = 0');
$hiddenGalleryCount   = $count('SELECT COUNT(*) FROM gallery_items WHERE is_active = 0');
$hiddenVideoCount     = $count('SELECT COUNT(*) FROM gallery_videos WHERE is_active = 0');

// Leads live in leads.json, not the database, so they are counted separately.
$leads        = leads_all();
$leadLabels   = leads_branch_labels();
$leadCounts   = leads_branch_counts($leads);
$leadsToday   = 0;
$today        = date('Y-m-d');
foreach ($leads as $lead) {
    if (substr($lead['timestamp'], 0, 10) === $today) {
        $leadsToday++;
    }
}

$stats = [
    [
        'label' => 'Leads',
        'sub'   => $leadsToday > 0 ? $leadsToday . ' came in today' : 'from the website forms',
        'value' => count($leads),
        'icon'  => 'fa-address-book',
        'link'  => 'leads.php',
    ],
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
        'label' => 'Gallery Videos',
        'sub'   => 'shown on the website',
        'value' => $videoCount,
        'icon'  => 'fa-video',
        'link'  => 'gallery-videos.php',
    ],
    [
        'label' => 'Hidden Entries',
        'sub'   => 'saved but not published',
        'value' => $hiddenPlacementCount === null || $hiddenGalleryCount === null || $hiddenVideoCount === null
            ? null
            : $hiddenPlacementCount + $hiddenGalleryCount + $hiddenVideoCount,
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
  <div class="ad-card-head">
    <h2 class="ad-card-title">Leads by Branch</h2>
    <a class="ad-btn ad-btn-line" href="<?php echo ADMIN_PATH; ?>/leads.php">
      <i class="fa-solid fa-list"></i> Open Leads
    </a>
  </div>
  <p class="ad-card-note">Which campus each enquiry asked for. Click a branch to see only its leads.</p>
  <div class="ad-branch-grid">
    <?php foreach ($leadLabels as $bKey => $bLabel): ?>
      <?php if ($bKey === 'other' && (int) $leadCounts[$bKey] === 0) { continue; } ?>
      <a class="ad-branch-card" href="<?php echo ADMIN_PATH; ?>/leads.php?branch=<?php echo e($bKey); ?>">
        <span class="ad-branch-count"><?php echo (int) $leadCounts[$bKey]; ?></span>
        <span class="ad-branch-name"><?php echo e(str_replace('OFSYS - ', '', $bLabel)); ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<div class="ad-card">
  <h2 class="ad-card-title">Quick Actions</h2>
  <div class="ad-quick">
    <a class="ad-btn ad-btn-primary" href="<?php echo ADMIN_PATH; ?>/placements.php?action=new">
      <i class="fa-solid fa-plus"></i> Add a Placement
    </a>
    <a class="ad-btn ad-btn-primary" href="<?php echo ADMIN_PATH; ?>/gallery.php?action=new">
      <i class="fa-solid fa-plus"></i> Add Gallery Photos
    </a>
    <a class="ad-btn ad-btn-primary" href="<?php echo ADMIN_PATH; ?>/gallery-videos.php?action=new">
      <i class="fa-solid fa-plus"></i> Add a Gallery Video
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
    <li><strong>Leads</strong> collects every enquiry form on the website, with the branch the student chose; filter by branch, date or course and download the list as CSV.</li>
    <li><strong>Placements</strong> and <strong>Gallery</strong> entries you add here appear on the website straight away.</li>
    <li><strong>Gallery &rsaquo; Photos</strong> takes several images in one upload; <strong>Gallery &rsaquo; Videos</strong> takes a YouTube or Vimeo link.</li>
    <li>Set an entry to <strong>Hidden</strong> to take it off the website without deleting it.</li>
    <li><strong>Order</strong> decides the position: a smaller number comes first.</li>
    <li>Change the login password from the <strong>Password</strong> page.</li>
  </ul>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
