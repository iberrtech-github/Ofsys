<?php
/**
 * OFSYS Admin - Gallery > Videos: add YouTube or Vimeo links, edit them,
 * reorder, hide and delete the videos shown in the website video gallery.
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/gallery-schema.php';
admin_require_login();

$db     = ofsy_db();
$action = $_GET['action'] ?? 'list';
$editId = (int) ($_GET['id'] ?? 0);
$self   = ADMIN_PATH . '/gallery-videos.php';

if (!$db) {
    $adminTitle = 'Gallery Videos';
    include __DIR__ . '/includes/header.php';
    echo '<div class="ad-alert ad-alert-error"><i class="fa-solid fa-circle-exclamation"></i>'
       . '<span>The database is not reachable. Start MySQL in the XAMPP Control Panel, then reload this page.</span></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$missingSchema = admin_gallery_schema_missing($db);

/**
 * Asks Vimeo for the poster image of a video, so a Vimeo link gets a
 * thumbnail without the admin having to upload one. Returns '' on any
 * problem: the card then shows a plain play panel instead.
 */
function admin_fetch_vimeo_thumb($videoId)
{
    $videoId = preg_replace('/\D+/', '', (string) $videoId);
    if ($videoId === '' || !function_exists('curl_init')) {
        return '';
    }

    $ch = curl_init('https://vimeo.com/api/oembed.json?url='
        . rawurlencode('https://vimeo.com/' . $videoId));
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_USERAGENT      => 'OFSYS-Admin/1.0',
    ]);
    $body = curl_exec($ch);
    $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);

    if ($code !== 200 || !is_string($body)) {
        return '';
    }

    $data  = json_decode($body, true);
    $thumb = is_array($data) ? trim((string) ($data['thumbnail_url'] ?? '')) : '';

    // Only a plain https image URL is stored, never whatever came back.
    if ($thumb === '' || !preg_match('#^https://[^\s"\'<>]+$#i', $thumb)) {
        return '';
    }

    return mb_substr($thumb, 0, 500);
}

/* ---------------------------------------------------------------- Save/delete */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();
    $posted = $_POST['do'] ?? '';

    if ($posted === 'upgrade-schema') {
        $upgradeError = '';
        if (admin_gallery_schema_upgrade($db, $upgradeError)) {
            admin_flash('The database was upgraded. The video gallery is ready to use.');
        } else {
            admin_flash($upgradeError, 'error');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($missingSchema) {
        admin_flash('Upgrade the database first, then try again.', 'error');
        header('Location: ' . $self);
        exit;
    }

    // On the list page every card lives in one form, so the pressed button
    // is what says which row to act on.
    if (isset($_POST['delete_id'])) {
        $posted = 'delete';
        $_POST['id'] = $_POST['delete_id'];
    } elseif (isset($_POST['toggle_id'])) {
        $posted = 'toggle';
        $_POST['id'] = $_POST['toggle_id'];
    }

    if ($posted === 'delete') {
        $id   = (int) ($_POST['id'] ?? 0);
        $stmt = $db->prepare('SELECT thumbnail FROM gallery_videos WHERE id = ?');
        $stmt->execute([$id]);
        if ($row = $stmt->fetch()) {
            $db->prepare('DELETE FROM gallery_videos WHERE id = ?')->execute([$id]);
            // Leaves a fetched YouTube/Vimeo URL alone, deletes an upload.
            admin_delete_image($row['thumbnail']);
            admin_flash('The video was deleted.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'toggle') {
        $id = (int) ($_POST['id'] ?? 0);
        $db->prepare('UPDATE gallery_videos SET is_active = 1 - is_active WHERE id = ?')->execute([$id]);
        admin_flash('The video visibility was updated.');
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'reorder') {
        $orders = $_POST['order'] ?? [];
        if (is_array($orders)) {
            $stmt = $db->prepare('UPDATE gallery_videos SET sort_order = ? WHERE id = ?');
            foreach ($orders as $id => $value) {
                $stmt->execute([(int) $value, (int) $id]);
            }
            admin_flash('The display order was saved.');
        }
        header('Location: ' . $self);
        exit;
    }

    if ($posted === 'save') {
        $id        = (int) ($_POST['id'] ?? 0);
        $title     = mb_substr(trim((string) ($_POST['title'] ?? '')), 0, 180);
        $url       = trim((string) ($_POST['video_url'] ?? ''));
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive  = empty($_POST['is_active']) ? 0 : 1;
        $backTo    = $self . '?action=' . ($id ? 'edit&id=' . $id : 'new');

        $parsed = ofsy_parse_video_url($url);
        if (!$parsed) {
            admin_flash('That does not look like a YouTube or Vimeo link. '
                . 'Paste the full video address, for example https://www.youtube.com/watch?v=XXXXXXXXXXX', 'error');
            header('Location: ' . $backTo);
            exit;
        }

        $existing = null;
        if ($id) {
            $stmt = $db->prepare('SELECT * FROM gallery_videos WHERE id = ?');
            $stmt->execute([$id]);
            $existing = $stmt->fetch();
            if (!$existing) {
                admin_flash('That video could not be found.', 'error');
                header('Location: ' . $self);
                exit;
            }
        }

        $thumbError = '';
        $thumbnail  = admin_store_image('thumbnail', 'gallery', $thumbError);
        if (is_string($thumbError) && $thumbError !== '') {
            admin_flash('Thumbnail: ' . $thumbError, 'error');
            header('Location: ' . $backTo);
            exit;
        }

        // YouTube stills are built from the id when the page renders, so only
        // Vimeo needs a poster looked up - once, here, not on every visit.
        $autoThumb = null;
        if ($thumbnail === null && $parsed['provider'] === 'vimeo') {
            $isNewVideo = !$existing || (string) $existing['video_id'] !== $parsed['id'];
            if ($isNewVideo || empty($existing['thumbnail'])) {
                $fetched = admin_fetch_vimeo_thumb($parsed['id']);
                if ($fetched !== '') {
                    $autoThumb = $fetched;
                }
            }
        }

        if ($id) {
            $db->prepare(
                'UPDATE gallery_videos
                    SET title = ?, video_url = ?, provider = ?, video_id = ?,
                        sort_order = ?, is_active = ?,
                        thumbnail = COALESCE(?, ?, thumbnail)
                  WHERE id = ?'
            )->execute([
                $title, mb_substr($url, 0, 500), $parsed['provider'], $parsed['id'],
                $sortOrder, $isActive, $thumbnail, $autoThumb, $id,
            ]);

            if ($thumbnail !== null || $autoThumb !== null) {
                admin_delete_image($existing['thumbnail']);
            }

            admin_flash('The video was updated.');
        } else {
            $db->prepare(
                'INSERT INTO gallery_videos (title, video_url, provider, video_id, thumbnail, sort_order, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, ?)'
            )->execute([
                $title, mb_substr($url, 0, 500), $parsed['provider'], $parsed['id'],
                $thumbnail !== null ? $thumbnail : $autoThumb, $sortOrder, $isActive,
            ]);

            admin_flash('The video was added to ' . DB_NAME . '.gallery_videos (ID ' . $db->lastInsertId() . ').');
        }

        header('Location: ' . $self);
        exit;
    }
}

/* ----------------------------------------------------------------- Load data */
$rows    = [];
$editing = null;

if ($missingSchema) {
    $action = 'list';
} else {
    if ($action === 'edit' && $editId) {
        $stmt = $db->prepare('SELECT * FROM gallery_videos WHERE id = ?');
        $stmt->execute([$editId]);
        $editing = $stmt->fetch() ?: null;
        if (!$editing) {
            $action = 'list';
        }
    }

    $rows = $db->query(
        'SELECT * FROM gallery_videos ORDER BY sort_order ASC, id DESC'
    )->fetchAll();
}

$adminTitle = 'Gallery Videos';
include __DIR__ . '/includes/header.php';
?>

<?php if ($missingSchema): ?>

  <?php echo admin_gallery_schema_notice($missingSchema, $self); ?>

<?php elseif ($action === 'new' || $action === 'edit'): ?>

  <div class="ad-card">
    <h2 class="ad-card-title"><?php echo $editing ? 'Edit Video' : 'Add Video'; ?></h2>

    <form method="POST" action="<?php echo $self; ?>" enctype="multipart/form-data" class="ad-form">
      <?php echo admin_csrf_field(); ?>
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="id" value="<?php echo (int) ($editing['id'] ?? 0); ?>">

      <label class="ad-label" for="video_url">Video URL *</label>
      <input class="ad-input" type="url" id="video_url" name="video_url" maxlength="500" required
             value="<?php echo e($editing['video_url'] ?? ''); ?>"
             placeholder="https://www.youtube.com/watch?v=...">
      <small class="ad-hint">
        A YouTube or Vimeo link: watch, youtu.be, Shorts, embed or vimeo.com addresses all work.
      </small>

      <div class="ad-form-grid">
        <div>
          <label class="ad-label" for="title">Title</label>
          <input class="ad-input" type="text" id="title" name="title" maxlength="180"
                 value="<?php echo e($editing['title'] ?? ''); ?>"
                 placeholder="Shown under the video">
        </div>
        <div>
          <label class="ad-label" for="sort_order">Display order</label>
          <input class="ad-input" type="number" id="sort_order" name="sort_order" step="1"
                 value="<?php echo (int) ($editing['sort_order'] ?? 0); ?>">
          <small class="ad-hint">A smaller number comes first.</small>
        </div>
      </div>

      <label class="ad-label" for="thumbnail">Custom thumbnail</label>
      <input class="ad-input" type="file" id="thumbnail" name="thumbnail"
             accept="image/jpeg,image/png,image/gif,image/webp" data-preview="#thumbPreview">
      <small class="ad-hint">
        Optional, JPG/PNG/GIF/WEBP up to 5 MB. Left empty, the video's own still is used.
      </small>

      <?php $thumbSrc = $editing ? ofsy_video_thumb($editing) : ''; ?>
      <div class="ad-thumb ad-thumb-wide" <?php echo $thumbSrc === '' ? 'style="display:none"' : ''; ?>>
        <img id="thumbPreview" src="<?php echo e($thumbSrc); ?>" alt="">
      </div>

      <label class="ad-check">
        <input type="checkbox" name="is_active" value="1"
          <?php echo (!$editing || (int) $editing['is_active'] === 1) ? 'checked' : ''; ?>>
        <span>Show on the website</span>
      </label>

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
      <h2 class="ad-card-title">Gallery Videos <span class="ad-count"><?php echo count($rows); ?></span></h2>
      <a class="ad-btn ad-btn-primary" href="<?php echo $self; ?>?action=new">
        <i class="fa-solid fa-plus"></i> Add Video
      </a>
    </div>

    <?php if (!$rows): ?>
      <div class="ad-empty">
        <i class="fa-solid fa-video"></i>
        <p>No videos yet. Click <strong>Add Video</strong> and paste a YouTube or Vimeo link.</p>
      </div>
    <?php else: ?>
      <form method="POST" action="<?php echo $self; ?>">
        <?php echo admin_csrf_field(); ?>
        <input type="hidden" name="do" value="reorder">

        <div class="ad-video-grid">
          <?php foreach ($rows as $row): ?>
            <?php $thumb = ofsy_video_thumb($row); ?>
            <div class="ad-video <?php echo $row['is_active'] ? '' : 'is-hidden'; ?>">
              <div class="ad-video-thumb">
                <?php if ($thumb !== ''): ?>
                  <img src="<?php echo e($thumb); ?>" alt="" loading="lazy">
                <?php else: ?>
                  <span class="ad-video-blank"><i class="fa-solid fa-film"></i></span>
                <?php endif; ?>
                <a class="ad-video-play" href="<?php echo e($row['video_url']); ?>"
                   target="_blank" rel="noopener" title="Open the video in a new tab">
                  <i class="fa-solid fa-circle-play"></i>
                </a>
                <?php if (!$row['is_active']): ?>
                  <span class="ad-photo-flag"><i class="fa-solid fa-eye-slash"></i> Hidden</span>
                <?php endif; ?>
              </div>
              <div class="ad-video-body">
                <strong><?php echo $row['title'] !== '' ? e($row['title']) : '<em>Untitled</em>'; ?></strong>
                <small><?php echo e(ucfirst($row['provider'])); ?> &middot; <?php echo e($row['video_id']); ?></small>
              </div>
              <div class="ad-video-foot">
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
                        title="Delete" data-confirm="Delete this video? This cannot be undone.">
                  <i class="fa-solid fa-trash"></i>
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="ad-order-bar">
          <small>Change the number under a video to move it, then save. A smaller number comes first.</small>
          <button type="submit" class="ad-btn ad-btn-line">
            <i class="fa-solid fa-arrow-down-1-9"></i> Save order
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>

<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
