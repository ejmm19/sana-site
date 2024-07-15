<?php
/**
 * @return void
 */
function assets(): void
{
    wp_register_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        '',
        '5.3.3',
        'all'
    );
    wp_register_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        '',
        '6.5.2',
        'all'
    );
    wp_register_style(
        'animate',
        'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
        '',
        '4.1.1',
        'all'
    );
    wp_register_style(
        'ubuntu',
        'https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap',
        '',
        '1.0',
        'all'
    );
    wp_register_style(
        'NotoSans',
        'https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap',
        '',
        '1.0',
        'all'
    );
    wp_register_style(
        'Varela Round',
        'https://fonts.googleapis.com/css2?family=Varela+Round&display=swap',
        '',
        '1.0',
        'all'
    );
    wp_enqueue_style('stylesheet', get_stylesheet_uri(), ['bootstrap', 'animate', 'ubuntu', 'NotoSans', 'Varela Round', 'font-awesome'], '1.0', 'all');

    wp_register_script(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
        '',
        '5.3.3',
        true
    );
    wp_enqueue_script('bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js', ['jquery'], '1.0', true);
    wp_enqueue_script('script', get_template_directory_uri() . '/assets/js/script.js', ['jquery'], '1.0', true);
}

add_action('wp_enqueue_scripts', 'assets');

