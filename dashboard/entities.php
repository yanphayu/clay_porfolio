<?php

declare(strict_types=1);

/**
 * Everything the dashboard can manage, described as data.
 * 'table' is the MySQL table; 'fields' drive the forms and saving.
 */
return [
    'projects' => [
        'table' => 'projects',
        'title' => 'Projects',
        'label' => 'Project',
        'icon' => 'cube',
        'subtitle' => 'The cards in the “Selected works” section.',
        'list' => [
            ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon'],
            ['key' => 'image', 'label' => 'Image', 'type' => 'image'],
            ['key' => 'title', 'label' => 'Title'],
            ['key' => 'sort_order', 'label' => 'Order'],
        ],
        'fields' => [
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'required' => true, 'default' => 'leaf'],
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'required' => true],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'required' => true],
            ['name' => 'tags', 'label' => 'Tags (comma separated)', 'type' => 'text'],
            ['name' => 'color', 'label' => 'Clay color', 'type' => 'color', 'default' => 'var(--lav)'],
            ['name' => 'link', 'label' => 'Link', 'type' => 'text', 'default' => '#work'],
            ['name' => 'image', 'label' => 'Image', 'type' => 'image'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
        ],
    ],
    'skills' => [
        'table' => 'skills',
        'title' => 'Skills',
        'label' => 'Skill',
        'icon' => 'star',
        'subtitle' => 'Chips inside the toolbox tray.',
        'list' => [
            ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'sort_order', 'label' => 'Order'],
        ],
        'fields' => [
            ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true],
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'required' => true, 'default' => 'code'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
        ],
    ],
    'stats' => [
        'table' => 'stats',
        'title' => 'Stats',
        'label' => 'Stat',
        'icon' => 'bolt',
        'subtitle' => 'The little counters inside the about card.',
        'list' => [
            ['key' => 'num', 'label' => 'Number'],
            ['key' => 'lbl', 'label' => 'Label'],
            ['key' => 'sort_order', 'label' => 'Order'],
        ],
        'fields' => [
            ['name' => 'num', 'label' => 'Number', 'type' => 'text', 'required' => true],
            ['name' => 'lbl', 'label' => 'Label', 'type' => 'text', 'required' => true],
            ['name' => 'color', 'label' => 'Clay color', 'type' => 'color', 'default' => 'var(--lav)'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
        ],
    ],
    'socials' => [
        'table' => 'socials',
        'title' => 'Social links',
        'label' => 'Social link',
        'icon' => 'heart',
        'subtitle' => 'The round clay buttons in the contact card.',
        'list' => [
            ['key' => 'icon', 'label' => 'Icon', 'type' => 'icon'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'url', 'label' => 'URL'],
            ['key' => 'sort_order', 'label' => 'Order'],
        ],
        'fields' => [
            ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true],
            ['name' => 'icon', 'label' => 'Icon', 'type' => 'icon', 'required' => true, 'default' => 'heart'],
            ['name' => 'color', 'label' => 'Clay color', 'type' => 'color', 'default' => 'var(--lav)'],
            ['name' => 'url', 'label' => 'URL', 'type' => 'text', 'required' => true, 'default' => '#contact'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
        ],
    ],
    'marquee' => [
        'table' => 'marquee_items',
        'title' => 'Marquee',
        'label' => 'Marquee item',
        'icon' => 'wind',
        'subtitle' => 'The scrolling band under the hero.',
        'list' => [
            ['key' => 'label', 'label' => 'Text'],
            ['key' => 'sort_order', 'label' => 'Order'],
        ],
        'fields' => [
            ['name' => 'label', 'label' => 'Text', 'type' => 'text', 'required' => true],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number', 'default' => 0],
        ],
    ],
];
