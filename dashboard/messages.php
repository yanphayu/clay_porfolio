<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

$pdo = dashboard_db_ready();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');
    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0 && $action === 'read') {
        $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = :id')->execute(['id' => $id]);
        dashboard_flash('Message marked as read.');
    } elseif ($id > 0 && $action === 'unread') {
        $pdo->prepare('UPDATE messages SET is_read = 0 WHERE id = :id')->execute(['id' => $id]);
        dashboard_flash('Message marked as unread.');
    } elseif ($id > 0 && $action === 'delete') {
        $pdo->prepare('DELETE FROM messages WHERE id = :id')->execute(['id' => $id]);
        dashboard_flash('Message deleted.');
    }
    dashboard_redirect('messages.php');
}

$rows = $pdo->query('SELECT * FROM messages ORDER BY is_read, created_at DESC, id DESC')->fetchAll();

// Detail view: /dashboard/messages.php?id=5 (marks the message as read)
$detailId = (int) ($_GET['id'] ?? 0);
$detail = null;
if ($detailId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM messages WHERE id = :id');
    $stmt->execute(['id' => $detailId]);
    $detail = $stmt->fetch() ?: null;
    if ($detail && (int) $detail['is_read'] === 0) {
        $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = :id')->execute(['id' => $detailId]);
        $detail['is_read'] = 1;
    }
}

dashboard_header('messages', 'messages');
?>
  <?php if ($detail): ?>
  <div class="panel clay" style="--c: rgba(255,255,255,.85)">
    <a class="backlink" href="messages.php"><?= clay_icon('back', 15) ?> All messages</a>
    <article class="msg-card clay clay-soft" style="--c: var(--lav)">
      <header class="msg-head">
        <strong><?= e((string) $detail['name']) ?></strong>
        <a href="mailto:<?= e((string) $detail['email']) ?>"><?= e((string) $detail['email']) ?></a>
        <time><?= e((string) $detail['created_at']) ?></time>
      </header>
      <p class="msg-body"><?= nl2br(e((string) $detail['msg'])) ?></p>
      <div class="btn-row">
        <a class="mini-btn" style="--c: var(--lemon); text-decoration:none" href="mailto:<?= e((string) $detail['email']) ?>?subject=Re: your message on momo.dev"><?= clay_icon('send', 13) ?> Reply by email</a>
        <form method="post" class="inline-form">
          <input type="hidden" name="id" value="<?= (int) $detail['id'] ?>" />
          <input type="hidden" name="action" value="unread" />
          <button class="mini-btn" style="--c: var(--mint)" type="submit"><?= clay_icon('eye', 13) ?> Mark unread</button>
        </form>
        <form method="post" class="inline-form" onsubmit="return confirm('Delete this message?');">
          <input type="hidden" name="id" value="<?= (int) $detail['id'] ?>" />
          <input type="hidden" name="action" value="delete" />
          <button class="mini-btn" style="--c: var(--peach)" type="submit"><?= clay_icon('trash', 13) ?> Delete</button>
        </form>
      </div>
    </article>
  </div>
  <?php return; endif ?>

  <div class="panel clay" style="--c: rgba(255,255,255,.85)">
    <div class="panel-head">
      <div>
        <h2>Inbox <?= clay_icon('mail', 22) ?></h2>
        <p class="sub">Messages sent through the contact form on the public page.</p>
      </div>
    </div>

    <?php if ($rows === []): ?>
    <p class="empty">No messages yet. The pigeons are resting.</p>
    <?php else: ?>
    <div class="msg-list">
      <?php foreach ($rows as $msg): ?>
      <article class="msg-card clay clay-soft <?= (int) $msg['is_read'] === 1 ? 'is-read' : '' ?>" style="--c: var(--lav)">
        <header class="msg-head">
          <strong><a href="messages.php?id=<?= (int) $msg['id'] ?>"><?= e((string) $msg['name']) ?></a></strong>
          <a href="mailto:<?= e((string) $msg['email']) ?>"><?= e((string) $msg['email']) ?></a>
          <time><?= e((string) $msg['created_at']) ?></time>
          <?php if ((int) $msg['is_read'] === 0): ?><span class="unread-dot" title="unread"></span><?php endif ?>
        </header>
        <p class="msg-body"><?= nl2br(e(portfolio_truncate((string) $msg['msg'], 160) . (strlen((string) $msg['msg']) > 160 ? '…' : ''))) ?></p>
        <div class="btn-row">
          <a class="mini-btn" style="--c: var(--sky); text-decoration:none" href="messages.php?id=<?= (int) $msg['id'] ?>"><?= clay_icon('eye', 13) ?> View detail</a>
          <a class="mini-btn" style="--c: var(--lemon); text-decoration:none" href="mailto:<?= e((string) $msg['email']) ?>?subject=Re: your message on momo.dev"><?= clay_icon('send', 13) ?> Reply</a>
          <form method="post" class="inline-form">
            <input type="hidden" name="id" value="<?= (int) $msg['id'] ?>" />
            <input type="hidden" name="action" value="<?= (int) $msg['is_read'] === 1 ? 'unread' : 'read' ?>" />
            <button class="mini-btn" style="--c: var(--mint)" type="submit"><?= clay_icon('eye', 13) ?> <?= (int) $msg['is_read'] === 1 ? 'Mark unread' : 'Mark read' ?></button>
          </form>
          <form method="post" class="inline-form" onsubmit="return confirm('Delete this message?');">
            <input type="hidden" name="id" value="<?= (int) $msg['id'] ?>" />
            <input type="hidden" name="action" value="delete" />
            <button class="mini-btn" style="--c: var(--peach)" type="submit"><?= clay_icon('trash', 13) ?> Delete</button>
          </form>
        </div>
      </article>
      <?php endforeach ?>
    </div>
    <?php endif ?>
  </div>
<?php
dashboard_footer();
