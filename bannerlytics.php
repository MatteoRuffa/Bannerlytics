<?php
/*
Plugin Name: Bannerlytics
Plugin URI: https://github.com/MatteoRuffa/bannerlytics
Description: Plugin che registra un CPT "banner", shortcode per visualizzarlo e template dedicato.
Version: 1.0
Author: Matteo R
Author URI: https://github.com/MatteoRuffa?tab=repositories
License: GPL2
*/

if ( ! defined('ABSPATH') ) {
    exit; 
}

// Includo i file con le funzionalità
require_once plugin_dir_path(__FILE__) . 'includes/cpt-banner.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-banner.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/dashboard-widget.php';
require_once plugin_dir_path(__FILE__) . 'admin/menu.php';

// Carico automaticamente il template single-banner.php:
add_filter('single_template', 'my_banner_single_template');
function my_banner_single_template($single) {
    global $post;
    if ( isset($post->post_type) && $post->post_type === 'banner' ) {
        return plugin_dir_path(__FILE__) . 'templates/single-banner.php';
    }
    return $single;
}


// Enqueue del CSS del plugin
add_action('wp_enqueue_scripts', 'my_banner_plugin_enqueue_assets', 999); 
function my_banner_plugin_enqueue_assets() {
    $css_url = plugin_dir_url(__FILE__) . 'assets/css/style.css';
    
    // Enqueue del CSS
    wp_enqueue_style(
        'bannerlytics-styles', $css_url, array(), '1.0.0'                    
    );
}

// Script per le pagine admin (menu e editor banner)
add_action('admin_enqueue_scripts', 'my_banner_admin_scripts');
function my_banner_admin_scripts($hook_suffix) {
    if ( $hook_suffix !== 'toplevel_page_bannerlytics-dashboard' &&
         $hook_suffix !== 'bannerlytics_page_bannerlytics-gestione-banners' &&
         $hook_suffix !== 'bannerlytics_page_bannerlytics-editor-banner' ) {
        return;
    }

    wp_enqueue_script(
        'bannerlytics-admin-scripts',
        plugin_dir_url(__FILE__) . 'assets/js/admin-scripts.js',
        array('jquery'),
        '1.0',
        true
    );

    wp_enqueue_style(
        'bannerlytics-admin-styles',
        plugin_dir_url(__FILE__) . 'assets/css/style.css',
        array(),
        '1.0.0'
    );
}

// Attivazione del plugin con flush delle regole di permalink
function bannerlytics_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'bannerlytics_activate');

// Disattivazione del plugin
function bannerlytics_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'bannerlytics_deactivate');


// Funzione globale per caricare lo stile admin su tutte le pagine del plugin
function bannerlytics_enqueue_admin_styles($hook_suffix) {
    $admin_pages = array(
        'toplevel_page_bannerlytics-dashboard',
        'bannerlytics_page_bannerlytics-gestione-banner',
        'bannerlytics_page_bannerlytics-editor-banner',
    );

    if ( in_array($hook_suffix, $admin_pages) ) {
        wp_enqueue_style(
            'bannerlytics-admin-styles',
            plugin_dir_url(__FILE__) . '../assets/css/admin-style.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'bannerlytics_enqueue_admin_styles');



// Script metabox-preview (caricato solo quando si è nell'editor CPT banner)
// add_action('admin_enqueue_scripts', 'my_banner_admin_scripts');
// function my_banner_admin_scripts($hook_suffix) {
//     if ($hook_suffix !== 'post.php' && $hook_suffix !== 'post-new.php') {
//         return;
//     }

//     global $post;
//     if (!$post || $post->post_type !== 'banner') {
//         return;
//     }

//     // Carica la libreria media di WordPress
//     wp_enqueue_media();

//     // Carica lo script personalizzato
//     wp_enqueue_script(
//         'bannerlytics-preview',
//         plugin_dir_url(__FILE__) . 'assets/js/metabox-preview.js',
//         array('jquery'),
//         '1.0',
//         true
//     );
// }

