<?php
if (!defined('ABSPATH')) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . '/dashboard.php';
require_once plugin_dir_path(__FILE__) . '/gestione-banner.php';
require_once plugin_dir_path(__FILE__) . '/editor-banner.php';

function bannerlytics_register_admin_menu() {
    // Voce di menu principale
    add_menu_page(
        'BannerLytics',              
        'BannerLytics',                
        'manage_options',              
        'bannerlytics-dashboard',   
        'bannerlytics_dashboard_page', 
        'dashicons-chart-area',        
        25                             // Posizione nel menu (opzionale)
    );

    // Sotto-pagina: Dashboard 
    add_submenu_page(
        'bannerlytics-dashboard',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'bannerlytics-dashboard',         
        'bannerlytics_dashboard_page'
    );

    // Sotto-pagina: Gestione Banner
    add_submenu_page(
        'bannerlytics-dashboard',
        'Gestione Banner',
        'Gestione Banner',
        'manage_options',
        'bannerlytics-gestione-banner',
        'bannerlytics_gestione_banner_page'
    );

    // Sotto-pagina: Editor Banner
    add_submenu_page(
        'bannerlytics-dashboard',
        'Editor Banner',
        'Editor Banner',
        'manage_options',
        'bannerlytics-editor-banner',
        'bannerlytics_editor_banner_page'
    );
}

add_action('admin_menu', 'bannerlytics_register_admin_menu');
