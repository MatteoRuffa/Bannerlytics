<?php
if (!defined('ABSPATH')) {
    exit; // Sicurezza
}

// 1. Creiamo la meta box "Impostazioni Banner" nel CPT "banner"
add_action('add_meta_boxes', 'webperformer_add_banner_metabox');
function webperformer_add_banner_metabox() {
    add_meta_box(
        'webperformer_banner_metabox_id',
        'Impostazioni Banner',
        'webperformer_banner_metabox_callback',
        'banner',   // Slug del CPT
        'normal',
        'default'
    );
}

// 2. Callback: stampa i campi HTML
function webperformer_banner_metabox_callback($post) {
    // Nonce per sicurezza
    wp_nonce_field('webperformer_save_banner_meta', 'webperformer_banner_nonce');

    // Recupera i valori già salvati (se esistono)
    $colore_sfondo               = get_post_meta($post->ID, '_colore_sfondo', true);
    $tipologia_immagine_desktop  = get_post_meta($post->ID, '_tipologia_immagine_desktop', true);
    $tipologia_immagine_mobile   = get_post_meta($post->ID, '_tipologia_immagine_mobile', true);
    $immagine                    = get_post_meta($post->ID, '_immagine', true);
    $titolo                      = get_post_meta($post->ID, '_titolo', true);
    $colore_titolo               = get_post_meta($post->ID, '_colore_titolo', true);
    $descrizione                 = get_post_meta($post->ID, '_descrizione', true);
    $colore_descrizione          = get_post_meta($post->ID, '_colore_descrizione', true);
    $testo_bottone               = get_post_meta($post->ID, '_testo_bottone', true);
    $link_bottone                = get_post_meta($post->ID, '_link_bottone', true);
    $colore_bottone              = get_post_meta($post->ID, '_colore_bottone', true);
    $colore_testo_bottone        = get_post_meta($post->ID, '_colore_testo_bottone', true);
    $apertura_link               = get_post_meta($post->ID, '_apertura_link', true);
    $colore_bordo_bottone        = get_post_meta($post->ID, '_colore_bordo_bottone', true);
    $border_radius_bottone       = get_post_meta($post->ID, '_border_radius_bottone', true);
    $colore_hover_bg_bottone     = get_post_meta($post->ID, '_colore_hover_bg_bottone', true);
    $colore_hover_testo_bottone  = get_post_meta($post->ID, '_colore_hover_testo_bottone', true);
    $colore_hover_bordo_bottone  = get_post_meta($post->ID, '_colore_hover_bordo_bottone', true);

    // Stampiamo i campi in HTML
    echo '<div style="margin:0 0 20px;">';
    
    // Colore sfondo
    echo '<p><label><strong>Colore Sfondo</strong></label><br/>';
    echo '<input type="text" name="colore_sfondo" value="'.esc_attr($colore_sfondo).'" placeholder="#FFFFFF" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Tipologia immagine desktop
    echo '<p><label><strong>Tipologia Immagine Desktop</strong></label><br/>';
    echo '<select name="tipologia_immagine_desktop" style="min-width:200px;">';
    echo '  <option value="">-- Seleziona --</option>';
    echo '  <option value="destra" '.selected($tipologia_immagine_desktop, 'destra', false).'>Destra</option>';
    echo '  <option value="sinistra" '.selected($tipologia_immagine_desktop, 'sinistra', false).'>Sinistra</option>';
    echo '</select>';
    echo '</p>';

    // Tipologia immagine mobile
    echo '<p><label><strong>Tipologia Immagine Mobile</strong></label><br/>';
    echo '<select name="tipologia_immagine_mobile" style="min-width:200px;">';
    echo '  <option value="">-- Seleziona --</option>';
    echo '  <option value="destra" '.selected($tipologia_immagine_mobile, 'destra', false).'>Destra</option>';
    echo '  <option value="sinistra" '.selected($tipologia_immagine_mobile, 'sinistra', false).'>Sinistra</option>';
    echo '</select>';
    echo '</p>';

    // Immagine (URL)
    echo '<p><label><strong>Immagine (URL)</strong></label><br/>';
    echo '<input type="text" name="immagine" value="'.esc_attr($immagine).'" placeholder="https://..." style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Titolo
    echo '<p><label><strong>Titolo</strong></label><br/>';
    echo '<input type="text" name="titolo" value="'.esc_attr($titolo).'" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore titolo
    echo '<p><label><strong>Colore Titolo</strong></label><br/>';
    echo '<input type="text" name="colore_titolo" value="'.esc_attr($colore_titolo).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Descrizione (campo più ampio, es. textarea)
    echo '<p><label><strong>Descrizione</strong></label><br/>';
    echo '<textarea name="descrizione" rows="4" style="width:100%;max-width:400px;">'.esc_textarea($descrizione).'</textarea>';
    echo '</p>';

    // Colore descrizione
    echo '<p><label><strong>Colore Descrizione</strong></label><br/>';
    echo '<input type="text" name="colore_descrizione" value="'.esc_attr($colore_descrizione).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Testo bottone
    echo '<p><label><strong>Testo Bottone</strong></label><br/>';
    echo '<input type="text" name="testo_bottone" value="'.esc_attr($testo_bottone).'" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Link bottone
    echo '<p><label><strong>Link Bottone</strong></label><br/>';
    echo '<input type="url" name="link_bottone" value="'.esc_attr($link_bottone).'" placeholder="https://..." style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore bottone
    echo '<p><label><strong>Colore Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_bottone" value="'.esc_attr($colore_bottone).'" placeholder="#FFFFFF" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore testo bottone
    echo '<p><label><strong>Colore Testo Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_testo_bottone" value="'.esc_attr($colore_testo_bottone).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Apertura link (select)
    echo '<p><label><strong>Apertura Link</strong></label><br/>';
    echo '<select name="apertura_link" style="min-width:200px;">';
    echo '  <option value="">-- Seleziona --</option>';
    echo '  <option value="stessa finestra" '.selected($apertura_link, 'stessa finestra', false).'>Stessa finestra</option>';
    echo '  <option value="nuova finestra" '.selected($apertura_link, 'nuova finestra', false).'>Nuova finestra</option>';
    echo '</select>';
    echo '</p>';

    // Colore bordo bottone
    echo '<p><label><strong>Colore Bordo Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_bordo_bottone" value="'.esc_attr($colore_bordo_bottone).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Border radius bottone
    echo '<p><label><strong>Border Radius Bottone</strong></label><br/>';
    echo '<input type="text" name="border_radius_bottone" value="'.esc_attr($border_radius_bottone).'" placeholder="5px / 50% / etc." style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore hover BG bottone
    echo '<p><label><strong>Colore Hover BG Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_hover_bg_bottone" value="'.esc_attr($colore_hover_bg_bottone).'" placeholder="#CCCCCC" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore hover testo bottone
    echo '<p><label><strong>Colore Hover Testo Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_hover_testo_bottone" value="'.esc_attr($colore_hover_testo_bottone).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    // Colore hover bordo bottone
    echo '<p><label><strong>Colore Hover Bordo Bottone</strong></label><br/>';
    echo '<input type="text" name="colore_hover_bordo_bottone" value="'.esc_attr($colore_hover_bordo_bottone).'" placeholder="#000000" style="width:100%;max-width:400px;" />';
    echo '</p>';

    echo '</div><!-- /.banner-fields-container -->';
}

// 3. Salvataggio dei campi
add_action('save_post_banner', 'webperformer_save_banner_meta');
function webperformer_save_banner_meta($post_id) {
    // A. Sicurezza base
    if (!isset($_POST['webperformer_banner_nonce']) ||
        !wp_verify_nonce($_POST['webperformer_banner_nonce'], 'webperformer_save_banner_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // B. Aggiorna i campi (usa la sanitizzazione corretta per ognuno)
    if (isset($_POST['colore_sfondo'])) {
        update_post_meta($post_id, '_colore_sfondo', sanitize_text_field($_POST['colore_sfondo']));
    }
    if (isset($_POST['tipologia_immagine_desktop'])) {
        update_post_meta($post_id, '_tipologia_immagine_desktop', sanitize_text_field($_POST['tipologia_immagine_desktop']));
    }
    if (isset($_POST['tipologia_immagine_mobile'])) {
        update_post_meta($post_id, '_tipologia_immagine_mobile', sanitize_text_field($_POST['tipologia_immagine_mobile']));
    }
    if (isset($_POST['immagine'])) {
        update_post_meta($post_id, '_immagine', esc_url_raw($_POST['immagine']));
    }
    if (isset($_POST['titolo'])) {
        update_post_meta($post_id, '_titolo', sanitize_text_field($_POST['titolo']));
    }
    if (isset($_POST['colore_titolo'])) {
        update_post_meta($post_id, '_colore_titolo', sanitize_text_field($_POST['colore_titolo']));
    }
    if (isset($_POST['descrizione'])) {
        update_post_meta($post_id, '_descrizione', wp_kses_post($_POST['descrizione']));
    }
    if (isset($_POST['colore_descrizione'])) {
        update_post_meta($post_id, '_colore_descrizione', sanitize_text_field($_POST['colore_descrizione']));
    }
    if (isset($_POST['testo_bottone'])) {
        update_post_meta($post_id, '_testo_bottone', sanitize_text_field($_POST['testo_bottone']));
    }
    if (isset($_POST['link_bottone'])) {
        update_post_meta($post_id, '_link_bottone', esc_url_raw($_POST['link_bottone']));
    }
    if (isset($_POST['colore_bottone'])) {
        update_post_meta($post_id, '_colore_bottone', sanitize_text_field($_POST['colore_bottone']));
    }
    if (isset($_POST['colore_testo_bottone'])) {
        update_post_meta($post_id, '_colore_testo_bottone', sanitize_text_field($_POST['colore_testo_bottone']));
    }
    if (isset($_POST['apertura_link'])) {
        $allowed_aperture = array('stessa finestra', 'nuova finestra');
        $chosen = in_array($_POST['apertura_link'], $allowed_aperture) ? $_POST['apertura_link'] : '';
        update_post_meta($post_id, '_apertura_link', sanitize_text_field($chosen));
    }
    if (isset($_POST['colore_bordo_bottone'])) {
        update_post_meta($post_id, '_colore_bordo_bottone', sanitize_text_field($_POST['colore_bordo_bottone']));
    }
    if (isset($_POST['border_radius_bottone'])) {
        update_post_meta($post_id, '_border_radius_bottone', sanitize_text_field($_POST['border_radius_bottone']));
    }
    if (isset($_POST['colore_hover_bg_bottone'])) {
        update_post_meta($post_id, '_colore_hover_bg_bottone', sanitize_text_field($_POST['colore_hover_bg_bottone']));
    }
    if (isset($_POST['colore_hover_testo_bottone'])) {
        update_post_meta($post_id, '_colore_hover_testo_bottone', sanitize_text_field($_POST['colore_hover_testo_bottone']));
    }
    if (isset($_POST['colore_hover_bordo_bottone'])) {
        update_post_meta($post_id, '_colore_hover_bordo_bottone', sanitize_text_field($_POST['colore_hover_bordo_bottone']));
    }
}
