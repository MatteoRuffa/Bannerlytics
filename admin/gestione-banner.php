<?php

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
    echo '<table class="wp-list-table widefat fixed striped gestione-banner-table">';
    echo '<thead><tr><th>ID</th><th>Titolo</th><th>Descrizione</th><th>Shortcode</th><th>Azioni</th></tr></thead><tbody>';
    if ( ! empty( $banners ) ) {
        foreach ( $banners as $banner ) {
            $shortcode = '[banner id="' . $banner->ID . '"]';
            $single_banner_link = get_permalink( $banner->ID ); 
            $edit_link = admin_url( 'admin.php?page=bannerlytics-editor-banner&banner_id=' . $banner->ID ); 
            $delete_link = get_delete_post_link( $banner->ID ); 
            $description = get_post_meta( $banner->ID, '_banner_description', true ); 

            echo '<tr>';
            echo '<td>' . $banner->ID . '</td>';
            echo '<td>' . esc_html( $banner->post_title ) . '</td>';
            echo '<td>' . esc_html( $description ) . '</td>';
            echo '<td><code>' . esc_html( $shortcode ) . '</code></td>';
            echo '<td>
                    <div class="admin-actions">
                        <a href="' . esc_url( $single_banner_link ) . '" class="button button-secondary" target="_blank">Visualizza</a>
                        <a href="' . esc_url( $edit_link ) . '" class="button button-primary">Modifica</a>
                        <a href="' . esc_url( $delete_link ) . '" class="button button-link-delete" onclick="return confirm(\'Sei sicuro di voler eliminare questo banner?\')">Elimina</a>
                    </div>
                  </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">Nessun banner trovato.</td></tr>';
    }
    echo '</tbody></table>';
    echo '</div>';
}