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
        'posts_per_page' => -1,  
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="dashboard-carousel-container">';
        echo '<button class="carousel-prev">&larr;</button>'; 
        echo '<div class="carousel-wrapper">';
        echo '<div class="carousel-track">';
        while ($query->have_posts()) {
            $query->the_post();
            $banner_id    = get_the_ID();
            $banner_title = get_the_title();
            $banner_img   = get_post_meta($banner_id, '_immagine', true);
            $banner_link  = get_permalink($banner_id); 
            $banner_descr = get_post_meta($banner_id, '_descrizione', true);
            $banner_descr = wp_trim_words($banner_descr, 20, '...');
            $tipologia_img = get_post_meta($banner_id, '_tipologia_immagine_desktop', true);

            // Card del banner
            echo '<div class="carousel-item">';
            echo '<div class="dashboard-banner-card-horizontal">';
            if ($tipologia_img === 'sinistra') {
                echo '<div class="dashboard-banner-image-wrapper">';
                echo '<img src="' . esc_url($banner_img) . '" alt="' . esc_attr($banner_title) . '" class="dashboard-banner-image">';
                echo '</div>';
                echo '<div class="dashboard-banner-text">';
                echo '<h3 class="dashboard-banner-title"><a class="dashboard-banner-title-link" href="' . esc_url($banner_link) . '" target="_blank">' . esc_html($banner_title) . '</a></h3>';
                echo '<p class="dashboard-banner-description">' . esc_html($banner_descr) . '</p>';
                echo '</div>';
            } else {
                echo '<div class="dashboard-banner-text">';
                echo '<h3 class="dashboard-banner-title"><a class="dashboard-banner-title-link" href="' . esc_url($banner_link) . '" target="_blank">' . esc_html($banner_title) . '</a></h3>';
                echo '<p class="dashboard-banner-description">' . esc_html($banner_descr) . '</p>';
                echo '</div>';
                echo '<div class="dashboard-banner-image-wrapper">';
                echo '<img src="' . esc_url($banner_img) . '" alt="' . esc_attr($banner_title) . '" class="dashboard-banner-image">';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        }
        echo '</div>'; 
        echo '</div>';  
        echo '<button class="carousel-next">&rarr;</button>'; 
        echo '</div>';  
        wp_reset_postdata();
    } else {
        echo '<p class="dashboard-no-banner">Non ci sono banner pubblicati al momento.</p>';
    }
}

// Enqueue del CSS e JavaScript per la dashboard
add_action('admin_enqueue_scripts', 'my_custom_dashboard_styles');
function my_custom_dashboard_styles($hook_suffix) {
    if ($hook_suffix === 'index.php') {  // Carica gli stili e gli script solo nella dashboard principale
        wp_enqueue_style(
            'bannerlytics-dashboard-styles',
            plugin_dir_url(__FILE__) . '../assets/css/dashboard-style.css',
            array(),
            '1.0.0'
        );
        wp_enqueue_script(

            'bannerlytics-dashboard-script',
            plugin_dir_url(__FILE__) . '../assets/js/dashboard-carousel.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
