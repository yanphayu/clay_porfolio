<?php

declare(strict_types=1);

require_once __DIR__ . '/data.php';

/** Default site copy — used as seed data and as fallback when MySQL is off. */
function portfolio_settings_defaults(): array
{
    return [
        'badge_text' => 'open for freelance — summer 2026',
        'badge_text_visible' => '1',
        'hero_line1' => 'I shape code',
        'hero_like' => 'like',
        'hero_highlight' => 'clay',
        'hero_name' => 'Momo',
        'hero_lead' => "Hi! I'm {name} — a creative developer who kneads rounded corners, pastel palettes and buttery animations into every project. Currently cooking up delightful web toys from a tiny, very cozy studio.",
        'about_p1' => "I believe software should feel like a toy you can't put down: soft to look at, springy to touch, and honest about what it does.",
        'about_p2' => 'By day I build design systems and playful interfaces. By night I make generative art, imperfect ceramics, and aggressively average sourdough. If it can be rounded — I will round it.',
        'contact_heading' => "Let's squish something together",
        'contact_blurb' => 'Got a project that needs a little softness? A wildly overdue redesign? Send a pigeon — or, you know, this form.',
        'footer_text' => 'by Momo — no pixels were fired in a real kiln.',
        'profile_image' => '',
        'marquee_tilt' => '-1.4',
        'theme' => 'default',
        'site_title' => 'Momo Kato · creative developer',
        'meta_description' => 'Momo Kato — creative developer shaping code like clay. A claymorphic portfolio.',
        'nav_logo' => 'momo.dev',
        'nav_cta' => 'Say hi',
        'hero_cta_work' => 'See my work',
        'hero_cta_contact' => 'Get in touch',
        'hero_cta_work_visible' => '1',
        'hero_cta_contact_visible' => '1',
        'hero_chip1' => 'Vue',
        'hero_chip2' => 'CSS',
        'hero_chip3' => 'refills',
        'hero_chip4' => 'vibes',
        'hero_chip1_icon' => 'vue',
        'hero_chip2_icon' => 'palette',
        'hero_chip3_icon' => 'coffee',
        'hero_chip4_icon' => 'sparkle',
        'hero_chip1_visible' => '1',
        'hero_chip2_visible' => '1',
        'hero_chip3_visible' => '1',
        'hero_chip4_visible' => '1',
        'works_chip' => 'fresh out of the kiln',
        'works_heading' => 'Selected works',
        'works_sub' => "Little worlds I've built — each one squish-tested on real humans before shipping.",
        'skills_chip' => 'the toolbox',
        'skills_heading' => 'Things I knead daily',
        'skills_sub' => 'Tools I\'ve poked, squished and shipped with — arranged in no particular order, except vibes.',
        'about_chip' => 'behind the blobs',
        'about_heading' => 'Nice to squish you',
        'form_name_placeholder' => 'Your name',
        'form_email_placeholder' => 'you@somewhere.nice',
        'form_msg_placeholder' => 'Tell me everything…',
        'form_submit' => 'Send it flying',
        'form_sent' => 'Squished into the kiln!',
    ];
}

/** Flat-file demo content (php/data.php), shaped exactly like the DB loader output. */
function portfolio_fallback_content(): array
{
    require __DIR__ . '/data.php';

    foreach ($projects as $i => &$p) {
        $p['sort_order'] = $i;
        $p['image'] = $p['image'] ?? '';
    }
    unset($p);

    foreach ($stats as $i => &$s) {
        $s = ['num' => $s['num'], 'lbl' => $s['lbl'], 'color' => $s['c'], 'sort_order' => $i];
    }
    unset($s);

    foreach ($socials as $i => &$s) {
        $s = [
            'label' => $s['label'],
            'icon' => $s['icon'],
            'color' => $s['c'],
            'url' => $s['url'],
            'size' => $s['size'],
            'sort_order' => $i,
        ];
    }
    unset($s);

    return [
        'projects' => $projects,
        'skills' => $skills,
        'stats' => $stats,
        'socials' => $socials,
        'marquee' => $marqueeItems,
        'settings' => portfolio_settings_defaults(),
    ];
}

/** All site content: from MySQL when available, flat-file fallback otherwise. */
function portfolio_content(): array
{
    $fallback = portfolio_fallback_content();
    $pdo = portfolio_db();
    if (!$pdo) {
        return $fallback;
    }

    try {
        // Auto-migrate databases created before the image column existed.
        try {
            $pdo->query('SELECT image FROM projects LIMIT 1');
        } catch (Throwable) {
            $pdo->exec("ALTER TABLE projects ADD COLUMN image VARCHAR(255) NOT NULL DEFAULT '' AFTER link");
        }

        $projects = [];
        foreach ($pdo->query('SELECT * FROM projects ORDER BY sort_order, id') as $row) {
            $projects[] = [
                'icon' => (string) $row['icon'],
                'title' => (string) $row['title'],
                'desc' => (string) $row['description'],
                'tags' => array_values(array_filter(array_map('trim', explode(',', (string) $row['tags'])))),
                'color' => (string) $row['color'],
                'link' => (string) $row['link'],
                'image' => (string) ($row['image'] ?? ''),
                'sort_order' => (int) $row['sort_order'],
            ];
        }

        $skills = [];
        foreach ($pdo->query('SELECT * FROM skills ORDER BY sort_order, id') as $row) {
            $skills[] = [(string) $row['label'], (string) $row['icon']];
        }

        $stats = [];
        foreach ($pdo->query('SELECT * FROM stats ORDER BY sort_order, id') as $row) {
            $stats[] = ['num' => (string) $row['num'], 'lbl' => (string) $row['lbl'], 'color' => (string) $row['color']];
        }

        $socials = [];
        foreach ($pdo->query('SELECT * FROM socials ORDER BY sort_order, id') as $row) {
            $socials[] = [
                'label' => (string) $row['label'],
                'icon' => (string) $row['icon'],
                'color' => (string) $row['color'],
                'url' => (string) $row['url'],
                'size' => 22,
            ];
        }

        $marquee = [];
        foreach ($pdo->query('SELECT * FROM marquee_items ORDER BY sort_order, id') as $row) {
            $marquee[] = (string) $row['label'];
        }

        $settings = portfolio_settings_defaults();
        foreach ($pdo->query('SELECT skey, svalue FROM settings') as $row) {
            $settings[(string) $row['skey']] = (string) $row['svalue'];
        }

        return [
            'projects' => $projects,
            'skills' => $skills,
            'stats' => $stats,
            'socials' => $socials,
            'marquee' => $marquee,
            'settings' => $settings,
        ];
    } catch (Throwable) {
        return $fallback;
    }
}

/** Are the tables there and readable? */
function portfolio_db_installed(): bool
{
    $pdo = portfolio_db();
    if (!$pdo) {
        return false;
    }
    try {
        $pdo->query('SELECT 1 FROM projects LIMIT 1');
        return true;
    } catch (Throwable) {
        return false;
    }
}

/** Create the database + tables and seed them with the demo content (idempotent). */
function portfolio_install(): void
{
    // Connect server-level first so the database itself can be created.
    $server = new PDO(
        sprintf('mysql:host=%s;port=%d;charset=utf8mb4', DB_HOST, DB_PORT),
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );
    $safeName = str_replace('`', '', DB_NAME);
    $server->exec("CREATE DATABASE IF NOT EXISTS `$safeName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    unset($server);

    $pdo = portfolio_db(true);
    if (!$pdo) {
        throw new RuntimeException('Could not connect to the database after creating it.');
    }

    $statements = [
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS projects (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            icon VARCHAR(32) NOT NULL DEFAULT 'leaf',
            title VARCHAR(120) NOT NULL,
            description TEXT NOT NULL,
            tags VARCHAR(255) NOT NULL DEFAULT '',
            color VARCHAR(60) NOT NULL DEFAULT 'var(--lav)',
            link VARCHAR(255) NOT NULL DEFAULT '#work',
            image VARCHAR(255) NOT NULL DEFAULT '',
            sort_order INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS skills (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(80) NOT NULL,
            icon VARCHAR(32) NOT NULL DEFAULT 'code',
            sort_order INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS stats (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            num VARCHAR(20) NOT NULL,
            lbl VARCHAR(120) NOT NULL,
            color VARCHAR(60) NOT NULL DEFAULT 'var(--lav)',
            sort_order INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS socials (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(60) NOT NULL,
            icon VARCHAR(32) NOT NULL DEFAULT 'heart',
            color VARCHAR(60) NOT NULL DEFAULT 'var(--lav)',
            url VARCHAR(255) NOT NULL DEFAULT '#',
            sort_order INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS marquee_items (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            label VARCHAR(80) NOT NULL,
            sort_order INT NOT NULL DEFAULT 0
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS messages (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(120) NOT NULL,
            email VARCHAR(160) NOT NULL,
            msg TEXT NOT NULL,
            is_read TINYINT(1) NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS logs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            level VARCHAR(16) NOT NULL DEFAULT 'info',
            message VARCHAR(500) NOT NULL,
            context VARCHAR(255) NOT NULL DEFAULT '',
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
        <<<'SQL'
        CREATE TABLE IF NOT EXISTS settings (
            skey VARCHAR(60) PRIMARY KEY,
            svalue TEXT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        SQL,
    ];
    foreach ($statements as $sql) {
        $pdo->exec($sql);
    }

    // Seed only tables that are still empty, so re-installing never wipes edits.
    $seed = portfolio_fallback_content();

    $insert = static function (string $table, array $row) use ($pdo): void {
        $cols = array_keys($row);
        $marks = implode(', ', array_map(static fn ($c) => ":$c", $cols));
        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $cols) . ') VALUES (' . $marks . ')';
        $pdo->prepare($sql)->execute($row);
    };

    $isEmpty = static function (string $table) use ($pdo): bool {
        return (int) $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn() === 0;
    };

    if ($isEmpty('projects')) {
        foreach ($seed['projects'] as $p) {
            $insert('projects', [
                'icon' => $p['icon'],
                'title' => $p['title'],
                'description' => $p['desc'],
                'tags' => implode(', ', $p['tags']),
                'color' => $p['color'],
                'link' => $p['link'],
                'sort_order' => $p['sort_order'],
            ]);
        }
    }

    if ($isEmpty('skills')) {
        foreach ($seed['skills'] as $i => [$label, $icon]) {
            $insert('skills', ['label' => $label, 'icon' => $icon, 'sort_order' => $i]);
        }
    }

    if ($isEmpty('stats')) {
        foreach ($seed['stats'] as $i => $s) {
            $insert('stats', ['num' => $s['num'], 'lbl' => $s['lbl'], 'color' => $s['color'], 'sort_order' => $i]);
        }
    }

    if ($isEmpty('socials')) {
        foreach ($seed['socials'] as $i => $s) {
            $insert('socials', [
                'label' => $s['label'],
                'icon' => $s['icon'],
                'color' => $s['color'],
                'url' => $s['url'],
                'sort_order' => $i,
            ]);
        }
    }

    if ($isEmpty('marquee_items')) {
        foreach ($seed['marquee'] as $i => $label) {
            $insert('marquee_items', ['label' => $label, 'sort_order' => $i]);
        }
    }

    if ($isEmpty('settings')) {
        foreach (portfolio_settings_defaults() as $key => $value) {
            $insert('settings', ['skey' => $key, 'svalue' => $value]);
        }
    }
}

/** Truncate to a column length — works with or without the mbstring extension. */
function portfolio_truncate(string $value, int $max): string
{
    return function_exists('mb_substr') ? mb_substr($value, 0, $max) : substr($value, 0, $max);
}

/** Write an entry to the dashboard log. No-op when MySQL is unavailable. */
function portfolio_log(string $message, string $level = 'info', string $context = ''): void
{
    $pdo = portfolio_db();
    if (!$pdo) {
        return;
    }
    try {
        $pdo->prepare('INSERT INTO logs (level, message, context) VALUES (?, ?, ?)')->execute([
            portfolio_truncate($level, 16),
            portfolio_truncate($message, 500),
            portfolio_truncate($context, 255),
        ]);
    } catch (Throwable) {
        // Never let logging break the site.
    }
}

/** Store a contact-form submission. Returns false when MySQL is unavailable. */
function portfolio_save_message(string $name, string $email, string $msg): bool
{
    $pdo = portfolio_db();
    if (!$pdo) {
        return false;
    }
    try {
        $pdo->prepare('INSERT INTO messages (name, email, msg) VALUES (?, ?, ?)')->execute([
            portfolio_truncate($name, 120),
            portfolio_truncate($email, 160),
            portfolio_truncate($msg, 10000),
        ]);
        return true;
    } catch (Throwable) {
        return false;
    }
}
