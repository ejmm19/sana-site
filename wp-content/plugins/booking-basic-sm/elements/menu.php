<?php
/**
 * @return void
 */
function add_menu_plugin(): void
{
    add_menu_page(
        'Dashboard',  // Título de la página
        'Booking Basic',
        'manage_options', // Capacidad
        'booking-basic',  // Slug del menú
        'showDashboard',// Función que muestra la vista 1
        'dashicons-calendar', // Icono del menú (puedes cambiar el icono)
        30                  // Posición del menú
    );

    add_submenu_page(
        'booking-basic',  // Slug del menú principal
        __('Employees'),
        __('Employees'),
        'manage_options', // Capacidad
        'booking-basic-employees', // Slug del submenú
        'showMenuItem2' // Función que muestra la vista 2
    );

    add_submenu_page(
        'booking-basic',
        __('Scheduling'),
        __('Scheduling'),
        'manage_options',
        'booking-basic-scheduling',
        'showCalendar'
    );

    add_submenu_page(
        'booking-basic',
        __('Settings'),
        __('Settings'),
        'manage_options',
        'booking-basic-settings',
        'showSettings'
    );
}

add_action('admin_menu', 'add_menu_plugin');

function showDashboard(): void
{
    require_once plugin_dir_path(__DIR__) . '/templates/wp-admin/view/dashboard.php';
}

function showMenuItem2(): void
{
    require_once plugin_dir_path(__DIR__) . '/templates/wp-admin/view/employees.php';
}

function showCalendar(): void
{
    require_once plugin_dir_path(__DIR__) . '/templates/wp-admin/view/scheduling.php';
}

function showSettings(): void
{
    require_once plugin_dir_path(__DIR__) . '/templates/wp-admin/view/settings.php';
}