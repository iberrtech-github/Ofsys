<?php
/**
 * OFSYS Admin - Gallery > Photos: upload (one or many), edit the details,
 * reorder, hide and delete the photos shown on the website gallery.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/gallery-schema.php';
admin_require_login();

$db     = ofsy_db();
$action = $_GET['action'] ?? 'list';
$editId = (int) ($_GET['id'] ?? 0);
$self   = ADMIN_PATH . '/gallery.php';

if (!$db) {
    $adminTitle = 'Gallery Photos';
    include __DIR__ . '/includes/header.php';
    echo '<div class="ad-alert ad-alert-error"><i class="fa-solid fa-circle-exclamation"></i>'
       . '<span>The database is not reachable. Start MySQL in the XAMPP Control Panel, then reload this page.</span></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$missingSchema = admin_gallery_schema_missing($db);

/* ---------------------------------------------------------------- Save/delete */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $posted = $_POST['do'] ?? '';

    if ($posted === 'upgrade-schema') {
        $upgradeError = '';
        if (admin_gallery_schema_upgrade($db, $upgradeError)) {
            admin_flash('The database was upgraded. The gallery is ready to use.');
        } else {
            admin_flash($upgradeError, 'error');
        }
        header('Location: ' . $self);
        exit;
    }

    // Everything below writes the new columns, so it waits for the upgrade.
    if ($missingSchema) {
        admin_flash('Upgrade the database first, then try again.', 'error');
        header('Location: ' . $self);
        exit;
    }

    // On the list page the photos sit in one form, so the button that was
    // pressed is what says which row to act on.
    if (isset($_POST['delete_id'])) {
        $posted = 'delete';
        $_POST['id'] = $_POST['delete_id'];
    } elseif (isset($_POST['toggle_id'])) {
        $posted = 'toggle';
        $_POST['id'] = $_POST['toggle_id'];
    }

    if ($posted === 'delete') {
        $id   = (int) ($_POST['id'] ?? 0);
        $stmt = $db->prepare('SELECT image, thumbnail FROM gallery_items WHERE id = ?');
        $stmt->execute([$id]);
        if ($row = $stmt->fetch()) {
            $db->prepare('DELETE FROM gallery_items WHERE id = ?')->execute([$id]);
            admin_delete_image($row['image']);
            admin_delete_image($row['thumbnail']);
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

    // The order boxes under every card on the list page, saved in one go.
    if ($posted === 'reorder') {
        $orders = $_POST['order'] ?? [];
        if (is_array($orders)) {
            $stmt = $db->prepare('UPDATE gallery_items SET sort_order = ? WHERE id = ?');
            foreach ($orders as $id => $value) {
                $stmt->execute([(int) $value, (int) $id]);
            }
            admin_flash('The display order was saved.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'save') {
        $id         = (int) ($_POST['id'] ?? 0);
        $title      = trim((string) ($_POST['title'] ?? ''));
        $altText    = trim((string) ($_POST['alt_text'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $sortOrder  = (int) ($_POST['sort_order'] ?? 0);
        $isActive   = empty($_POST['is_active']) ? 0 : 1;
        $category   = $categoryId > 0 ? $categoryId : null;

        // Keep the stored values inside the column widths.
        $title   = mb_substr($title, 0, 180);
        $altText = mb_substr($altText, 0, 255);

        $backTo = $self . '?action=' . ($id ? 'edit&id=' . $id : 'new');

        /* ------------------------------------------------- Editing one photo */
        if ($id) {
            $stmt = $db->prepare('SELECT * FROM gallery_items WHERE id = ?');
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            if (!$existing) {
                admin_flash('That photo could not be found.', 'error');
                header('Location: ' . $self);
                exit;
            }

            $uploadError = '';
            $image = admin_store_image('image', 'gallery', $uploadError);
            if (is_string($uploadError) && $uploadError !== '') {
                admin_flash($uploadError, 'error');
                header('Location: ' . $backTo);
                exit;
            }

            $thumbError = '';
            $thumbnail = admin_store_image('thumbnail', 'gallery', $thumbError);
            if (is_string($thumbError) && $thumbError !== '') {
                admin_flash('Thumbnail: ' . $thumbError, 'error');
                header('Location: ' . $backTo);
                exit;
            }

            // A new full image gets a fresh auto thumbnail, unless the admin
            // uploaded one of their own in the same save.
            if ($image !== null && $thumbnail === null) {
                $thumbnail = admin_make_thumbnail($image);
            }

            $db->prepare(
                'UPDATE gallery_items
                    SET title = ?, alt_text = ?, category_id = ?, sort_order = ?, is_active = ?,
                        image = COALESCE(?, image), thumbnail = COALESCE(?, thumbnail)
                  WHERE id = ?'
            )->execute([$title, $altText, $category, $sortOrder, $isActive, $image, $thumbnail, $id]);

            // Only drop the old files once the row no longer points at them.
            if ($image !== null) {
                admin_delete_image($existing['image']);
            }
            if ($thumbnail !== null) {
                admin_delete_image($existing['thumbnail']);
            }

            admin_flash('The photo was updated.');
            header('Location: ' . $self);
            exit;
        }

        /* ------------------------------------- Adding one or several photos */
        $uploadErrors = [];
        $images = admin_store_images('images', 'gallery', $uploadErrors);

        if (!$images) {
            admin_flash($uploadErrors
                ? implode(' ', $uploadErrors)
                : 'Please choose at least one photo to upload.', 'error');
            header('Location: ' . $self . '?action=new');
            exit;
        }

        $insert = $db->prepare(
            'INSERT INTO gallery_items (title, alt_text, category_id, image, thumbnail, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $added = 0;
        foreach ($images as $index => $path) {
            $insert->execute([
                $title,
                $altText,
                $category,
                $path,
                admin_make_thumbnail($path),
                // Several photos in one upload keep the order they were picked.
                $sortOrder + $index,
                $isActive,
            ]);
            $added++;
        }

        $message = $added === 1
            ? 'The photo was added to ' . DB_NAME . '.gallery_items (ID ' . $db->lastInsertId() . ').'
            : $added . ' photos were added to ' . DB_NAME . '.gallery_items.';
        if ($uploadErrors) {
            $message .= ' Skipped: ' . implode(' ', $uploadErrors);
        }
        admin_flash($message, $uploadErrors ? 'error' : 'success');

        header('Location: ' . $self);
        exit;
    }
}

/* ----------------------------------------------------------------- Load data */
$categories = [];
$rows       = [];
$editing    = null;

if ($missingSchema) {
    $action = 'list';
} else {
    $categories = ofsy_get_gallery_categories_all();

    if ($action === 'edit' && $editId) {
        $stmt = $db->prepare('SELECT * FROM gallery_items WHERE id = ?');
        $stmt->execute([$editId]);
        $editing = $stmt->fetch() ?: null;
        if (!$editing) {
            $action = 'list';
        }
    }

    $rows = $db->query(
        'SELECT g.*, c.name AS category_name
           FROM gallery_items g
      LEFT JOIN gallery_categories c ON c.id = g.category_id
          ORDER BY g.sort_order ASC, g.id DESC'
    )->fetchAll();
}

$adminTitle = 'Gallery Photos';
include __DIR__ . '/includes/header.php';
?>

<?php if ($missingSchema): ?>

  <?php echo admin_gallery_schema_notice($missingSchema, $self); ?>

<?php elseif ($action === 'new' || $action === 'edit'): ?>

  <div class="ad-card">
    <h2 class="ad-card-title"><?php echo $editing ? 'Edit Photo' : 'Add Photos'; ?></h2>

    <form method="POST" action="<?php echo $self; ?>" enctype="multipart/form-data" class="ad-form">
      <?php echo admin_csrf_field(); ?>
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

      <?php if ($editing): ?>

        <label class="ad-label" for="image">Replace image</label>
        <input class="ad-input" type="file" id="image" name="image"
               accept="image/jpeg,image/png,image/gif,image/webp" data-preview="#imgPreview">
        <small class="ad-hint">JPG, PNG, GIF or WEBP, up to 5 MB. Leave empty to keep the current image.</small>

        <div class="ad-thumb ad-thumb-wide" <?php echo empty($editing['image']) ? 'style="display:none"' : ''; ?>>
          <img id="imgPreview" src="<?php echo e(ofsy_image_src($editing['image'] ?? '')); ?>" alt="">
        </div>

      <?php else: ?>

        <label class="ad-label" for="images">Photos *</label>
        <input class="ad-input" type="file" id="images" name="images[]"
               accept="image/jpeg,image/png,image/gif,image/webp" multiple required>
        <small class="ad-hint">
          JPG, PNG, GIF or WEBP, up to 5 MB each. Pick several files at once to upload them together
          (up to <?php echo (int) (ini_get('max_file_uploads') ?: 20); ?> per upload).
          A thumbnail is made for every photo automatically.
        </small>

      <?php endif; ?>

      <div class="ad-form-grid">
        <div>
          <label class="ad-label" for="title">Title</label>
          <input class="ad-input" type="text" id="title" name="title" maxlength="180"
                 value="<?php echo e($editing['title'] ?? ''); ?>"
                 placeholder="Shown as the photo caption">
          <?php if (!$editing): ?>
            <small class="ad-hint">Used for every photo in this upload. Leave it empty and add titles later from Edit.</small>
          <?php endif; ?>
        </div>
        <div>
          <label class="ad-label" for="alt_text">Alt text</label>
          <input class="ad-input" type="text" id="alt_text" name="alt_text" maxlength="255"
                 value="<?php echo e($editing['alt_text'] ?? ''); ?>"
                 placeholder="Describes the photo for screen readers">
          <small class="ad-hint">Falls back to the title when left empty.</small>
        </div>
        <div>
          <label class="ad-label" for="category_id">Category</label>
          <select class="ad-input" id="category_id" name="category_id">
            <option value="0">No category</option>
            <?php foreach ($categories as $category): ?>
              <option value="<?php echo (int) $category['id']; ?>"
                <?php echo (int) ($editing['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : ''; ?>>
                <?php echo e($category['name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label class="ad-label" for="sort_order">Display order</label>
          <input class="ad-input" type="number" id="sort_order" name="sort_order" step="1"
                 value="<?php echo (int) ($editing['sort_order'] ?? 0); ?>">
          <small class="ad-hint">A smaller number comes first.</small>
        </div>
      </div>

      <?php if ($editing): ?>
        <label class="ad-label" for="thumbnail">Custom thumbnail</label>
        <input class="ad-input" type="file" id="thumbnail" name="thumbnail"
               accept="image/jpeg,image/png,image/gif,image/webp" data-preview="#thumbPreview">
        <small class="ad-hint">Optional. Leave empty to keep the thumbnail made from the photo itself.</small>
        <div class="ad-thumb" <?php echo empty($editing['thumbnail']) ? 'style="display:none"' : ''; ?>>
          <img id="thumbPreview" src="<?php echo e(ofsy_image_src($editing['thumbnail'] ?? '')); ?>" alt="">
        </div>
      <?php endif; ?>

      <label class="ad-check">
        <input type="checkbox" name="is_active" value="1"
          <?php echo (!$editing || (int) $editing['is_active'] === 1) ? 'checked' : ''; ?>>
        <span>Show on the website</span>
      </label>

      <div class="ad-form-actions">
        <button type="submit" class="ad-btn ad-btn-primary">
          <i class="fa-solid fa-floppy-disk"></i> <?php echo $editing ? 'Update' : 'Upload'; ?>
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
        <i class="fa-solid fa-plus"></i> Add Photos
      </a>
    </div>

    <?php if (!$rows): ?>
      <div class="ad-empty">
        <i class="fa-solid fa-images"></i>
        <p>No photos yet. Click <strong>Add Photos</strong> to upload your first images.</p>
      </div>
    <?php else: ?>
      <!-- One form around the grid: the order boxes save together, while the
           hide and delete buttons carry the id of their own photo. -->
      <form method="POST" action="<?php echo $self; ?>">
        <?php echo admin_csrf_field(); ?>
        <input type="hidden" name="do" value="reorder">

        <div class="ad-photo-grid">
          <?php foreach ($rows as $row): ?>
            <div class="ad-photo <?php echo $row['is_active'] ? '' : 'is-hidden'; ?>">
              <div class="ad-photo-img">
                <img src="<?php echo e(ofsy_image_src($row['thumbnail'] ?: $row['image'])); ?>"
                     alt="<?php echo e($row['alt_text'] ?: $row['title']); ?>" loading="lazy">
                <?php if (!$row['is_active']): ?>
                  <span class="ad-photo-flag"><i class="fa-solid fa-eye-slash"></i> Hidden</span>
                <?php endif; ?>
              </div>
              <div class="ad-photo-body">
                <strong><?php echo $row['title'] !== '' ? e($row['title']) : '<em>Untitled</em>'; ?></strong>
                <small><?php echo e($row['category_name'] ?: 'No category'); ?></small>
              </div>
              <div class="ad-photo-foot">
                <input class="ad-order-input" type="number" step="1"
                       name="order[<?php echo (int) $row['id']; ?>]"
                       value="<?php echo (int) $row['sort_order']; ?>"
                       aria-label="Display order" title="Display order">
                <a class="ad-icon-btn" href="<?php echo $self; ?>?action=edit&id=<?php echo (int) $row['id']; ?>" title="Edit">
                  <i class="fa-solid fa-pen"></i>
                </a>
                <button type="submit" class="ad-icon-btn" name="toggle_id" value="<?php echo (int) $row['id']; ?>"
                        title="<?php echo $row['is_active'] ? 'Hide' : 'Show'; ?>">
                  <i class="fa-solid <?php echo $row['is_active'] ? 'fa-eye' : 'fa-eye-slash'; ?>"></i>
                </button>
                <button type="submit" class="ad-icon-btn is-danger" name="delete_id" value="<?php echo (int) $row['id']; ?>"
                        title="Delete" data-confirm="Delete this photo? This cannot be undone.">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="ad-order-bar">
          <small>Change the number under a photo to move it, then save. A smaller number comes first.</small>
          <button type="submit" class="ad-btn ad-btn-line">
            <i class="fa-solid fa-arrow-down-1-9"></i> Save order
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
