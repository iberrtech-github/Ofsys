<?php
/**
 * OFSYS Admin - add, edit, hide and delete gallery photos.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();

$db     = ofsy_db();
$action = $_GET['action'] ?? 'list';
$editId = (int) ($_GET['id'] ?? 0);
$self   = ADMIN_PATH . '/gallery.php';

if (!$db) {
    $adminTitle = 'Gallery';
    include __DIR__ . '/includes/header.php';
    echo '<div class="ad-alert ad-alert-error"><i class="fa-solid fa-circle-exclamation"></i>'
       . '<span>The database is not reachable. Start MySQL in the XAMPP Control Panel, then reload this page.</span></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

/* ---------------------------------------------------------------- Save/delete */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $posted = $_POST['do'] ?? '';

    if ($posted === 'delete') {
        $id   = (int) ($_POST['id'] ?? 0);
        $stmt = $db->prepare('SELECT image FROM gallery_items WHERE id = ?');
        $stmt->execute([$id]);
        if ($row = $stmt->fetch()) {
            admin_delete_image($row['image']);
            $db->prepare('DELETE FROM gallery_items WHERE id = ?')->execute([$id]);
            admin_flash('The photo was deleted.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $db->prepare('UPDATE gallery_items SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        admin_flash('The photo visibility was updated.');
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'save') {
        $id = (int) ($_POST['id'] ?? 0);

        $uploadError = '';
        $image = admin_store_image('image', 'gallery', $uploadError);
        if (is_string($uploadError) && $uploadError !== '') {
            admin_flash($uploadError, 'error');
            header('Location: ' . $self . '?action=' . ($id ? 'edit&id=' . $id : 'new'));
            exit;
        }

        if (!$id && $image === null) {
            admin_flash('Please choose a photo to upload.', 'error');
            header('Location: ' . $self . '?action=new');
            exit;
        }

        if ($id) {
            $old = $db->prepare('SELECT image FROM gallery_items WHERE id = ?');
            $old->execute([$id]);
            $oldImage = (string) ($old->fetchColumn() ?: '');

            if ($image !== null) {
                admin_delete_image($oldImage);
                $db->prepare('UPDATE gallery_items SET image=? WHERE id=?')->execute([$image, $id]);
            }

            admin_flash('The photo was updated.');
        } else {
            $db->prepare(
                'INSERT INTO gallery_items (image) VALUES (?)'
            )->execute([$image]);
            admin_flash(
                'The photo was added to ' . DB_NAME . '.gallery_items (ID ' . $db->lastInsertId() . ').'
            );
        }

        header('Location: ' . $self);
        exit;
    }
}

/* ----------------------------------------------------------------- Load data */
$editing = null;
if ($action === 'edit' && $editId) {
    $stmt = $db->prepare('SELECT * FROM gallery_items WHERE id = ?');
    $stmt->execute([$editId]);
    $editing = $stmt->fetch() ?: null;
    if (!$editing) {
        $action = 'list';
    }
}

$rows = $db->query(
    'SELECT * FROM gallery_items ORDER BY sort_order ASC, id DESC'
)->fetchAll();

$adminTitle = 'Gallery';
include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'new' || $action === 'edit'): ?>

  <div class="ad-card">
    <h2 class="ad-card-title"><?php echo $editing ? 'Edit Gallery Image' : 'Add Gallery Image'; ?></h2>

    <form method="POST" action="<?php echo $self; ?>" enctype="multipart/form-data" class="ad-form">
      <?php echo admin_csrf_field(); ?>
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

      <label class="ad-label" for="image">Image <?php echo $editing ? '' : '*'; ?></label>
      <input class="ad-input" type="file" id="image" name="image" accept="image/*" data-preview="#imgPreview"
             <?php echo $editing ? '' : 'required'; ?>>
      <small class="ad-hint">JPG, PNG, GIF or WEBP, up to 5 MB.<?php echo $editing ? ' Leave empty to keep the current image.' : ''; ?></small>

      <div class="ad-thumb ad-thumb-wide" <?php echo empty($editing['image']) ? 'style="display:none"' : ''; ?>>
        <img id="imgPreview" src="<?php echo e(ofsy_image_src($editing['image'] ?? '')); ?>" alt="">
      </div>

      <div class="ad-form-actions">
        <button type="submit" class="ad-btn ad-btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> <?php echo $editing ? 'Update' : 'Save'; ?>
        </button>
        <a class="ad-btn ad-btn-line" href="<?php echo $self; ?>">Cancel</a>
      </div>
    </form>
  </div>

<?php else: ?>

  <div class="ad-card">
    <div class="ad-card-head">
      <h2 class="ad-card-title">Gallery Photos <span class="ad-count"><?php echo count($rows); ?></span></h2>
      <a class="ad-btn ad-btn-primary" href="<?php echo $self; ?>?action=new">
        <i class="fa-solid fa-plus"></i> Add Photo
      </a>
    </div>

    <?php if (!$rows): ?>
      <div class="ad-empty">
        <i class="fa-solid fa-images"></i>
        <p>No photos yet. Click <strong>Add Photo</strong> to upload your first image.</p>
      </div>
    <?php else: ?>
      <div class="ad-photo-grid">
        <?php foreach ($rows as $row): ?>
          <div class="ad-photo <?php echo $row['is_active'] ? '' : 'is-hidden'; ?>">
            <div class="ad-photo-img">
              <img src="<?php echo e(ofsy_image_src($row['image'])); ?>" alt="Gallery image">
              <?php if (!$row['is_active']): ?>
                <span class="ad-photo-flag"><i class="fa-solid fa-eye-slash"></i> Hidden</span>
              <?php endif; ?>
            </div>
            <div class="ad-photo-foot">
              <a class="ad-icon-btn" href="<?php echo $self; ?>?action=edit&id=<?php echo (int) $row['id']; ?>" title="Edit">
                <i class="fa-solid fa-pen"></i>
              </a>
              <form method="POST" action="<?php echo $self; ?>" class="ad-inline">
                <?php echo admin_csrf_field(); ?>
                <input type="hidden" name="do" value="toggle">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                <button type="submit" class="ad-icon-btn" title="<?php echo $row['is_active'] ? 'Hide' : 'Show'; ?>">
                  <i class="fa-solid <?php echo $row['is_active'] ? 'fa-eye' : 'fa-eye-slash'; ?>"></i>
                </button>
              </form>
              <form method="POST" action="<?php echo $self; ?>" class="ad-inline"
                    data-confirm="Delete this photo? This cannot be undone.">
                <?php echo admin_csrf_field(); ?>
                <input type="hidden" name="do" value="delete">
                <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                <button type="submit" class="ad-icon-btn is-danger" title="Delete">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
