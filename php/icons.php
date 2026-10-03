<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

/**
 * The full hand-drawn icon set — 24x24, chunky rounded strokes, tinted via currentColor.
 *
 * @return array<string, string> icon name => inner SVG markup
 */
function clay_icon_all(): array
{
    static $icons = [
        'artist' => '<circle cx="12" cy="13.5" r="7"/><circle cx="9.5" cy="13" r="0.9" fill="currentColor" stroke="none"/><circle cx="14.5" cy="13" r="0.9" fill="currentColor" stroke="none"/><path d="M9.3 16.3c1.6 1.5 3.8 1.5 5.4 0"/><path d="M5.8 9.3C6.5 5.8 9.2 4 12.2 4.3c3 .3 5.2 2.1 5.9 5-1.8 1.1-3.8 1.4-6.1 1.4-2.2 0-4.4-.4-6.2-1.4z"/><circle cx="17.6" cy="4.4" r="0.8" fill="currentColor" stroke="none"/>',
        'bubble' => '<circle cx="9.5" cy="11" r="4.5"/><circle cx="17" cy="8" r="2.8"/><circle cx="15.5" cy="16.5" r="2.2"/>',
        'search' => '<circle cx="11" cy="11" r="6.5"/><path d="M16 16 21 21"/>',
        'send' => '<path d="M22 2 11 13"/><path d="M22 2 15 22 11 13 2 9z"/>',
        'mail' => '<rect x="3.5" y="5.5" width="17" height="13" rx="3"/><path d="m4.5 8.5 7.5 5 7.5-5"/>',
        'sparkle' => '<path d="M12 3.5c.7 4 2 5.3 6 6-4 .7-5.3 2-6 6-.7-4-2-5.3-6-6 4-.7 5.3-2 6-6z"/><circle cx="18.5" cy="17.5" r="1" fill="currentColor" stroke="none"/>',
        'party' => '<path d="M10 7.5c.6 3.4 1.7 4.5 5 5-3.3.5-4.4 1.6-5 5-.6-3.4-1.7-4.5-5-5 3.3-.5 4.4-1.6 5-5z"/><circle cx="17.5" cy="6.5" r="1.1" fill="currentColor" stroke="none"/><circle cx="19.5" cy="12" r="0.9" fill="currentColor" stroke="none"/><circle cx="15" cy="16.5" r="0.9" fill="currentColor" stroke="none"/><path d="M4 19.5h5"/>',
        'coffee' => '<path d="M5 9.5h11v5.5a4.5 4.5 0 0 1-4.5 4.5h-2A4.5 4.5 0 0 1 5 15z"/><path d="M16 10.5h1.4a2.6 2.6 0 0 1 0 5.2H16"/><path d="M8.5 3.8v1.7M12.5 3.8v1.7"/>',
        'heart' => '<path d="M12 20.5C7.3 16.8 4 13.8 4 10.4A4.4 4.4 0 0 1 12 7.6a4.4 4.4 0 0 1 8 2.8c0 3.4-3.3 6.4-8 10.1z"/>',
        'flame' => '<path d="M12 3.5c.4 3 4.5 4.7 4.5 8.5a4.5 4.5 0 0 1-9 0c0-1.9 1-3.3 2.1-4.4.1 1.2.7 2 1.7 2.6-.2-1.9.2-4.5.7-6.7z"/>',
        'toolbox' => '<rect x="3.5" y="8" width="17" height="11" rx="3"/><path d="M9.5 8V6.7a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2V8"/><path d="M3.5 13h17"/>',
        'leaf' => '<path d="M12 21v-8"/><path d="M12 13C7.5 13 5 9.8 5 5.5c4.3 0 7 3.3 7 7.5z"/><path d="M12 10.5C12 6.2 15 4 19 4c0 4.2-2.7 6.5-7 6.5z"/>',
        'sliders' => '<path d="M6 4v5M6 13.5V20M12 4v9M12 17.5V20M18 4v2M18 10.5V20"/><circle cx="6" cy="11" r="2"/><circle cx="12" cy="15" r="2"/><circle cx="18" cy="8" r="2"/>',
        'frog' => '<ellipse cx="12" cy="14.5" rx="8.5" ry="6"/><circle cx="8" cy="8" r="2.8"/><circle cx="16" cy="8" r="2.8"/><circle cx="8" cy="8.3" r="0.8" fill="currentColor" stroke="none"/><circle cx="16" cy="8.3" r="0.8" fill="currentColor" stroke="none"/><path d="M9 15.5c1.8 1.6 4.2 1.6 6 0"/><circle cx="10.8" cy="12.6" r="0.55" fill="currentColor" stroke="none"/><circle cx="13.2" cy="12.6" r="0.55" fill="currentColor" stroke="none"/>',
        'planet' => '<circle cx="12" cy="12" r="5.8"/><ellipse cx="12" cy="12" rx="10" ry="3.6" transform="rotate(-18 12 12)"/>',
        'vue' => '<path d="M3 4.5h4.2L12 12.6 16.8 4.5H21L12 19.8z"/><path d="M8.6 4.5 12 10.2l3.4-5.7"/>',
        'code' => '<path d="M8.5 6.5 3.5 12l5 5.5"/><path d="M15.5 6.5 20.5 12l-5 5.5"/>',
        'palette' => '<path d="M12 21.5a9.5 9.5 0 1 1 9.5-9.5c0 1.8-1.4 3-3.2 3h-2.1a2 2 0 0 0-1.5 3.3c.8 1 .3 3.2-2.7 3.2z"/><circle cx="7.8" cy="11" r="1" fill="currentColor" stroke="none"/><circle cx="10.3" cy="7.5" r="1" fill="currentColor" stroke="none"/><circle cx="14.6" cy="7.5" r="1" fill="currentColor" stroke="none"/><circle cx="16.8" cy="11" r="1" fill="currentColor" stroke="none"/>',
        'hexagon' => '<path d="M12 2.8 20.2 7.4v9.2L12 21.2 3.8 16.6V7.4z"/>',
        'bolt' => '<path d="M13 2.8 5.2 13.3h5.4L11 21.2l7.8-10.5h-5.4z"/>',
        'cube' => '<path d="M12 2.8 20.3 7.3v9.4L12 21.2 3.7 16.7V7.3z"/><path d="M3.7 7.3 12 11.8l8.3-4.5"/><path d="M12 11.8v9.4"/>',
        'pen' => '<path d="M4 20l1.2-4.6L16.6 4a2.15 2.15 0 0 1 3 3L8.2 18.4z"/><path d="M14.6 6.1l3 3"/>',
        'wind' => '<path d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H3"/><path d="M9.6 4.6A2 2 0 1 1 11 8H3"/><path d="M12.6 19.4A2 2 0 1 0 14 16H3"/>',
        'access' => '<circle cx="12" cy="5.3" r="2"/><path d="M5.5 9.3c2.1 1 4.2 1.5 6.5 1.5s4.4-.5 6.5-1.5"/><path d="M12 10.8v2.7"/><path d="M12 13.5l-2.8 6M12 13.5l2.8 6"/>',
        'volume' => '<path d="M4 9.5h3.3L12 5.7v12.6l-4.7-3.8H4z"/><path d="M15.5 9.2a4.3 4.3 0 0 1 0 5.6"/><path d="M18 6.8a7.7 7.7 0 0 1 0 10.4"/>',
        'github' => '<path fill="currentColor" stroke="none" d="M12 .5C5.65.5.5 5.65.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56 0-.28-.01-1.02-.02-2-3.2.7-3.88-1.54-3.88-1.54-.52-1.33-1.28-1.68-1.28-1.68-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.76 2.7 1.25 3.36.96.1-.75.4-1.25.72-1.54-2.55-.29-5.24-1.28-5.24-5.68 0-1.26.45-2.28 1.18-3.09-.12-.29-.51-1.46.11-3.05 0 0 .96-.31 3.15 1.18a10.9 10.9 0 0 1 5.74 0c2.19-1.49 3.15-1.18 3.15-1.18.62 1.59.23 2.76.11 3.05.73.81 1.18 1.83 1.18 3.09 0 4.41-2.69 5.38-5.25 5.67.41.35.77 1.05.77 2.12 0 1.53-.01 2.76-.01 3.14 0 .3.21.67.8.55A11.51 11.51 0 0 0 23.5 12C23.5 5.65 18.35.5 12 .5z"/>',
        'x' => '<path d="M5 4.5 19 19.5"/><path d="M19 4.5 5 19.5"/>',
        'dribbble' => '<circle cx="12" cy="12" r="9"/><path d="M18.6 5.4C15.3 8.9 11 10 4.4 10.4"/><path d="M20.8 12.9c-6-1.3-11 .9-14.7 5.7"/><path d="M9.2 3.3c3.9 5.4 5.4 8.5 7.2 16"/>',
        'gear' => '<circle cx="12" cy="12" r="3.4"/><path d="M12 2.8v2.6M12 18.6v2.6M2.8 12h2.6M18.6 12h2.6M5.5 5.5l1.8 1.8M16.7 16.7l1.8 1.8M18.5 5.5l-1.8 1.8M7.3 16.7l-1.8 1.8"/>',
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'trash' => '<path d="M4.5 6.5h15"/><path d="M9.5 6.5V5.2a1.7 1.7 0 0 1 1.7-1.7h1.6a1.7 1.7 0 0 1 1.7 1.7v1.3"/><path d="M6.8 6.5l.9 13.1a1.6 1.6 0 0 0 1.6 1.5h5.4a1.6 1.6 0 0 0 1.6-1.5l.9-13.1"/><path d="M10.2 10.5v6M13.8 10.5v6"/>',
        'home' => '<path d="M4.5 10.7 12 4.2l7.5 6.5"/><path d="M6.5 9.2V20h11V9.2"/><path d="M10 20v-5h4v5"/>',
        'star' => '<path d="m12 3.8 2.5 5.1 5.6.8-4 4 .9 5.6-5-2.7-5 2.7.9-5.6-4-4 5.6-.8z"/>',
        'logout' => '<path d="M9.5 4.5H6A1.5 1.5 0 0 0 4.5 6v12A1.5 1.5 0 0 0 6 19.5h3.5"/><path d="m15 8 4 4-4 4"/><path d="M19 12H9.5"/>',
        'eye' => '<path d="M2.8 12S6.2 5.8 12 5.8 21.2 12 21.2 12 17.8 18.2 12 18.2 2.8 12 2.8 12z"/><circle cx="12" cy="12" r="2.8"/>',
        'back' => '<path d="M19 12H5.5"/><path d="m11 6-6 6 6 6"/>',
    ];

    return $icons;
}

/**
 * Render one of the hand-drawn clay-style icons as inline SVG.
 */
function clay_icon(string $name, int $size = 20, string $class = ''): string
{
    $icons = clay_icon_all();

    $paths = $icons[$name] ?? '';
    if ($paths === '') {
        return '';
    }

    $classes = array_filter(['clay-icon', $class]);

    return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor"'
        . ' stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"'
        . ' class="' . e(implode(' ', $classes)) . '">' . $paths . '</svg>';
}

/**
 * All available icon names — used for the dashboard icon pickers.
 *
 * @return string[]
 */
function clay_icon_names(): array
{
    return array_keys(clay_icon_all());
}
