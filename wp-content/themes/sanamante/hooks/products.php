<?php
function products_type(): void
{
    $labels = [
        'name' => 'Productos',
        'singular_name' => 'Producto',
        'menu_name' => 'Productos'
    ];

    $args = [
        'label' => 'Productos',
        'description' => 'Productos de mobiliarios famarsa',
        'labels' => $labels,
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'public' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-store',
        'can_export' => true,
        'publicly_queryable' => true,
        'rewrite' =>true,
        'show_in_rest' => true
    ];
    register_post_type('product', $args);
}

add_action('init', 'products_type');