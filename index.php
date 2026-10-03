<?php

declare(strict_types=1);

require_once __DIR__ . '/php/helpers.php';
require_once __DIR__ . '/php/icons.php';
require_once __DIR__ . '/php/config.php';
require_once __DIR__ . '/php/db.php';
require_once __DIR__ . '/php/content.php';

// Store contact-form submissions in MySQL when reachable (no-JS fallback path).
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && isset($_POST['name'], $_POST['email'], $_POST['msg'])) {
    portfolio_save_message(
        trim((string) $_POST['name']),
        trim((string) $_POST['email']),
        trim((string) $_POST['msg']),
    );
    portfolio_log('Contact form submission from ' . trim((string) $_POST['name']), 'info', trim((string) $_POST['email']));
    $uri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
    header('Location: ' . $uri . '#contact');
    exit;
}

// All content comes from MySQL (managed at /dashboard); falls back to php/data.php.
$content = portfolio_content();
$projects = $content['projects'];
$skills = $content['skills'];
$stats = $content['stats'];
$socials = $content['socials'];
$marqueeItems = $content['marquee'];
$S = $content['settings'];

?>
<!doctype html>
<html lang="en" data-theme="<?= e((string) ($S['theme'] ?? 'default')) ?>">
  <head>
    <meta charset="UTF-8" />
    <link rel="icon" type="image/svg+xml" href="assets/favicon.svg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?= e((string) $S['meta_description']) ?>" />
    <title><?= e((string) $S['site_title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito:wght@500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="backdrop" aria-hidden="true">
      <div class="blob blob-1"></div>
      <div class="blob blob-2"></div>
      <div class="blob blob-3"></div>
    </div>

    <header class="nav">
      <nav class="nav-pill clay clay-soft" id="nav-pill" aria-label="Main">
        <a class="nav-logo" href="#top"><?= e((string) $S['nav_logo']) ?> <?= clay_icon('bubble', 17) ?></a>
        <div class="nav-links">
          <a href="#work">Work</a>
          <a href="#skills">Skills</a>
          <a href="#about">About</a>
        </div>
        <a class="clay clay-soft clay-press nav-cta" style="--c: var(--pink)" href="#contact"><?= e((string) $S['nav_cta']) ?> <?= clay_icon('send', 15) ?></a>
      </nav>
    </header>

    <main id="top">
      <!-- Hero -->
      <section class="wrap hero">
        <div class="hero-copy">
          <?php if ((string) ($S['badge_text_visible'] ?? '1') !== '0'): ?><div class="reveal">
            <span class="chip clay clay-soft hero-badge"><i class="dot"></i> <?= e((string) $S['badge_text']) ?></span>
          </div><?php endif ?>
          <h1 class="reveal" style="--rd: 0.08s">
            <?= e((string) $S['hero_line1']) ?><br />
            <?= e((string) $S['hero_like']) ?> <span class="hl clay clay-soft" style="--c: var(--lemon)"><?= e((string) $S['hero_highlight']) ?></span> <?= clay_icon('bubble', 34) ?>
          </h1>
          <p class="lead reveal" style="--rd: 0.16s">
            <?= nl2br(e(str_replace('{name}', (string) $S['hero_name'], (string) $S['hero_lead']))) ?>
          </p>
          <div class="hero-ctas reveal" style="--rd: 0.24s">
            <?php if ((string) ($S['hero_cta_work_visible'] ?? '1') !== '0'): ?><a class="btn clay clay-soft clay-press" style="--c: var(--lemon)" href="#work"><?= e((string) $S['hero_cta_work']) ?> <?= clay_icon('search', 17) ?></a><?php endif ?>
            <?php if ((string) ($S['hero_cta_contact_visible'] ?? '1') !== '0'): ?><a class="btn clay clay-soft clay-press" style="--c: rgba(255, 255, 255, 0.85)" href="#contact"><?= e((string) $S['hero_cta_contact']) ?> <?= clay_icon('mail', 17) ?></a><?php endif ?>
          </div>
        </div>

        <div class="avatar-stage reveal" style="--rd: 0.2s">
          <div class="avatar-card clay" style="--c: var(--pink)">
            <div class="avatar-face clay-inset" style="--c: #fff0f8"><?php if ((string) $S['profile_image'] !== ''): ?><img src="<?= e((string) $S['profile_image']) ?>" alt="<?= e((string) $S['hero_name']) ?>" /><?php else: ?><?= clay_icon('artist', 92) ?><?php endif ?></div>
          </div>
          <?php if ((string) ($S['hero_chip1_visible'] ?? '1') !== '0'): ?><span class="float-chip clay clay-soft fc-1" style="--c: var(--mint)"><?= clay_icon((string) ($S['hero_chip1_icon'] ?? 'vue'), 15) ?> <?= e((string) $S['hero_chip1']) ?></span><?php endif ?>
          <?php if ((string) ($S['hero_chip2_visible'] ?? '1') !== '0'): ?><span class="float-chip clay clay-soft fc-2" style="--c: var(--sky); --d: 0.7s; --tilt: 3deg"><?= clay_icon((string) ($S['hero_chip2_icon'] ?? 'palette'), 15) ?> <?= e((string) $S['hero_chip2']) ?></span><?php endif ?>
          <?php if ((string) ($S['hero_chip3_visible'] ?? '1') !== '0'): ?><span class="float-chip clay clay-soft fc-3" style="--c: var(--lemon); --d: 1.2s; --tilt: -3deg"><?= clay_icon((string) ($S['hero_chip3_icon'] ?? 'coffee'), 15) ?> <?= e((string) $S['hero_chip3']) ?></span><?php endif ?>
          <?php if ((string) ($S['hero_chip4_visible'] ?? '1') !== '0'): ?><span class="float-chip clay clay-soft fc-4" style="--c: var(--peach); --d: 1.8s; --tilt: 2deg"><?= clay_icon((string) ($S['hero_chip4_icon'] ?? 'sparkle'), 15) ?> <?= e((string) $S['hero_chip4']) ?></span><?php endif ?>
        </div>
      </section>

      <!-- Marquee -->
      <div class="marquee clay" aria-hidden="true" style="transform: rotate(<?= e((string) $S['marquee_tilt']) ?>deg)">
        <div class="marquee-track">
          <?php for ($copy = 0; $copy < 2; $copy++): ?>
          <div class="m-group">
            <?php foreach ($marqueeItems as $item): ?>
            <span><?= e($item) ?></span><i>✦</i>
            <?php endforeach ?>
          </div>
          <?php endfor ?>
        </div>
      </div>

      <!-- Projects -->
      <section id="work" class="wrap">
        <div class="sec-head reveal">
          <span class="chip clay clay-soft" style="--c: var(--peach)"><?= clay_icon('flame', 15) ?> <?= e((string) $S['works_chip']) ?></span>
          <h2><?= e((string) $S['works_heading']) ?></h2>
          <p><?= e((string) $S['works_sub']) ?></p>
        </div>
        <div class="grid">
          <?php foreach ($projects as $i => $project): ?>
          <div class="reveal" style="--rd: <?= round($i * 0.08, 2) ?>s">
            <article class="clay clay-soft project" style="--c: <?= e($project['color']) ?>">
              <div class="tile clay-inset" style="--c: <?= e($project['color']) ?>"><?php if (!empty($project['image'])): ?><img src="<?= e($project['image']) ?>" alt="<?= e($project['title']) ?>" style="width:100%;height:100%;object-fit:cover;display:block" /><?php else: ?><?= clay_icon($project['icon'], 42) ?><?php endif ?></div>
              <h3><?= e($project['title']) ?></h3>
              <p><?= e($project['desc']) ?></p>
              <div class="tags">
                <?php foreach ($project['tags'] as $tag): ?><span class="tag"><?= e($tag) ?></span><?php endforeach ?>
              </div>
              <a class="peek" href="<?= e($project['link']) ?>">Peek inside →</a>
            </article>
          </div>
          <?php endforeach ?>
        </div>
      </section>

      <!-- Skills -->
      <section id="skills" class="wrap">
        <div class="sec-head reveal">
          <span class="chip clay clay-soft" style="--c: var(--mint)"><?= clay_icon('toolbox', 15) ?> <?= e((string) $S['skills_chip']) ?></span>
          <h2><?= e((string) $S['skills_heading']) ?></h2>
          <p><?= e((string) $S['skills_sub']) ?></p>
        </div>
        <div class="skills-tray clay reveal">
          <?php foreach ($skills as $i => [$label, $icon]): ?>
          <span class="skill clay clay-soft" style="--c: <?= e($palette[$i % count($palette)]) ?>; --tilt: <?= $i % 2 ? '1.6deg' : '-1.6deg' ?>">
            <?= clay_icon($icon, 18) ?><?= e($label) ?>
          </span>
          <?php endforeach ?>
        </div>
      </section>

      <!-- About -->
      <section id="about" class="wrap">
        <div class="sec-head reveal">
          <span class="chip clay clay-soft" style="--c: var(--lav)"><?= clay_icon('bubble', 15) ?> <?= e((string) $S['about_chip']) ?></span>
          <h2><?= e((string) $S['about_heading']) ?></h2>
        </div>
        <div class="about-card clay reveal">
          <p><?= nl2br(e((string) $S['about_p1'])) ?></p>
          <p><?= nl2br(e((string) $S['about_p2'])) ?></p>
          <div class="stats">
            <?php foreach ($stats as $i => $stat): ?>
            <div class="stat clay clay-soft" style="--c: <?= e((string) $stat['color']) ?>; --rd: <?= round($i * 0.06, 2) ?>s">
              <span class="num"><?= e($stat['num']) ?></span>
              <span class="lbl"><?= e($stat['lbl']) ?></span>
            </div>
            <?php endforeach ?>
          </div>
        </div>
      </section>

      <!-- Contact -->
      <section id="contact" class="wrap">
        <div class="contact-card clay reveal">
          <div>
            <h2><?= e((string) $S['contact_heading']) ?> <?= clay_icon('sparkle', 24) ?></h2>
            <p class="blurb">
              <?= e((string) $S['contact_blurb']) ?>
            </p>
            <div class="socials">
              <?php foreach ($socials as $social): ?>
              <a class="social clay clay-soft clay-press" style="--c: <?= e((string) $social['color']) ?>" href="<?= e((string) $social['url']) ?>" aria-label="<?= e((string) $social['label']) ?>">
                <?= clay_icon($social['icon'], (int) $social['size']) ?>
              </a>
              <?php endforeach ?>
            </div>
          </div>
          <form method="post" action="#contact">
            <input class="field" type="text" name="name" placeholder="<?= e((string) $S['form_name_placeholder']) ?>" required />
            <input class="field" type="email" name="email" placeholder="<?= e((string) $S['form_email_placeholder']) ?>" required />
            <textarea class="field" name="msg" placeholder="<?= e((string) $S['form_msg_placeholder']) ?>" required></textarea>
            <button class="btn clay clay-soft clay-press submit" type="submit">
              <span class="when-idle"><?= clay_icon('send', 17) ?> <?= e((string) $S['form_submit']) ?></span>
              <span class="when-sent"><?= clay_icon('party', 17) ?> <?= e((string) $S['form_sent']) ?></span>
            </button>
          </form>
        </div>
      </section>
    </main>

    <footer>
      <p>Hand-molded with <?= clay_icon('heart', 14, 'heart') ?> <?= e((string) $S['footer_text']) ?></p>
    </footer>

    <script src="assets/js/main.js"></script>
  </body>
</html>
