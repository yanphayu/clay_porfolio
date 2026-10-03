<?php

declare(strict_types=1);

require_once __DIR__ . '/_init.php';

$pdo = dashboard_db_ready();

$fields = [
    ['key' => 'badge_text', 'label' => 'Hero badge text', 'type' => 'text'],
    ['key' => 'badge_text_visible', 'label' => 'Hero badge visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_line1', 'label' => 'Hero line 1', 'type' => 'text'],
    ['key' => 'hero_like', 'label' => 'Word before the highlight', 'type' => 'text'],
    ['key' => 'hero_highlight', 'label' => 'Highlighted word (lemon pill)', 'type' => 'text'],
    ['key' => 'hero_name', 'label' => 'Your name', 'type' => 'text'],
    ['key' => 'hero_lead', 'label' => 'Hero intro ({name} becomes your name)', 'type' => 'textarea'],
    ['key' => 'about_p1', 'label' => 'About paragraph 1', 'type' => 'textarea'],
    ['key' => 'about_p2', 'label' => 'About paragraph 2', 'type' => 'textarea'],
    ['key' => 'contact_heading', 'label' => 'Contact heading', 'type' => 'text'],
    ['key' => 'contact_blurb', 'label' => 'Contact blurb', 'type' => 'textarea'],
    ['key' => 'footer_text', 'label' => 'Footer text (after the heart)', 'type' => 'text'],
    ['key' => 'profile_image', 'label' => 'Profile image (path or URL — empty uses the clay icon)', 'type' => 'text'],
    ['key' => 'marquee_tilt', 'label' => 'Marquee tilt (degrees, e.g. -2, 0, 3)', 'type' => 'text'],
    ['key' => 'theme', 'label' => 'Color theme', 'type' => 'select', 'options' => ['default' => 'Pastel clay (current)', 'light-dark' => 'Light dark (white bg, black cards)', 'sunset' => 'Sunset', 'mint' => 'Mint', 'lavender' => 'Lavender', 'dark' => 'Midnight']],
    ['key' => 'site_title', 'label' => 'Browser tab title', 'type' => 'text'],
    ['key' => 'meta_description', 'label' => 'Meta description (SEO)', 'type' => 'text'],
    ['key' => 'nav_logo', 'label' => 'Nav logo text', 'type' => 'text'],
    ['key' => 'nav_cta', 'label' => 'Nav button text', 'type' => 'text'],
    ['key' => 'hero_cta_work', 'label' => 'Hero button 1', 'type' => 'text'],
    ['key' => 'hero_cta_contact', 'label' => 'Hero button 2', 'type' => 'text'],
    ['key' => 'hero_cta_work_visible', 'label' => 'Hero button 1 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_cta_contact_visible', 'label' => 'Hero button 2 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_chip1', 'label' => 'Floating chip 1', 'type' => 'text'],
    ['key' => 'hero_chip2', 'label' => 'Floating chip 2', 'type' => 'text'],
    ['key' => 'hero_chip3', 'label' => 'Floating chip 3', 'type' => 'text'],
    ['key' => 'hero_chip4', 'label' => 'Floating chip 4', 'type' => 'text'],
    ['key' => 'hero_chip1_icon', 'label' => 'Floating chip 1 icon', 'type' => 'icon'],
    ['key' => 'hero_chip2_icon', 'label' => 'Floating chip 2 icon', 'type' => 'icon'],
    ['key' => 'hero_chip3_icon', 'label' => 'Floating chip 3 icon', 'type' => 'icon'],
    ['key' => 'hero_chip4_icon', 'label' => 'Floating chip 4 icon', 'type' => 'icon'],
    ['key' => 'hero_chip1_visible', 'label' => 'Floating chip 1 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_chip2_visible', 'label' => 'Floating chip 2 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_chip3_visible', 'label' => 'Floating chip 3 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'hero_chip4_visible', 'label' => 'Floating chip 4 visible?', 'type' => 'select', 'options' => ['1' => 'Show', '0' => 'Hide']],
    ['key' => 'works_chip', 'label' => 'Works section chip', 'type' => 'text'],
    ['key' => 'works_heading', 'label' => 'Works section heading', 'type' => 'text'],
    ['key' => 'works_sub', 'label' => 'Works section subtitle', 'type' => 'textarea'],
    ['key' => 'skills_chip', 'label' => 'Skills section chip', 'type' => 'text'],
    ['key' => 'skills_heading', 'label' => 'Skills section heading', 'type' => 'text'],
    ['key' => 'skills_sub', 'label' => 'Skills section subtitle', 'type' => 'textarea'],
    ['key' => 'about_chip', 'label' => 'About section chip', 'type' => 'text'],
    ['key' => 'about_heading', 'label' => 'About section heading', 'type' => 'text'],
    ['key' => 'form_name_placeholder', 'label' => 'Form: name placeholder', 'type' => 'text'],
    ['key' => 'form_email_placeholder', 'label' => 'Form: email placeholder', 'type' => 'text'],
    ['key' => 'form_msg_placeholder', 'label' => 'Form: message placeholder', 'type' => 'text'],
    ['key' => 'form_submit', 'label' => 'Form: submit button', 'type' => 'text'],
    ['key' => 'form_sent', 'label' => 'Form: sent confirmation', 'type' => 'text'],
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'upload_photo' && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        $mime = (string) (mime_content_type($_FILES['photo']['tmp_name']) ?: '');
        if (!isset($allowed[$mime])) {
            dashboard_flash('Only JPG, PNG, WebP or GIF images are allowed.', 'err');
        } else {
            $dir = __DIR__ . '/../assets/uploads';
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            $path = $dir . '/profile.' . $allowed[$mime];
            move_uploaded_file($_FILES['photo']['tmp_name'], $path);
            $rel = 'assets/uploads/profile.' . $allowed[$mime];
            $pdo->prepare('INSERT INTO settings (skey, svalue) VALUES (\'profile_image\', :v) ON DUPLICATE KEY UPDATE svalue = :v2')
                ->execute(['v' => $rel, 'v2' => $rel]);
            dashboard_flash('Profile image updated.');
        }
        dashboard_redirect('settings.php');
    }

    $stmt = $pdo->prepare('INSERT INTO settings (skey, svalue) VALUES (:k, :v) ON DUPLICATE KEY UPDATE svalue = :v2');
    foreach ($fields as $field) {
        $value = trim((string) ($_POST[$field['key']] ?? ''));
        $stmt->execute(['k' => $field['key'], 'v' => $value, 'v2' => $value]);
    }
    dashboard_flash('Site copy saved — refresh the public page to see it.');
    dashboard_redirect('settings.php');
}

$values = portfolio_settings_defaults();
foreach ($pdo->query('SELECT skey, svalue FROM settings') as $row) {
    $values[(string) $row['skey']] = (string) $row['svalue'];
}

dashboard_header('settings', 'settings');
?>
  <div class="panel clay" style="--c: rgba(255,255,255,.85)">
    <h2>Site copy <?= clay_icon('gear', 22) ?></h2>
    <p class="sub">All the words on the public page. Plain text — line breaks are kept.</p>

    <form method="post" enctype="multipart/form-data" class="btn-row" style="margin-bottom:1rem">
      <input type="hidden" name="action" value="upload_photo" />
      <input class="field file-field" style="width:auto;flex:1" type="file" name="photo" accept="image/*" required />
      <button class="btn clay clay-soft clay-press" style="--c: var(--lemon)" type="submit"><?= clay_icon('plus', 15) ?> Upload profile photo</button>
    </form>

    <form method="post" class="form-grid">
      <div class="btn-row">
        <button class="btn clay clay-soft clay-press" style="--c: var(--mint)" type="submit"><?= clay_icon('sparkle', 17) ?> Save copy</button>
      </div>
      <?php foreach ($fields as $field): ?>
      <?php if ($field['type'] === 'textarea'): ?>
      <label class="field-label"><?= e((string) $field['label']) ?>
        <textarea class="field" name="<?= e((string) $field['key']) ?>" rows="3"><?= e((string) $values[$field['key']]) ?></textarea>
      </label>
      <?php elseif ($field['type'] === 'icon'): ?>
      <label class="field-label"><?= e((string) $field['label']) ?>
        <select class="field" name="<?= e((string) $field['key']) ?>">
          <?php foreach (clay_icon_names() as $iconName): ?>
          <option value="<?= e($iconName) ?>"<?= $values[$field['key']] === $iconName ? ' selected' : '' ?>><?= e($iconName) ?></option>
          <?php endforeach ?>
        </select>
      </label>
      <?php elseif ($field['type'] === 'select'): ?>
      <label class="field-label"><?= e((string) $field['label']) ?>
        <select class="field" name="<?= e((string) $field['key']) ?>">
          <?php foreach ($field['options'] as $optVal => $optLabel): ?>
          <option value="<?= e((string) $optVal) ?>"<?= (string) $values[$field['key']] === (string) $optVal ? ' selected' : '' ?>><?= e((string) $optLabel) ?></option>
          <?php endforeach ?>
        </select>
      </label>
      <?php else: ?>
      <label class="field-label"><?= e((string) $field['label']) ?>
        <input class="field" type="text" name="<?= e((string) $field['key']) ?>" value="<?= e((string) $values[$field['key']]) ?>" />
      </label>
      <?php endif ?>
      <?php endforeach ?>
      <div class="btn-row">
        <button class="btn clay clay-soft clay-press" style="--c: var(--mint)" type="submit"><?= clay_icon('sparkle', 17) ?> Save copy</button>
      </div>
    </form>
  </div>
<?php
dashboard_footer();
