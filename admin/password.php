<?php
/**
 * OFSYS Admin - change the login password.
 */
require_once __DIR__ . '/includes/bootstrap.php';
admin_require_login();

$db   = ofsy_db();
$self = ADMIN_PATH . '/password.php';

if ($db && $_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();

    $current = (string) ($_POST['current_password'] ?? '');
    $new     = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');

    $stmt = $db->prepare('SELECT * FROM admin_users WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current, $user['password_hash'])) {
        admin_flash('Your current password is not correct.', 'error');
    } elseif (strlen($new) < 8) {
        admin_flash('The new password must be at least 8 characters long.', 'error');
    } elseif ($new !== $confirm) {
        admin_flash('The two new passwords do not match.', 'error');
    } else {
        $db->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')
           ->execute([password_hash($new, PASSWORD_DEFAULT), $user['id']]);
        admin_flash('Your password was changed.');
    }

    header('Location: ' . $self);
    exit;
}

$adminTitle = 'Change Password';
include __DIR__ . '/includes/header.php';
?>

<?php if (!$db): ?>
  <div class="ad-alert ad-alert-error">
    <i class="fa-solid fa-circle-exclamation"></i>
    <span>The database is not reachable. Start MySQL in the XAMPP Control Panel, then reload this page.</span>
  </div>
<?php else: ?>
  <div class="ad-card ad-card-narrow">
    <h2 class="ad-card-title">Change Password</h2>
    <p class="ad-card-note">You are signed in as <strong><?php echo e($_SESSION['admin_user'] ?? 'admin'); ?></strong>.</p>

    <form method="POST" action="<?php echo $self; ?>" class="ad-form" autocomplete="off">
      <?php echo admin_csrf_field(); ?>

      <label class="ad-label" for="current_password">Current Password</label>
      <input class="ad-input" type="password" id="current_password" name="current_password" required>

      <label class="ad-label" for="new_password">New Password</label>
      <input class="ad-input" type="password" id="new_password" name="new_password" minlength="8" required>
      <small class="ad-hint">At least 8 characters.</small>

      <label class="ad-label" for="confirm_password">Repeat New Password</label>
      <input class="ad-input" type="password" id="confirm_password" name="confirm_password" minlength="8" required>

      <div class="ad-form-actions">
        <button type="submit" class="ad-btn ad-btn-primary">
          <i class="fa-solid fa-key"></i> Update Password
        </button>
      </div>
    </form>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
