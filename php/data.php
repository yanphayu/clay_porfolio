<?php

declare(strict_types=1);

/**
 * All page content lives here — edit this file to make the portfolio yours.
 */

$projects = [
    [
        'icon' => 'leaf',
        'title' => 'Sproutly',
        'desc' => 'A plant-care companion that kindly nags you when your ferns are thirsty. 12k happy plants and counting.',
        'tags' => ['Vue', 'PWA', 'Web Push'],
        'color' => 'var(--mint)',
        'link' => '#work',
    ],
    [
        'icon' => 'sliders',
        'title' => 'Lo-Fi Machine',
        'desc' => 'A tiny beat maker with squishy pads, tape wobble and a "rainy Tokyo" reverb preset. Headphones on.',
        'tags' => ['Web Audio', 'Canvas'],
        'color' => 'var(--lemon)',
        'link' => '#work',
    ],
    [
        'icon' => 'frog',
        'title' => 'Ribbit',
        'desc' => 'A habit tracker where your streak grows an actual frog. Miss a day and, well, it rains.',
        'tags' => ['Nuxt', 'SVG', 'GSAP'],
        'color' => 'var(--peach)',
        'link' => '#work',
    ],
    [
        'icon' => 'planet',
        'title' => 'Orbit',
        'desc' => 'A pomodoro timer for space cadets — every focus session launches a tiny rocket across your screen.',
        'tags' => ['TypeScript', 'Vite'],
        'color' => 'var(--lav)',
        'link' => '#work',
    ],
];

$skills = [
    ['Vue', 'vue'],
    ['TypeScript', 'code'],
    ['CSS sorcery', 'palette'],
    ['Node', 'hexagon'],
    ['Vite', 'bolt'],
    ['Three.js', 'cube'],
    ['Figma', 'pen'],
    ['GSAP', 'wind'],
    ['Accessibility', 'access'],
    ['Web Audio', 'volume'],
];

$marqueeItems = [
    'creative developer',
    'ui tinkerer',
    'motion lover',
    'css chef',
    'pixel potter',
    'accessibility nerd',
    'emoji enthusiast',
];

$stats = [
    ['num' => '3+', 'lbl' => 'years shaping pixels', 'c' => 'var(--lav)'],
    ['num' => '24', 'lbl' => 'projects out of the kiln', 'c' => 'var(--pink)'],
    ['num' => '1.2k', 'lbl' => 'cups of coffee', 'c' => 'var(--lemon)'],
    ['num' => '7', 'lbl' => 'rubber ducks consulted', 'c' => 'var(--mint)'],
];

$socials = [
    ['icon' => 'github', 'label' => 'GitHub', 'c' => 'var(--lav)', 'url' => '#contact', 'size' => 24],
    ['icon' => 'x', 'label' => 'X / Twitter', 'c' => 'var(--sky)', 'url' => '#contact', 'size' => 22],
    ['icon' => 'dribbble', 'label' => 'Dribbble', 'c' => 'var(--pink)', 'url' => '#contact', 'size' => 22],
    ['icon' => 'mail', 'label' => 'Email', 'c' => 'var(--mint)', 'url' => '#contact', 'size' => 22],
];

$palette = ['var(--lav)', 'var(--peach)', 'var(--mint)', 'var(--pink)', 'var(--lemon)', 'var(--sky)'];
