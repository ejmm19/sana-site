<?php
/**
 * @return void
 */
function init_template(): void
{
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');

    register_nav_menus([
        'top_menu' => __('Menú principal')
    ]);
}

add_action('after_setup_theme', 'init_template');