<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Funzione per visualizzare l'editor dei banner
function bannerlytics_editor_banner_page() {
    global $wpdb;

    // Controlla se è stato inviato un form per salvare o aggiornare un banner
    if ( isset( $_POST['banner_submit'] ) && current_user_can( 'edit_posts' ) ) {
        $banner_id = isset( $_POST['banner_id'] ) ? intval( $_POST['banner_id'] ) : 0;
        $fields = [
            'colore_sfondo', 'tipologia_immagine_desktop', 'tipologia_immagine_mobile', 'immagine', 'titolo', 'colore_titolo',
            'descrizione', 'colore_descrizione', 'testo_bottone', 'link_bottone', 'colore_bottone', 'colore_testo_bottone',
            'apertura_link', 'colore_bordo_bottone', 'border_radius_bottone', 'colore_hover_bg_bottone',
            'colore_hover_testo_bottone', 'colore_hover_bordo_bottone', 'larghezza_banner', 'border_radius_banner', 'box_shadow_banner'
        ];

        $meta_data = [];
        foreach ( $fields as $field ) {
            $meta_data[$field] = isset( $_POST[$field] ) ? sanitize_text_field( $_POST[$field] ) : '';
        }

        $banner_data = [
            'post_title' => $meta_data['titolo'],
            'post_content' => '', // I dettagli verranno salvati nei meta
            'post_status' => 'publish',
            'post_type' => 'banner',
        ];

        if ( $banner_id > 0 ) {
            $banner_data['ID'] = $banner_id;
            wp_update_post( $banner_data );
            foreach ( $meta_data as $key => $value ) {
                update_post_meta( $banner_id, "_{$key}", $value );
            }
            echo '<div class="notice notice-success"><p>Banner aggiornato con successo!</p></div>';
        } else {
            $new_banner_id = wp_insert_post( $banner_data );
            if ( $new_banner_id ) {
                foreach ( $meta_data as $key => $value ) {
                    update_post_meta( $new_banner_id, "_{$key}", $value );
                }
                echo '<div class="notice notice-success"><p>Nuovo banner creato con successo!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Errore durante la creazione del banner.</p></div>';
            }
        }
    }

    // Recupera i valori esistenti o predefiniti
    $banner_id = isset( $_GET['banner_id'] ) ? intval( $_GET['banner_id'] ) : 0;
    $banner = get_post( $banner_id );
    $defaults = [
        'colore_sfondo' => '#ffffff', 'tipologia_immagine_desktop' => '', 'tipologia_immagine_mobile' => '', 'immagine' => '',
        'titolo' => '', 'colore_titolo' => '#000000', 'descrizione' => '', 'colore_descrizione' => '#000000',
        'testo_bottone' => '', 'link_bottone' => '', 'colore_bottone' => '#0073aa', 'colore_testo_bottone' => '#ffffff',
        'apertura_link' => 'stessa finestra', 'colore_bordo_bottone' => '#000000', 'border_radius_bottone' => '5px',
        'colore_hover_bg_bottone' => '#cccccc', 'colore_hover_testo_bottone' => '#000000', 'colore_hover_bordo_bottone' => '#000000',
        'larghezza_banner' => '960', 'border_radius_banner' => '5', 'box_shadow_banner' => '10px'
    ];

    $meta_data = [];
    foreach ( $defaults as $key => $default ) {
        $meta_data[$key] = $banner ? get_post_meta( $banner_id, "_{$key}", true ) : $default;
    }

    // Modulo HTML per l'editor del banner
    echo '<div class="wrap">';
    echo '<h1>' . ( $banner_id > 0 ? 'Modifica Banner' : 'Crea Nuovo Banner' ) . '</h1>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="banner_id" value="' . esc_attr( $banner_id ) . '">';

    echo '<div class="editor-banner-layout">';

        // -------------------- RIGA 1 --------------------
        echo '<div class="riga-1">';
        echo '<div>';
        echo '<p><label for="colore_sfondo"><strong>Colore Sfondo</strong></label><br/>';
        echo '<input type="text" name="colore_sfondo" id="colore_sfondo" value="' . esc_attr( $meta_data['colore_sfondo'] ) . '" placeholder="#FFFFFF">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="tipologia_immagine_desktop"><strong>Tipologia Immagine Desktop</strong></label><br/>';
        echo '<select name="tipologia_immagine_desktop" id="tipologia_immagine_desktop">';
        echo '<option value="">-- Seleziona --</option>';
        echo '<option value="destra" ' . selected( $meta_data['tipologia_immagine_desktop'], 'destra', false ) . '>Destra</option>';
        echo '<option value="sinistra" ' . selected( $meta_data['tipologia_immagine_desktop'], 'sinistra', false ) . '>Sinistra</option>';
        echo '</select></p></div>';

        echo '<div>';
        echo '<p><label for="tipologia_immagine_mobile"><strong>Tipologia Immagine Mobile</strong></label><br/>';
        echo '<select name="tipologia_immagine_mobile" id="tipologia_immagine_mobile">';
        echo '<option value="">-- Seleziona --</option>';
        echo '<option value="destra" ' . selected( $meta_data['tipologia_immagine_mobile'], 'destra', false ) . '>Destra</option>';
        echo '<option value="sinistra" ' . selected( $meta_data['tipologia_immagine_mobile'], 'sinistra', false ) . '>Sinistra</option>';
        echo '</select></p></div>';
        echo '</div>'; // Fine riga 1

        // -------------------- RIGA 2 --------------------
        echo '<div class="riga-2">';
        echo '<div class="preview-container">';
        echo '<p><label for="immagine"><strong>Immagine</strong></label></p>';
        echo '<input type="hidden" id="banner-image-url" name="immagine" value="' . esc_url($meta_data['immagine']) . '">';
        echo '<button type="button" class="button select-banner-image">Seleziona immagine</button>';

        echo '<div class="banner-image-preview-wrapper">';
        if (!empty($meta_data['immagine'])) {
            echo '<img id="banner-image-preview" class="banner-preview-image" src="' . esc_url($meta_data['immagine']) . '">';
            echo '<button type="button" class="button remove-banner-image">Rimuovi immagine</button>';
        } else {
            echo '<img id="banner-image-preview" class="banner-preview-image" src="#" style="display: none;">';
            echo '<button type="button" class="button remove-banner-image" style="display: none;">Rimuovi immagine</button>';
        }
        echo '</div>';
        echo '</div>';

        echo '<div class="flex-item">';
        echo '<p><label for="titolo"><strong>Titolo</strong></label></p>';
        echo '<input type="text" name="titolo" id="titolo" value="' . esc_attr($meta_data['titolo']) . '">';
        echo '</div>';

        echo '<div class="flex-item">';
        echo '<p><label for="colore_titolo"><strong>Colore Titolo</strong></label></p>';
        echo '<input type="text" name="colore_titolo" id="colore_titolo" value="' . esc_attr($meta_data['colore_titolo']) . '" placeholder="#000000">';
        echo '</div>';
        echo '</div>'; // Fine riga 2

        // -------------------- RIGA 3 --------------------
        echo '<div class="riga-3">';

        echo '<div>';
        echo '<p><label for="descrizione"><strong>Descrizione</strong></label><br/>';
        echo '<textarea name="descrizione" id="descrizione" rows="4">' . esc_textarea( $meta_data['descrizione'] ) . '</textarea>';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="colore_descrizione"><strong>Colore Descrizione</strong></label><br/>';
        echo '<input type="text" name="colore_descrizione" id="colore_descrizione" value="' . esc_attr( $meta_data['colore_descrizione'] ) . '" placeholder="#000000">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="testo_bottone"><strong>Testo Bottone</strong></label><br/>';
        echo '<input type="text" name="testo_bottone" id="testo_bottone" value="' . esc_attr( $meta_data['testo_bottone'] ) . '">';
        echo '</p></div>';
        echo '</div>'; // Fine riga 3

        // -------------------- RIGA 4 --------------------
        echo '<div class="riga-4">';

        echo '<div>';
        echo '<p><label for="link_bottone"><strong>Link Bottone</strong></label><br/>';
        echo '<input type="url" name="link_bottone" id="link_bottone" value="' . esc_attr( $meta_data['link_bottone'] ) . '" placeholder="https://...">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="colore_bottone"><strong>Colore Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_bottone" id="colore_bottone" value="' . esc_attr( $meta_data['colore_bottone'] ) . '" placeholder="#FFFFFF">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="colore_testo_bottone"><strong>Colore Testo Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_testo_bottone" id="colore_testo_bottone" value="' . esc_attr( $meta_data['colore_testo_bottone'] ) . '" placeholder="#000000">';
        echo '</p></div>';
        echo '</div>'; // Fine riga 4

        // -------------------- RIGA 5 --------------------
        echo '<div class="riga-5">';

        echo '<div>';
        echo '<p><label for="apertura_link"><strong>Apertura Link</strong></label><br/>';
        echo '<select name="apertura_link" id="apertura_link">';
        echo '<option value="">-- Seleziona --</option>';
        echo '<option value="stessa finestra" ' . selected( $meta_data['apertura_link'], 'stessa finestra', false ) . '>Stessa finestra</option>';
        echo '<option value="nuova finestra" ' . selected( $meta_data['apertura_link'], 'nuova finestra', false ) . '>Nuova finestra</option>';
        echo '</select></p></div>';

        echo '<div>';
        echo '<p><label for="colore_bordo_bottone"><strong>Colore Bordo Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_bordo_bottone" id="colore_bordo_bottone" value="' . esc_attr( $meta_data['colore_bordo_bottone'] ) . '" placeholder="#000000">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="border_radius_bottone"><strong>Border Radius Bottone</strong></label><br/>';
        echo '<input type="text" name="border_radius_bottone" id="border_radius_bottone" value="' . esc_attr( $meta_data['border_radius_bottone'] ) . '" placeholder="5px / 50% / etc.">';
        echo '</p></div>';
        echo '</div>'; // Fine riga 5
    
         // -------------------- RIGA 6 --------------------
        echo '<div class="riga-6">';

        echo '<div>';
        echo '<p><label for="colore_hover_bg_bottone"><strong>Colore Hover BG Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_hover_bg_bottone" id="colore_hover_bg_bottone" value="' . esc_attr( $meta_data['colore_hover_bg_bottone'] ) . '" placeholder="#CCCCCC">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="colore_hover_testo_bottone"><strong>Colore Hover Testo Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_hover_testo_bottone" id="colore_hover_testo_bottone" value="' . esc_attr( $meta_data['colore_hover_testo_bottone'] ) . '" placeholder="#000000">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="colore_hover_bordo_bottone"><strong>Colore Hover Bordo Bottone</strong></label><br/>';
        echo '<input type="text" name="colore_hover_bordo_bottone" id="colore_hover_bordo_bottone" value="' . esc_attr( $meta_data['colore_hover_bordo_bottone'] ) . '" placeholder="#000000">';
        echo '</p></div>';
        echo '</div>'; // Fine riga 6
        
// -------------------- RIGA 7 --------------------
echo '<div class="riga-7">';
echo '<div class="slider-container">';
echo '<p style="text-align: center;"><label for="larghezza_banner"><strong>Larghezza Banner</strong></label></p>';
echo '<input type="range" name="larghezza_banner" id="container_width_slider" min="350" max="1200" value="' . esc_attr($meta_data['larghezza_banner']) . '" class="slider-input">';
echo '<span id="width_value">' . esc_html($meta_data['larghezza_banner']) . ' px</span>';
echo '</div>';
echo '</div>'; // Fine riga 7

        // -------------------- RIGA 8 --------------------
        echo '<div class="riga-8">';

        echo '<div>';
        echo '<p><label for="border_radius_banner"><strong>Border Radius Banner</strong></label><br/>';
        echo '<input type="text" name="border_radius_banner" id="border_radius_banner" value="' . esc_attr( $meta_data['border_radius_banner'] ) . '" placeholder="5px">';
        echo '</p></div>';

        echo '<div>';
        echo '<p><label for="box_shadow_banner"><strong>Box Shadow Banner</strong></label><br/>';
        echo '<input type="text" name="box_shadow_banner" id="box_shadow_banner" value="' . esc_attr( $meta_data['box_shadow_banner'] ) . '" placeholder="0px 0px 10px rgba(0,0,0,0.1)">';
        echo '</p></div>';

        echo '</div>'; // Fine riga 8

    echo '<p class="submit"><input type="submit" name="banner_submit" id="banner_submit" class="button button-primary" value="Salva Banner"></p>';
    echo '</form>';
    echo '</div>'; // Fine wrap
}