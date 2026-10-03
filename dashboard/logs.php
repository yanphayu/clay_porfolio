<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

$pdo = dashboard_db_ready();

// Make sure the table exists even on databases installed before logs were added.
$pdo->exec(<<<'SQL'
    CREATE TABLE IF NOT EXISTS logs (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        level VARCHAR(16) NOT NULL DEFAULT 'info',
        message VARCHAR(500) NOT NULL,
        context VARCHAR(255) NOT NULL DEFAULT '',
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    if ($action === 'clear') {
        $pdo->exec('DELETE FROM logs');
        dashboard_flash('Log cleared.');
    } elseif ($action === 'add') {
        $message = trim((string) ($_POST['message'] ?? ''));
        $level = trim((string) ($_POST['level'] ?? 'info')) ?: 'info';
        if ($message !== '') {
            portfolio_log($message, $level, 'dashboard');
            dashboard_flash('Log entry added.');
        }
    }
    dashboard_redirect('logs.php');
}

$rows = $pdo->query('SELECT * FROM logs ORDER BY created_at DESC, id DESC LIMIT 200')->fetchAll();

dashboard_header('logs', 'logs');
?>
  <div class="panel clay" style="--c: rgba(255,255,255,.85)">
    <div class="panel-head">
      <div>
        <h2>Activity log <?= clay_icon('pen', 22) ?></h2>
        <p class="sub">Entries written via <code>portfolio_log($message, $level, $context)</code> anywhere in the PHP code.</p>
      </div>
    </div>

    <form method="post" class="btn-row" style="margin-bottom:1rem">
      <input type="hidden" name="action" value="add" />
      <input class="field" type="text" name="message" placeholder="Log a note…" required style="flex:1" />
      <select class="field" name="level" style="width:auto">
        <option value="info">info</option>
        <option value="warn">warn</option>
        <option value="error">error</option>
      </select>
      <button class="btn clay clay-soft clay-press" style="--c: var(--mint)" type="submit"><?= clay_icon('plus', 15) ?> Add entry</button>
      <button class="btn clay clay-soft clay-press" style="--c: var(--peach)" type="submit" name="action" value="clear" onclick="return confirm('Clear the whole log?');" formnovalidate><?= clay_icon('trash', 15) ?> Clear log</button>
    </form>

    <?php if ($rows === []): ?>
    <p class="empty">No log entries yet. Totally quiet in here.</p>
    <?php else: ?>
    <div class="msg-list">
      <?php foreach ($rows as $row): ?>
      <article class="msg-card clay clay-soft" style="--c: var(--sky)">
        <header class="msg-head">
          <strong><?= e((string) $row['level']) ?></strong>
          <?php if ($row['context'] !== ''): ?><span><?= e((string) $row['context']) ?></span><?php endif ?>
          <time><?= e((string) $row['created_at']) ?></time>
        </header>
        <p class="msg-body"><?= e((string) $row['message']) ?></p>
      </article>
      <?php endforeach ?>
    </div>
    <?php endif ?>
  </div>
<?php
dashboard_footer();
