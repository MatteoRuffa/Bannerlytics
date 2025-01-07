<?php
if (!defined('ABSPATH')) {
    exit; // Protezione contro accessi diretti
}

// Aggiunge il widget alla dashboard
add_action('wp_dashboard_setup', 'add_my_custom_dashboard_widget');
function add_my_custom_dashboard_widget() {
    wp_add_dashboard_widget(
        'custom_banner_dashboard_widget',  
        'Preview Banner',  
        'my_custom_dashboard_widget_display'  
    );
}

// Funzione per mostrare il contenuto del widget
function my_custom_dashboard_widget_display() {

    // Query per ottenere gli ultimi 3 banner pubblicati
    $args = array(
        'post_type'      => 'banner',
        'posts_per_page' => 3,  
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="dashboard-banner-preview-container">';
        while ($query->have_posts()) {
            $query->the_post();
            $banner_id    = get_the_ID();
            $banner_title = get_the_title();
            $banner_img   = get_post_meta($banner_id, '_immagine', true);
            $banner_desc  = get_post_meta($banner_id, '_descrizione', true);
            $banner_url   = get_permalink($banner_id);

            // Stampa ciascun banner con uno stile di card
            echo '<div class="dashboard-banner-card">';
            echo '<h3 class="dashboard-banner-title">' . esc_html($banner_title) . '</h3>';
            if ($banner_img) {
                echo '<img src="' . esc_url($banner_img) . '" alt="' . esc_attr($banner_title) . '" class="dashboard-banner-image" style=" max-width: 350px; ">';
            } else {
                echo '<p class="dashboard-no-image">Nessuna immagine disponibile.</p>';
            }
            echo '<p class="dashboard-banner-description">' . esc_html($banner_desc) . '</p>';
            echo '<p><a href="' . esc_url($banner_url) . '" target="_blank" class="dashboard-banner-link">Vai al banner</a></p>';  
            echo '</div>';
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<p class="dashboard-no-banner">Non ci sono banner pubblicati al momento.</p>';
    }
}

// Enqueue del CSS per la dashboard
add_action('admin_enqueue_scripts', 'my_custom_dashboard_styles');
function my_custom_dashboard_styles($hook_suffix) {
    if ($hook_suffix === 'index.php') {  // Carica lo stile solo nella dashboard principale
        wp_enqueue_style(
            'M-M-banner-dashboard-styles',
            plugin_dir_url(__FILE__) . '../assets/css/dashboard-style.css',
            array(),
            '1.0.0'
        );
    }
}
