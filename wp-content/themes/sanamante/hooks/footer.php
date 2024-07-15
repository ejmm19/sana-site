<?php
function sidebar(): void
{
    register_sidebar(
        [
            'name' => 'Pie de página',
            'id' => 'footer',
            'description' => 'Zona de Widgets para pie de página',
            'before_title' => '<p>',
            'after_title' => '</p>',
            'before_widget' => '<div id="%1$s" class="%2$s">',
            'after_widget' => '</div>',
        ]
    );
}

add_action('widgets_init', 'sidebar');