<?php

function orientadores_type(): void
{
    $labels = [
        'name' => 'Orientadores',
        'singular_name' => 'Orientadores',
        'menu_name' => 'Orientadores'
    ];

    $args = [
        'label' => 'Orientadores',
        'description' => 'Orientadores Sanamente',
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-groups',
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite' =>true,
        'show_in_rest' => true
    ];
    register_post_type('orientadores', $args);
}

add_action('init', 'orientadores_type');
