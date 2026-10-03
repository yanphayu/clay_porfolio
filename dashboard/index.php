<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';
dashboard_require_login();

// One-click install: creates the database, tables and demo content.
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'install') {
    try {
        portfolio_install();
        dashboard_flash('Database installed and seeded. Everything is squishy now.');
    } catch (Throwable $ex) {
        dashboard_flash('Install failed: ' . $ex->getMessage(), 'err');
    }
    dashboard_redirect('index.php');
}

$pdo = portfolio_db();
$installed = $pdo !== null && portfolio_db_installed();

dashboard_header('overview', 'index');

if (!$installed) {
    $connected = $pdo !== null;
    ?>
    <div class="panel clay" style="--c: var(--pink)">
      <h2><?= $connected ? 'One step left: install the kiln' : 'MySQL is not reachable' ?> <?= clay_icon('flame', 22) ?></h2>
      <?php if ($connected): ?>
      <p class="sub">
        Connected as <code><?= e(DB_USER) ?></code> — the database <code><?= e(DB_NAME) ?></code> and its tables will be
        created, then seeded with the demo content from <code>php/data.php</code>.
      </p>
      <form method="post" class="btn-row">
        <input type="hidden" name="action" value="install" />
        <button class="btn clay clay-soft clay-press" style="--c: var(--lemon)" type="submit"><?= clay_icon('flame', 17) ?> Install &amp; seed database</button>
      </form>
      <?php else: ?>
      <p class="sub">
        Check that MySQL is running and that the credentials in <code>php/config.php</code> are correct
        (currently user <code><?= e(DB_USER) ?></code> on <code><?= e(DB_HOST) ?>:<?= e((string) DB_PORT) ?></code>).
        The public site keeps working with the demo content meanwhile.
      </p>
      <?php endif ?>
    </div>
    <?php
    dashboard_footer();
    return;
}

$count = static function (string $table) use ($pdo): int {
    return (int) $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
};
$unread = (int) $pdo->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();

$cards = [
    ['cube', 'Projects', $count('projects'), 'crud.php?entity=projects', 'var(--lav)'],
    ['star', 'Skills', $count('skills'), 'crud.php?entity=skills', 'var(--mint)'],
    ['bolt', 'Stats', $count('stats'), 'crud.php?entity=stats', 'var(--lemon)'],
    ['heart', 'Socials', $count('socials'), 'crud.php?entity=socials', 'var(--pink)'],
    ['wind', 'Marquee', $count('marquee_items'), 'crud.php?entity=marquee', 'var(--sky)'],
    ['mail', 'Unread messages', $unread, 'messages.php', 'var(--peach)'],
    ['gear', 'Settings', $count('settings'), 'settings.php', 'var(--sky)'],
];
?>
    <div class="dash-grid">
      <?php foreach ($cards as [$icon, $label, $num, $href, $color]): ?>
      <a class="dash-card clay clay-soft" style="--c: <?= e($color) ?>" href="<?= e($href) ?>">
        <?= clay_icon($icon, 22) ?>
        <span class="num"><?= e((string) $num) ?></span>
        <span class="lbl"><?= e($label) ?></span>
      </a>
      <?php endforeach ?>
    </div>

    <div class="panel clay" style="--c: rgba(255,255,255,.8)">
      <h2>How this works <?= clay_icon('bubble', 20) ?></h2>
      <p class="sub">
        Everything you edit here lives in MySQL and shows up on the public page instantly — just refresh
        <code>../index.php</code>. If MySQL is ever offline, the site quietly falls back to the demo content in
        <code>php/data.php</code>. Contact-form submissions land in your inbox under <em>Messages</em>.
      </p>
      <div class="btn-row">
        <a class="btn clay clay-soft clay-press" style="--c: var(--mint)" href="settings.php"><?= clay_icon('gear', 17) ?> Edit site copy</a>
        <a class="btn clay clay-soft clay-press" style="--c: var(--lemon)" href="crud.php?entity=projects"><?= clay_icon('plus', 17) ?> Add a project</a>
      </div>
    </div>
<?php
dashboard_footer();
