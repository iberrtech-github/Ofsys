<?php
/**
 * OFSYS Admin - add, edit, hide and delete placed students.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();

$db     = ofsy_db();
$action = $_GET['action'] ?? 'list';
$editId = (int) ($_GET['id'] ?? 0);
$self   = ADMIN_PATH . '/placements.php';

if (!$db) {
    $adminTitle = 'Placements';
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
        $stmt = $db->prepare('SELECT image FROM placements WHERE id = ?');
        $stmt->execute([$id]);
        if ($row = $stmt->fetch()) {
            $db->prepare('DELETE FROM placements WHERE id = ?')->execute([$id]);
            admin_delete_image($row['image']);
            admin_flash('The placement was deleted.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $db->prepare('UPDATE placements SET is_active = 1 - is_active WHERE id = ?');
        $stmt->execute([$id]);
        if ($stmt->rowCount()) {
            admin_flash('The placement visibility was updated.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'save') {
        $id = (int) ($_POST['id'] ?? 0);

        $existing = null;
        if ($id) {
            $stmt = $db->prepare('SELECT image FROM placements WHERE id = ?');
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            if (!$existing) {
                admin_flash('That placement could not be found.', 'error');
                header('Location: ' . $self);
                exit;
            }
        }

        $uploadError = '';
        $image = admin_store_image('image', 'placements', $uploadError);
        if (is_string($uploadError) && $uploadError !== '') {
            admin_flash($uploadError, 'error');
            header('Location: ' . $self . '?action=' . ($id ? 'edit&id=' . $id : 'new'));
            exit;
        }
        if (!$id && $image === null) {
            admin_flash('Please choose an image to upload.', 'error');
            header('Location: ' . $self . '?action=new');
            exit;
        }

        if ($id) {
            $db->prepare(
                'UPDATE placements
                    SET name = \'\', course = \'\', company = \'\', role = \'\',
                        package = \'\', quote = NULL, image = COALESCE(?, image)
                  WHERE id = ?'
            )->execute([$image, $id]);
            if ($image !== null) {
                admin_delete_image($existing['image']);
            }
            admin_flash('The placement was updated.');
        } else {
            $db->prepare(
                'INSERT INTO placements (name, image, is_active) VALUES (\'\', ?, 1)'
            )->execute([$image]);
            admin_flash(
                'The placement was added to ' . DB_NAME . '.placements (ID ' . $db->lastInsertId() . ').'
            );
        }

        header('Location: ' . $self);
        exit;
    }
}

/* ----------------------------------------------------------------- Load data */
$editing = null;
if ($action === 'edit' && $editId) {
    $stmt = $db->prepare('SELECT * FROM placements WHERE id = ?');
    $stmt->execute([$editId]);
    $editing = $stmt->fetch() ?: null;
    if (!$editing) {
        $action = 'list';
    }
}

$rows = $db->query('SELECT * FROM placements ORDER BY sort_order ASC, id DESC')->fetchAll();

$adminTitle = 'Placements';
include __DIR__ . '/includes/header.php';
?>

<?php if ($action === 'new' || $action === 'edit'): ?>

  <div class="ad-card">
    <h2 class="ad-card-title"><?php echo $editing ? 'Edit Placement' : 'Add Placement'; ?></h2>

    <form method="POST" action="<?php echo $self; ?>" enctype="multipart/form-data" class="ad-form">
      <?php echo admin_csrf_field(); ?>
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

      <label class="ad-label" for="image">Image <?php echo $editing ? '' : '*'; ?></label>
      <input class="ad-input" type="file" id="image" name="image" accept="image/*" data-preview="#imgPreview"
             <?php echo $editing ? '' : 'required'; ?>>
      <small class="ad-hint">JPG, PNG, GIF or WEBP, up to 5 MB.<?php echo $editing ? ' Leave empty to keep the current image.' : ''; ?></small>

      <div class="ad-thumb" <?php echo empty($editing['image']) ? 'style="display:none"' : ''; ?>>
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
      <h2 class="ad-card-title">Placements <span class="ad-count"><?php echo count($rows); ?></span></h2>
      <a class="ad-btn ad-btn-primary" href="<?php echo $self; ?>?action=new">
        <i class="fa-solid fa-plus"></i> Add Placement
      </a>
    </div>

    <?php if (!$rows): ?>
      <div class="ad-empty">
        <i class="fa-solid fa-user-graduate"></i>
        <p>No placements yet. Click <strong>Add Placement</strong> to add the first student.</p>
      </div>
    <?php else: ?>
      <div class="ad-table-wrap">
        <table class="ad-table">
          <thead>
            <tr>
              <th>Image</th>
              <th>Status</th>
              <th class="ad-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td>
                  <?php if (!empty($row['image'])): ?>
                    <img class="ad-avatar" src="<?php echo e(ofsy_image_src($row['image'])); ?>" alt="">
                  <?php else: ?>
                    <span class="ad-avatar ad-avatar-empty"><i class="fa-solid fa-image"></i></span>
                  <?php endif; ?>
                </td>
                <td>
                  <form method="POST" action="<?php echo $self; ?>" class="ad-inline">
                    <?php echo admin_csrf_field(); ?>
                    <input type="hidden" name="do" value="toggle">
                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                    <button type="submit" class="ad-status <?php echo $row['is_active'] ? 'is-on' : 'is-off'; ?>">
                      <?php echo $row['is_active'] ? 'Shown' : 'Hidden'; ?>
                    </button>
                  </form>
                </td>
                <td class="ad-right">
                  <a class="ad-icon-btn" href="<?php echo $self; ?>?action=edit&id=<?php echo (int) $row['id']; ?>" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form method="POST" action="<?php echo $self; ?>" class="ad-inline"
                        data-confirm="Delete this placement? This cannot be undone.">
                    <?php echo admin_csrf_field(); ?>
                    <input type="hidden" name="do" value="delete">
                    <input type="hidden" name="id" value="<?php echo (int) $row['id']; ?>">
                    <button type="submit" class="ad-icon-btn is-danger" title="Delete">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
