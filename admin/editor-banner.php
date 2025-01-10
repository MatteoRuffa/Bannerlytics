<?php
// File: editor-banner.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Funzione per visualizzare l'editor dei banner
function bannerlytics_editor_banner_page() {
    global $wpdb;

    // Controlla se è stato inviato un form per salvare o aggiornare un banner
    if ( isset( $_POST['banner_submit'] ) && current_user_can( 'edit_posts' ) ) {
        $banner_id = isset( $_POST['banner_id'] ) ? intval( $_POST['banner_id'] ) : 0;
        $banner_title = sanitize_text_field( $_POST['banner_title'] );
        $banner_description = sanitize_textarea_field( $_POST['banner_description'] );
        $banner_button_text = sanitize_text_field( $_POST['banner_button_text'] );

        $banner_data = array(
            'post_title' => $banner_title,
            'post_content' => '', // I dettagli aggiuntivi verranno salvati nei meta
            'post_status' => 'publish',
            'post_type' => 'banner',
        );

        if ( $banner_id > 0 ) {
            // Aggiorna il banner esistente
            $banner_data['ID'] = $banner_id;
            wp_update_post( $banner_data );
            update_post_meta( $banner_id, '_banner_description', $banner_description );
            update_post_meta( $banner_id, '_banner_button_text', $banner_button_text );
            echo '<div class="notice notice-success"><p>Banner aggiornato con successo!</p></div>';
        } else {
            // Crea un nuovo banner
            $new_banner_id = wp_insert_post( $banner_data );
            if ( $new_banner_id ) {
                update_post_meta( $new_banner_id, '_banner_description', $banner_description );
                update_post_meta( $new_banner_id, '_banner_button_text', $banner_button_text );
                echo '<div class="notice notice-success"><p>Nuovo banner creato con successo!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Errore durante la creazione del banner.</p></div>';
            }
        }
    }

    // Modulo HTML per l'editor del banner
    $banner_id = isset( $_GET['banner_id'] ) ? intval( $_GET['banner_id'] ) : 0;
    $banner = get_post( $banner_id );
    $banner_title = $banner ? $banner->post_title : '';
    $banner_description = $banner ? get_post_meta( $banner_id, '_banner_description', true ) : '';
    $banner_button_text = $banner ? get_post_meta( $banner_id, '_banner_button_text', true ) : '';

    echo '<div class="wrap">';
    echo '<h1>' . ( $banner_id > 0 ? 'Modifica Banner' : 'Crea Nuovo Banner' ) . '</h1>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="banner_id" value="' . esc_attr( $banner_id ) . '">';
    echo '<table class="form-table">';
    echo '<tr><th><label for="banner_title">Titolo del Banner</label></th><td><input type="text" name="banner_title" id="banner_title" value="' . esc_attr( $banner_title ) . '" class="regular-text" required></td></tr>';
    echo '<tr><th><label for="banner_description">Descrizione del Banner</label></th><td><textarea name="banner_description" id="banner_description" rows="5" class="large-text">' . esc_textarea( $banner_description ) . '</textarea></td></tr>';
    echo '<tr><th><label for="banner_button_text">Testo del Pulsante</label></th><td><input type="text" name="banner_button_text" id="banner_button_text" value="' . esc_attr( $banner_button_text ) . '" class="regular-text"></td></tr>';
    echo '</table>';
    echo '<p class="submit"><input type="submit" name="banner_submit" id="banner_submit" class="button button-primary" value="Salva Banner"></p>';
    echo '</form>';
    echo '</div>';
}