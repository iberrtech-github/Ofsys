<?php
/**
 * OFSYS Admin - login screen.
 */
require_once __DIR__ . '/includes/bootstrap.php';

if (admin_is_logged_in()) {
    header('Location: ' . ADMIN_PATH . '/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_verify_csrf();

    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $db       = ofsy_db();

    if (!$db) {
        $error = 'The database is not reachable. Start MySQL in XAMPP and try again.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter both your username and password.';
    } else {
        $stmt = $db->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $user['id'];
            $_SESSION['admin_user'] = $user['username'];
            header('Location: ' . ADMIN_PATH . '/index.php');
            exit;
        }

        $error = 'Wrong username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Admin Login | OFSYS Education</title>
  <link rel="icon" type="image/png" href="<?php echo BASE_PATH; ?>/assets/images/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/admin.css">
</head>
<body class="ad-login-body">

  <div class="ad-login-card">
    <img src="<?php echo BASE_PATH; ?>/assets/images/logo.png" alt="OFSYS Education" class="ad-login-logo">
    <h1>Admin Panel</h1>
    <p>Sign in to manage placements and the gallery.</p>

    <?php if ($error): ?>
      <div class="ad-alert ad-alert-error">
        <i class="fa-solid fa-circle-exclamation"></i><span><?php echo e($error); ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <?php echo admin_csrf_field(); ?>

      <label class="ad-label" for="username">Username</label>
      <input class="ad-input" type="text" id="username" name="username" autocomplete="username" required autofocus>

      <label class="ad-label" for="password">Password</label>
      <input class="ad-input" type="password" id="password" name="password" autocomplete="current-password" required>

      <button type="submit" class="ad-btn ad-btn-primary ad-btn-block">
        Sign In <i class="fa-solid fa-arrow-right"></i>
      </button>
    </form>

    <a class="ad-login-back" href="<?php echo BASE_PATH; ?>/">
      <i class="fa-solid fa-arrow-left"></i> Back to website
    </a>
  </div>

</body>
</html>
