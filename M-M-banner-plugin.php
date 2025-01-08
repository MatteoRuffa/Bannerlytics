<?php
/*
Plugin Name: M-M Banner Plugin
Plugin URI: https://github.com/MatteoRuffa/M-M-banner-plugin
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
require_once plugin_dir_path(__FILE__) . 'includes/metabox-banner.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcode-banner.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/dashboard-widget.php';

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
add_action('wp_enqueue_scripts', 'my_banner_plugin_enqueue_assets', 20); 
function my_banner_plugin_enqueue_assets() {
    $css_url = plugin_dir_url(__FILE__) . 'assets/css/style.css';
    
     // Enqueue del CSS
    wp_enqueue_style(
        'M-M-banner-plugin-styles', 
        $css_url,                   
        array('astra-child-theme-css'),       
        '1.0.0'                     
    );

}

// Scripts vari e script
add_action('admin_enqueue_scripts', 'my_banner_admin_scripts');
function my_banner_admin_scripts($hook_suffix) {
    if ($hook_suffix !== 'post.php' && $hook_suffix !== 'post-new.php') {
        return;
    }

    global $post;
    if (!$post || $post->post_type !== 'banner') {
        return;
    }

    // Libreria media di WordPress
    wp_enqueue_media();

    // Script metabox-preview (caricato solo quando si è nell'editor CPT banner)
    wp_enqueue_script(
        'M-M-banner-preview',
        plugin_dir_url(__FILE__) . 'assets/js/metabox-preview.js',
        array('jquery'),
        '1.0',
        true
    );

    // Carica lo stile opzionale per una migliore presentazione
    // wp_enqueue_style(
    //     'banner-metabox-style',
    //     plugin_dir_url(__FILE__) . 'assets/css/banner-metabox-style.css'
    // );

    // Script degli slider
    wp_enqueue_script(
        'slider-controls',
        plugin_dir_url(__FILE__) . 'assets/js/slider-controls.js',
        array('jquery'),
        '1.0',
        true
    );
}

