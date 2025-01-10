<?php
// File: gestione-banner.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Funzione per visualizzare la pagina Gestione Banners
function bannerlytics_gestione_banner_page() {
    $args = array(
        'post_type' => 'banner',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    $banners = get_posts( $args );

    echo '<div class="wrap">';
    echo '<h1>Gestione Banners</h1>';
    echo '<p>Questa è la pagina dedicata alla gestione di tutti i banner registrati.</p>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th>ID</th><th>Titolo</th><th>Shortcode</th></tr></thead><tbody>';
    if ( ! empty( $banners ) ) {
        foreach ( $banners as $banner ) {
            $shortcode = '[banner id="' . $banner->ID . '"]';
            echo '<tr><td>' . $banner->ID . '</td><td>' . esc_html( $banner->post_title ) . '</td><td><code>' . esc_html( $shortcode ) . '</code></td></tr>';
        }
    } else {
        echo '<tr><td colspan="3">Nessun banner trovato.</td></tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
}