<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

if (($_GET['action'] ?? '') === 'logout') {
    session_destroy();
    dashboard_redirect('login.php');
}

if (dashboard_user()) {
    dashboard_redirect('index.php');
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (hash_equals(ADMIN_PASSWORD, (string) ($_POST['password'] ?? ''))) {
        $_SESSION['portfolio_admin'] = true;
        session_regenerate_id(true);
        dashboard_redirect('index.php');
    }
    $error = 'Wrong password — that clay does not match.';
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>dashboard · login</title>
    <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="dashboard.css" />
  </head>
  <body>
    <div class="backdrop" aria-hidden="true">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      <div class="blob blob-3"></div>
    </div>

    <div class="login-wrap">
      <form class="login-card clay" method="post">
        <div class="login-face clay-inset" style="--c: #fff0f8"><?= clay_icon('artist', 52) ?></div>
        <h2>Squish in, admin</h2>
        <?php if ($error !== ''): ?><p class="login-error"><?= e($error) ?></p><?php endif ?>
        <input class="field" type="password" name="password" placeholder="Password" required autofocus />
        <button class="btn clay clay-soft clay-press" style="--c: var(--lemon)" type="submit"><?= clay_icon('logout', 16) ?> Unlock dashboard</button>
        <p class="login-hint">Default password is <code>admin</code> — change it in <code>php/config.php</code>.</p>
      </form>
    </div>
  </body>
</html>
