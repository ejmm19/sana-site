<?php
/*
Plugin Name: Booking Basic SM
Description: Un plugin básico de ejemplo.
Version: 1.0
Author: Eric Js
*/

use model\Order;

require_once 'model/Employees.php';
require_once 'model/Order.php';
require_once 'model/Config.php';
require_once 'shortcodes/main.php';
require_once 'elements/menu.php';
require_once 'filters/translate.php';


/**
 * @return void
 */
function create_orders_table(): void
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'orders';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(255) NOT NULL,
        name varchar(255) NOT NULL,
        phone varchar(10) NOT NULL,
        status varchar(10) DEFAULT 'pending' NOT NULL,
        employeeId mediumint(9) NOT NULL,
        schedule_date date NOT NULL,
        date_init datetime NOT NULL,
        date_finish datetime NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_orders_table');

/**
 * @return void
 */
function create_config_tabla(): void
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'config_data';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        path VARCHAR(255) NOT NULL UNIQUE,
        value LONGTEXT NULL,
        updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_config_tabla');




// Función para encolar el CSS
function enqueue_script_and_styles_plugin($hook): void
{
    // Verifica que estamos en las páginas del plugin
    if ($hook != 'toplevel_page_booking-basic' && $hook != 'booking-basic_page_booking-basic-vista-1'
        && $hook != 'booking-basic_page_booking-basic-employees' && $hook != 'booking-basic_page_booking-basic-scheduling'
        && $hook != 'booking-basic_page_booking-basic-settings') {
        return;
    }
    wp_register_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        '',
        '5.3.3',
        'all'
    );
    wp_register_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css',
        '',
        '6.6.0'
    );
    wp_enqueue_style('stylesheet', get_stylesheet_uri(), ['bootstrap', 'font-awesome'], '1.0', 'all');
    wp_enqueue_script( 'sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js', [], '11.12.4', true );
    wp_enqueue_style('sweetalert2-style', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css');

    wp_enqueue_style('estilos_plugin', plugin_dir_url(__FILE__) . 'templates/wp-admin/css/admin.css');

    // scripts
    #wp_enqueue_script( 'vue', 'https://unpkg.com/vue@3/dist/vue.global.js', ['jquery'], '2', true );
    #wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js', ['jquery'], '2', true );
    wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', ['jquery'], '2', true );
    #wp_enqueue_script('vue', plugin_dir_url(__FILE__)  . '/templates/wp-admin/js/vuejs.js', null, '1.0', true);
    wp_enqueue_script( 'v-calendar', 'https://unpkg.com/v-calendar', ['vue'], '2.4.2', true );
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__)  . '/templates/wp-admin/js/custom-script.js', ['vue', 'v-calendar'], '1.0', true);
    wp_localize_script('custom-script', 'schedule_obj', [ 'ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('schedule_order_nonce')]);
}

// Hook para encolar los estilos en el admin
add_action('admin_enqueue_scripts', 'enqueue_script_and_styles_plugin');


// in frontend
/**
 * @return void
 */
function pluginAssets(): void
{
    #wp_enqueue_script( 'vue', 'https://cdn.jsdelivr.net/npm/vue/dist/vue.js', ['jquery'], '2', true );
    #wp_enqueue_script( 'wompi', 'https://checkout.wompi.co/widget.js', [], '1', true );

    wp_enqueue_script( 'vue', 'https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js', ['jquery'], '2', true );
    wp_enqueue_script( 'v-calendar', 'https://unpkg.com/v-calendar', ['vue'], '2.4.2', true );

    wp_enqueue_script( 'sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js', [], '11.12.4', true );
    wp_enqueue_style('sweetalert2-style', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css');

    wp_enqueue_script('custom-script-frontend', plugin_dir_url(__FILE__)  . '/templates/frontend/js/script.js', ['v-calendar'], '1.0', true);
    wp_localize_script('custom-script-frontend', 'schedule_obj', [ 'ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('schedule_order_nonce')]);

    wp_enqueue_style('custom-style', plugin_dir_url(__FILE__)  . '/templates/frontend/css/calendar-front.css');
}
add_action('wp_enqueue_scripts', 'pluginAssets');


// order functions

/**
 * @return void
 */
function order_ajax_handler(): void
{
    try {
        check_ajax_referer('schedule_order_nonce', 'security');
        // match for call functions
        $handle = isset($_POST['handle']) ? sanitize_text_field($_POST['handle']) : '';
        $data = !empty($_POST['data']) ? $_POST['data'] : [];

        if (empty($handle) || empty($data)) {
            throw new Exception();
        }

        $order = new Order();
        $config = new \model\Config();
        match ($handle) {
            'setOrder' => $order->setOrder($data, true),
            'deleteOrder' => $order->deleteOrder($data),
            'approveOrder' => $order->approveOrder($data),
            'setMultipleConfig' => $config->setMultipleConfig($data),
            default => '',
        };
        $response = array(
            'status' => 'success',
            'message' => 'Este es un mensaje de éxito'
        );
    }catch (Exception $e){
        $response = [
            'status' => 'error',
            'message' => 'Se ha producido un error: ' . $e->getMessage()
        ];
    }

    wp_send_json($response);
}

add_action('wp_ajax_order_ajax_action', 'order_ajax_handler');
add_action('wp_ajax_nopriv_order_ajax_action', 'order_ajax_handler');
