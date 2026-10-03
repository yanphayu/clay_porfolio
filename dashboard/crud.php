<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

$entities = require __DIR__ . '/entities.php';
$entityKey = (string) ($_GET['entity'] ?? '');
$entity = $entities[$entityKey] ?? null;

if ($entity === null) {
    dashboard_redirect('index.php');
}

dashboard_require_login();

$pdo = portfolio_db();
if ($pdo === null || !portfolio_db_installed()) {
    dashboard_flash('Install the database first.', 'err');
    dashboard_redirect('index.php');
}

$table = (string) $entity['table'];
$listUrl = 'crud.php?entity=' . urlencode($entityKey);
$palette = ['var(--lav)', 'var(--peach)', 'var(--mint)', 'var(--pink)', 'var(--lemon)', 'var(--sky)'];

// ---------------- mutations ----------------
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $row = [];
        $missing = [];

        foreach ($entity['fields'] as $field) {
            $value = trim((string) ($_POST[$field['name']] ?? ''));
            if (!empty($field['required']) && $value === '' && $field['type'] !== 'image') {
                $missing[] = $field['label'];
            }
            if ($field['type'] === 'number') {
                $value = (string) (int) $value;
            }
            $row[$field['name']] = $value;
        }

        // Image upload for rows of type 'image'.
        foreach ($entity['fields'] as $field) {
            if (($field['type'] ?? '') !== 'image') {
                continue;
            }
            $fileKey = $field['name'] . '_file';
            if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
                $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
                $mime = (string) (mime_content_type($_FILES[$fileKey]['tmp_name']) ?: '');
                if (isset($allowed[$mime])) {
                    $dir = __DIR__ . '/../assets/uploads';
                    if (!is_dir($dir)) {
                        mkdir($dir, 0775, true);
                    }
                    $name = $entityKey . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
                    move_uploaded_file($_FILES[$fileKey]['tmp_name'], $dir . '/' . $name);
                    $row[$field['name']] = 'assets/uploads/' . $name;
                } else {
                    dashboard_flash('Image must be JPG, PNG, WebP or GIF.', 'err');
                    dashboard_redirect($listUrl . '&action=form&id=' . $id);
                }
            }
        }

        if ($missing !== []) {
            dashboard_flash('Please fill in: ' . implode(', ', $missing), 'err');
            dashboard_redirect($listUrl);
        }

        if ($id > 0) {
            $set = implode(', ', array_map(static fn ($c) => "$c = :$c", array_keys($row)));
            $row['id'] = $id;
            $pdo->prepare("UPDATE $table SET $set WHERE id = :id")->execute($row);
            dashboard_flash($entity['label'] . ' updated.');
        } else {
            $cols = implode(', ', array_keys($row));
            $marks = implode(', ', array_map(static fn ($c) => ":$c", array_keys($row)));
            $pdo->prepare("INSERT INTO $table ($cols) VALUES ($marks)")->execute($row);
            dashboard_flash($entity['label'] . ' added.');
        }
        dashboard_redirect($listUrl);
    }

    if ($action === 'delete') {
        $pdo->prepare("DELETE FROM $table WHERE id = :id")->execute(['id' => (int) ($_POST['id'] ?? 0)]);
        dashboard_flash($entity['label'] . ' deleted.');
        dashboard_redirect($listUrl);
    }

    dashboard_redirect($listUrl);
}

// ---------------- pages ----------------
$action = (string) ($_GET['action'] ?? 'list');
$id = (int) ($_GET['id'] ?? 0);

if ($action === 'form') {
    $row = [];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch() ?: [];
    }
    $value = static function (string $name) use ($row, $entity): string {
        if (isset($row[$name])) {
            return (string) $row[$name];
        }
        foreach ($entity['fields'] as $field) {
            if ($field['name'] === $name && isset($field['default'])) {
                return (string) $field['default'];
            }
        }
        return '';
    };

    dashboard_header($entity['title'] . ' · form', $entityKey);
    ?>
    <div class="panel clay" style="--c: rgba(255,255,255,.85)">
      <a class="backlink" href="<?= e($listUrl) ?>"><?= clay_icon('back', 15) ?> All <?= e(strtolower((string) $entity['title'])) ?></a>
      <h2><?= $id > 0 ? 'Edit' : 'New' ?> <?= e((string) $entity['label']) ?> <?= clay_icon((string) $entity['icon'], 22) ?></h2>

      <form method="post" class="form-grid" action="<?= e($listUrl) ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save" />
        <input type="hidden" name="id" value="<?= $id ?>" />
        <?php foreach ($entity['fields'] as $field): ?>
          <?php $val = $value((string) $field['name']); ?>
          <?php if ($field['type'] === 'textarea'): ?>
          <label class="field-label"><?= e((string) $field['label']) ?>
            <textarea class="field" name="<?= e((string) $field['name']) ?>" rows="3" <?= !empty($field['required']) ? 'required' : '' ?>><?= e($val) ?></textarea>
          </label>
          <?php elseif ($field['type'] === 'number'): ?>
          <label class="field-label"><?= e((string) $field['label']) ?>
            <input class="field" type="number" name="<?= e((string) $field['name']) ?>" value="<?= e($val) ?>" />
          </label>
          <?php elseif ($field['type'] === 'image'): ?>
          <label class="field-label"><?= e((string) $field['label']) ?>
            <input class="field" type="text" name="<?= e((string) $field['name']) ?>" value="<?= e($val) ?>" placeholder="assets/uploads/pic.jpg or https://… (optional)" />
          </label>
          <label class="field-label">Upload image instead
            <input class="field file-field" style="width:auto" type="file" name="<?= e((string) $field['name']) ?>_file" accept="image/*" />
          </label>
          <?php if ($val !== ''): ?>
          <img src="<?= e(str_starts_with($val, 'http') ? $val : '../' . $val) ?>" alt="" style="max-width:160px;border-radius:14px" />
          <?php endif ?>
          <?php elseif ($field['type'] === 'icon'): ?>
          <label class="field-label" for="field-<?= e((string) $field['name']) ?>"><?= e((string) $field['label']) ?></label>
          <div class="icon-row">
            <select class="field" id="field-<?= e((string) $field['name']) ?>" name="<?= e((string) $field['name']) ?>">
              <?php foreach (clay_icon_names() as $name): ?>
              <option value="<?= e($name) ?>"<?= $name === $val ? ' selected' : '' ?>><?= e($name) ?></option>
              <?php endforeach ?>
            </select>
            <span class="icon-swatch" style="--c: var(--lav)" title="Rendered with the clay icon set"><?= clay_icon($val !== '' ? $val : 'bubble', 18) ?></span>
          </div>
          <?php elseif ($field['type'] === 'color'): ?>
          <label class="field-label" for="field-<?= e((string) $field['name']) ?>"><?= e((string) $field['label']) ?></label>
          <div class="icon-row">
            <select class="field" id="field-<?= e((string) $field['name']) ?>" name="<?= e((string) $field['name']) ?>">
              <?php foreach ($palette as $c): ?>
              <option value="<?= e($c) ?>"<?= $c === $val ? ' selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach ?>
              <?php if ($val !== '' && !in_array($val, $palette, true)): ?>
              <option value="<?= e($val) ?>" selected><?= e($val) ?></option>
              <?php endif ?>
            </select>
            <span class="icon-swatch" style="--c: <?= e($val !== '' ? $val : 'var(--lav)') ?>"></span>
          </div>
          <?php else: ?>
          <label class="field-label"><?= e((string) $field['label']) ?>
            <input class="field" type="text" name="<?= e((string) $field['name']) ?>" value="<?= e($val) ?>" <?= !empty($field['required']) ? 'required' : '' ?> />
          </label>
          <?php endif ?>
        <?php endforeach ?>
        <div class="btn-row">
          <button class="btn clay clay-soft clay-press" style="--c: var(--mint)" type="submit"><?= clay_icon('sparkle', 17) ?> Save</button>
          <a class="btn clay clay-soft clay-press" style="--c: rgba(255,255,255,.85)" href="<?= e($listUrl) ?>">Cancel</a>
        </div>
      </form>
    </div>
    <?php
    dashboard_footer();
    return;
}

// ---------------- list (default) ----------------
$rows = $pdo->query("SELECT * FROM $table ORDER BY sort_order, id")->fetchAll();

dashboard_header((string) $entity['title'], $entityKey);
?>
  <div class="panel clay" style="--c: rgba(255,255,255,.85)">
    <div class="panel-head">
      <div>
        <h2><?= e((string) $entity['title']) ?> <?= clay_icon((string) $entity['icon'], 22) ?></h2>
        <p class="sub"><?= e((string) $entity['subtitle']) ?></p>
      </div>
      <a class="btn clay clay-soft clay-press" style="--c: var(--mint)" href="<?= e($listUrl) ?>&amp;action=form"><?= clay_icon('plus', 17) ?> New</a>
    </div>

    <?php if ($rows === []): ?>
    <p class="empty">Nothing here yet — add your first one!</p>
    <?php else: ?>
    <table class="dash-table">
      <thead>
        <tr>
          <?php foreach ($entity['list'] as $col): ?><th><?= e((string) $col['label']) ?></th><?php endforeach ?>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
          <?php foreach ($entity['list'] as $col): ?>
          <td>
            <?php if (($col['type'] ?? '') === 'icon'): ?>
            <span class="icon-swatch" style="--c: var(--lav)"><?= clay_icon((string) $row[$col['key']], 16) ?></span>
            <?php elseif (($col['type'] ?? '') === 'image'): ?>
            <?php $img = (string) ($row[$col['key']] ?? ''); ?>
            <?= $img !== '' ? '<img src="' . (str_starts_with($img, 'http') ? '' : '../') . e($img) . '" style="width:40px;height:40px;object-fit:cover;border-radius:10px" alt="" />' : '—' ?>
            <?php else: ?>
            <?= e((string) $row[$col['key']]) ?>
            <?php endif ?>
          </td>
          <?php endforeach ?>
          <td>
            <a class="mini-btn" style="--c: var(--lemon)" href="<?= e($listUrl) ?>&amp;action=form&amp;id=<?= (int) $row['id'] ?>"><?= clay_icon('pen', 13) ?> Edit</a>
            <form method="post" class="inline-form" action="<?= e($listUrl) ?>" onsubmit="return confirm('Delete this item?');">
              <input type="hidden" name="action" value="delete" />
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>" />
              <button class="mini-btn" style="--c: var(--peach)" type="submit"><?= clay_icon('trash', 13) ?> Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
    <?php endif ?>
  </div>
<?php
dashboard_footer();
