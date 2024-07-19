<?php
/*
Plugin Name: Booking Basic SM
Description: Un plugin básico de ejemplo.
Version: 1.0
Author: Eric Js
*/
require_once 'model/Employees.php';
function agregar_menu_plugin() {
    add_menu_page(
        'Booking Basic',  // Título de la página
        'Booking Basic',  // Título del menú
        'manage_options', // Capacidad
        'booking-basic',  // Slug del menú
        'showMenuItem1',// Función que muestra la vista 1
        'dashicons-calendar', // Icono del menú (puedes cambiar el icono)
        30                  // Posición del menú
    );

    add_submenu_page(
        'booking-basic',  // Slug del menú principal
        'Employees',        // Título de la página del submenú
        'Employees',        // Título del submenú
        'manage_options', // Capacidad
        'booking-basic-vista-2', // Slug del submenú
        'showMenuItem2' // Función que muestra la vista 2
    );
}

// Hook para agregar el menú en el panel de administración
add_action('admin_menu', 'agregar_menu_plugin');

// Función para encolar el CSS
function encolar_estilos_plugin($hook) {
    // Verifica que estamos en las páginas del plugin
    if ($hook != 'toplevel_page_booking-basic' && $hook != 'booking-basic_page_booking-basic-vista-1' && $hook != 'booking-basic_page_booking-basic-vista-2') {
        return;
    }
    wp_register_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        '',
        '5.3.3',
        'all'
    );
    wp_enqueue_style('stylesheet', get_stylesheet_uri(), ['bootstrap'], '1.0', 'all');
    wp_enqueue_style('estilos_plugin', plugin_dir_url(__FILE__) . 'templates/wp-admin/css/admin.css');

    // scripts
    #wp_enqueue_script( 'vue', 'https://unpkg.com/vue@3/dist/vue.global.js', ['jquery'], '2', true );
    #wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js', ['jquery'], '2', true );
    wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', ['jquery'], '2', true );
    #wp_enqueue_script('vue', plugin_dir_url(__FILE__)  . '/templates/wp-admin/js/vuejs.js', null, '1.0', true);
    wp_enqueue_script( 'v-calendar', 'https://unpkg.com/v-calendar', ['vue'], '2.4.2', true );
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__)  . '/templates/wp-admin/js/custom-script.js', ['vue', 'v-calendar'], '1.0', true);
}

// Hook para encolar los estilos en el admin
add_action('admin_enqueue_scripts', 'encolar_estilos_plugin');

function showMenuItem1(): void
{
    require_once plugin_dir_path(__FILE__) . '/templates/wp-admin/calendar.php';
}

function showMenuItem2(): void
{
    require_once plugin_dir_path(__FILE__) . '/templates/wp-admin/employees.php';
}
