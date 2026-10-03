<?php

declare(strict_types=1);

require_once __DIR__ . '/../php/helpers.php';
require_once __DIR__ . '/../php/icons.php';
require_once __DIR__ . '/../php/config.php';
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/content.php';

session_start();

const DASHBOARD_NAV = [
    'index' => ['home', 'Overview', 'index.php'],
    'projects' => ['cube', 'Projects', 'crud.php?entity=projects'],
    'skills' => ['star', 'Skills', 'crud.php?entity=skills'],
    'stats' => ['bolt', 'Stats', 'crud.php?entity=stats'],
    'socials' => ['heart', 'Socials', 'crud.php?entity=socials'],
    'marquee' => ['wind', 'Marquee', 'crud.php?entity=marquee'],
    'messages' => ['mail', 'Messages', 'messages.php'],
    'logs' => ['pen', 'Logs', 'logs.php'],
    'settings' => ['gear', 'Settings', 'settings.php'],
];

function dashboard_user(): bool
{
    return !empty($_SESSION['portfolio_admin']);
}

function dashboard_require_login(): void
{
    if (!dashboard_user()) {
        header('Location: login.php');
        exit;
    }
}

function dashboard_redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

/** Set a flash message (with args) or read+clear it (no args). */
function dashboard_flash(?string $message = null, string $type = 'ok'): ?array
{
    if ($message !== null) {
        $_SESSION['dashboard_flash'] = ['message' => $message, 'type' => $type];
        return null;
    }
    $flash = $_SESSION['dashboard_flash'] ?? null;
    unset($_SESSION['dashboard_flash']);
    return is_array($flash) ? $flash : null;
}

/** Guard: logged in AND database installed. Returns the PDO. */
function dashboard_db_ready(): PDO
{
    dashboard_require_login();

    $pdo = portfolio_db();
    if ($pdo === null || !portfolio_db_installed()) {
        dashboard_flash('Install the database first.', 'err');
        dashboard_redirect('index.php');
    }

    return $pdo ?? throw new RuntimeException('Database not ready.');
}

function dashboard_header(string $title, string $active = ''): void
{
    $flash = dashboard_flash();
    ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>dashboard · <?= e($title) ?></title>
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

    <div class="dash-topbar clay clay-soft wrap">
      <button class="dash-burger" id="dash-burger" aria-label="Menu" type="button">☰</button>
      <a class="sidebar-logo" style="padding:0" href="index.php">momo.dev <?= clay_icon('gear', 15) ?> admin</a>
      <a class="clay clay-soft clay-press nav-cta" style="--c: rgba(255,255,255,.85); padding:10px 14px" href="../index.php" target="_blank" rel="noopener"><?= clay_icon('eye', 15) ?> View site</a>
    </div>

    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="dash-layout wrap">
      <aside class="sidebar clay" id="sidebar" style="--c: rgba(255,255,255,.8)">
        <a class="sidebar-logo" href="index.php">momo.dev <?= clay_icon('gear', 15) ?> admin</a>
        <nav class="sidebar-links" aria-label="Dashboard">
          <?php foreach (DASHBOARD_NAV as $key => [$icon, $label, $href]): ?>
          <a href="<?= e($href) ?>"<?= $key === $active ? ' class="active"' : '' ?>><?= e($label) ?></a>
          <?php endforeach ?>
        </nav>
        <div class="sidebar-ctas">
          <a class="clay clay-soft clay-press nav-cta" style="--c: rgba(255,255,255,.85)" href="../index.php" target="_blank" rel="noopener"><?= clay_icon('eye', 15) ?> View site</a>
          <a class="clay clay-soft clay-press nav-cta" style="--c: var(--sky)" href="login.php?action=logout" title="Log out"><?= clay_icon('logout', 15) ?> Log out</a>
        </div>
      </aside>

      <main class="dash-main">
        <?php if ($flash): ?>
      <div class="flash clay clay-soft <?= $flash['type'] === 'ok' ? 'flash-ok' : 'flash-err' ?>">
        <?= clay_icon($flash['type'] === 'ok' ? 'sparkle' : 'flame', 16) ?>
        <?= e($flash['message']) ?>
      </div>
      <?php endif ?>
    <?php
}

function dashboard_footer(): void
{
    ?>
    </main>
    </div>

    <footer>
      <p>clay dashboard — squish responsibly <?= clay_icon('bubble', 13) ?></p>
    </footer>
    <script>
      var burger = document.getElementById('dash-burger');
      var sidebar = document.getElementById('sidebar');
      var overlay = document.getElementById('sidebar-overlay');
      function toggleSidebar(force) {
        var open = force !== undefined ? force : !sidebar.classList.contains('open');
        sidebar.classList.toggle('open', open);
        overlay.classList.toggle('open', open);
      }
      if (burger) burger.addEventListener('click', function () { toggleSidebar(); });
      if (overlay) overlay.addEventListener('click', function () { toggleSidebar(false); });
    </script>
  </body>
</html>
    <?php
}
