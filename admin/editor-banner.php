<?php
if (!defined('ABSPATH')) {
    exit; 
}

function bannerlytics_editor_banner_page() {
    // 1. Se il form è stato inviato in POST, gestisco salvataggio/aggiornamento
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        check_admin_referer('bannerlytics_editor_nonce_action', 'bannerlytics_editor_nonce');

        $banner_id = isset($_POST['banner_id']) ? intval($_POST['banner_id']) : 0;

        // Recupero e "pulisco" tutti i campi
        $colore_sfondo               = sanitize_text_field($_POST['colore_sfondo'] ?? '');
        $tipologia_immagine_desktop  = sanitize_text_field($_POST['tipologia_immagine_desktop'] ?? '');
        $tipologia_immagine_mobile   = sanitize_text_field($_POST['tipologia_immagine_mobile'] ?? '');
        $immagine                    = esc_url_raw($_POST['immagine'] ?? '');
        $titolo                      = sanitize_text_field($_POST['titolo'] ?? '');
        $colore_titolo               = sanitize_text_field($_POST['colore_titolo'] ?? '');
        $descrizione                 = wp_kses_post($_POST['descrizione'] ?? '');
        $colore_descrizione          = sanitize_text_field($_POST['colore_descrizione'] ?? '');
        $testo_bottone               = sanitize_text_field($_POST['testo_bottone'] ?? '');
        $link_bottone                = esc_url_raw($_POST['link_bottone'] ?? '');
        $colore_bottone              = sanitize_text_field($_POST['colore_bottone'] ?? '');
        $colore_testo_bottone        = sanitize_text_field($_POST['colore_testo_bottone'] ?? '');
        $apertura_link               = sanitize_text_field($_POST['apertura_link'] ?? '');
        $colore_bordo_bottone        = sanitize_text_field($_POST['colore_bordo_bottone'] ?? '');
        $border_radius_bottone       = sanitize_text_field($_POST['border_radius_bottone'] ?? '');
        $colore_hover_bg_bottone     = sanitize_text_field($_POST['colore_hover_bg_bottone'] ?? '');
        $colore_hover_testo_bottone  = sanitize_text_field($_POST['colore_hover_testo_bottone'] ?? '');
        $colore_hover_bordo_bottone  = sanitize_text_field($_POST['colore_hover_bordo_bottone'] ?? '');
        $larghezza_banner            = intval($_POST['larghezza_banner'] ?? 960);
        $border_radius_banner        = sanitize_text_field($_POST['border_radius_banner'] ?? '5px');
        $box_shadow_banner           = sanitize_text_field($_POST['box_shadow_banner'] ?? '0px 0px 4px 1px rgba(0, 0, 0, 0.1)');

        if ($banner_id > 0) {
            // Aggiorno il post esistente
            wp_update_post(array(
                'ID'         => $banner_id,
                'post_title' => $titolo,
            ));
        } else {
            // Creo un nuovo post di tipo 'banner'
            $banner_id = wp_insert_post(array(
                'post_type'   => 'banner',
                'post_title'  => $titolo,
                'post_status' => 'publish',
            ));
        }

        // Salvo i meta
        update_post_meta($banner_id, '_colore_sfondo',               $colore_sfondo);
        update_post_meta($banner_id, '_tipologia_immagine_desktop',  $tipologia_immagine_desktop);
        update_post_meta($banner_id, '_tipologia_immagine_mobile',   $tipologia_immagine_mobile);
        update_post_meta($banner_id, '_immagine',                    $immagine);
        update_post_meta($banner_id, '_titolo',                      $titolo);
        update_post_meta($banner_id, '_colore_titolo',               $colore_titolo);
        update_post_meta($banner_id, '_descrizione',                 $descrizione);
        update_post_meta($banner_id, '_colore_descrizione',          $colore_descrizione);
        update_post_meta($banner_id, '_testo_bottone',               $testo_bottone);
        update_post_meta($banner_id, '_link_bottone',                $link_bottone);
        update_post_meta($banner_id, '_colore_bottone',              $colore_bottone);
        update_post_meta($banner_id, '_colore_testo_bottone',        $colore_testo_bottone);
        update_post_meta($banner_id, '_apertura_link',               $apertura_link);
        update_post_meta($banner_id, '_colore_bordo_bottone',        $colore_bordo_bottone);
        update_post_meta($banner_id, '_border_radius_bottone',       $border_radius_bottone);
        update_post_meta($banner_id, '_colore_hover_bg_bottone',     $colore_hover_bg_bottone);
        update_post_meta($banner_id, '_colore_hover_testo_bottone',  $colore_hover_testo_bottone);
        update_post_meta($banner_id, '_colore_hover_bordo_bottone',  $colore_hover_bordo_bottone);
        update_post_meta($banner_id, '_larghezza_banner',            $larghezza_banner);
        update_post_meta($banner_id, '_border_radius_banner',        $border_radius_banner);
        update_post_meta($banner_id, '_box_shadow_banner',           $box_shadow_banner);

        // Redirect per non reinviare i dati con il refresh
        wp_safe_redirect(add_query_arg(array(
            'page'      => 'bannerlytics-editor-banner',
            'banner_id' => $banner_id,
            'updated'   => 'true'
        ), admin_url('admin.php')));
        exit;  
    }

    // 2. Carico i valori solo dopo il salvataggio e se esiste un ID banner
    $banner_id = isset($_GET['banner_id']) ? intval($_GET['banner_id']) : 0;

    $colore_sfondo               = '';
    $tipologia_immagine_desktop  = '';
    $tipologia_immagine_mobile   = '';
    $immagine                    = '';
    $titolo                      = '';
    $colore_titolo               = '';
    $descrizione                 = '';
    $colore_descrizione          = '';
    $testo_bottone               = '';
    $link_bottone                = '';
    $colore_bottone              = '';
    $colore_testo_bottone        = '';
    $apertura_link               = '';
    $colore_bordo_bottone        = '';
    $border_radius_bottone       = '';
    $colore_hover_bg_bottone     = '';
    $colore_hover_testo_bottone  = '';
    $colore_hover_bordo_bottone  = '';
    $larghezza_banner            = 960;
    $border_radius_banner        = '5px';
    $box_shadow_banner           = '0px 0px 4px 1px rgba(0, 0, 0, 0.1)';

    if ($banner_id > 0) {
        // Se esiste, carico i meta
        $colore_sfondo               = get_post_meta($banner_id, '_colore_sfondo', true);
        $tipologia_immagine_desktop  = get_post_meta($banner_id, '_tipologia_immagine_desktop', true);
        $tipologia_immagine_mobile   = get_post_meta($banner_id, '_tipologia_immagine_mobile', true);
        $immagine                    = get_post_meta($banner_id, '_immagine', true);
        $titolo                      = get_post_meta($banner_id, '_titolo', true);
        $colore_titolo               = get_post_meta($banner_id, '_colore_titolo', true);
        $descrizione                 = get_post_meta($banner_id, '_descrizione', true);
        $colore_descrizione          = get_post_meta($banner_id, '_colore_descrizione', true);
        $testo_bottone               = get_post_meta($banner_id, '_testo_bottone', true);
        $link_bottone                = get_post_meta($banner_id, '_link_bottone', true);
        $colore_bottone              = get_post_meta($banner_id, '_colore_bottone', true);
        $colore_testo_bottone        = get_post_meta($banner_id, '_colore_testo_bottone', true);
        $apertura_link               = get_post_meta($banner_id, '_apertura_link', true);
        $colore_bordo_bottone        = get_post_meta($banner_id, '_colore_bordo_bottone', true);
        $border_radius_bottone       = get_post_meta($banner_id, '_border_radius_bottone', true);
        $colore_hover_bg_bottone     = get_post_meta($banner_id, '_colore_hover_bg_bottone', true);
        $colore_hover_testo_bottone  = get_post_meta($banner_id, '_colore_hover_testo_bottone', true);
        $colore_hover_bordo_bottone  = get_post_meta($banner_id, '_colore_hover_bordo_bottone', true);
        $larghezza_banner            = get_post_meta($banner_id, '_larghezza_banner', true) ?: 960;
        $border_radius_banner        = get_post_meta($banner_id, '_border_radius_banner', true) ?: '5px';
        $box_shadow_banner           = get_post_meta($banner_id, '_box_shadow_banner', true) ?: '0px 0px 4px 1px rgba(0, 0, 0, 0.1)';
    }

    // 3. Stampo la UI
    ?>
    <div class="wrap">
        <h1>
            <?php echo ($banner_id > 0) ? 'Modifica Banner #'.$banner_id : 'Crea Nuovo Banner'; ?>
        </h1>

        <!-- Messaggio di conferma post-update -->
        <?php if (isset($_GET['updated'])): ?>
            <div class="notice notice-success is-dismissible">
                <p>Banner aggiornato con successo!</p>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <?php wp_nonce_field('bannerlytics_editor_nonce_action', 'bannerlytics_editor_nonce'); ?>
            <input type="hidden" name="banner_id" value="<?php echo esc_attr($banner_id); ?>">

            <!-- RIGA 1 -->
            <div style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <p><label><strong>Colore Sfondo</strong></label><br/>
                    <input type="text" name="colore_sfondo" value="<?php echo esc_attr($colore_sfondo); ?>" placeholder="#FFFFFF" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Tipologia Immagine Desktop</strong></label><br/>
                    <select name="tipologia_immagine_desktop" style="width:100%;">
                        <option value="">-- Seleziona --</option>
                        <option value="destra"   <?php selected($tipologia_immagine_desktop, 'destra');   ?>>Destra</option>
                        <option value="sinistra" <?php selected($tipologia_immagine_desktop, 'sinistra'); ?>>Sinistra</option>
                    </select>
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Tipologia Immagine Mobile</strong></label><br/>
                    <select name="tipologia_immagine_mobile" style="width:100%;">
                        <option value="">-- Seleziona --</option>
                        <option value="destra"   <?php selected($tipologia_immagine_mobile, 'destra');   ?>>Destra</option>
                        <option value="sinistra" <?php selected($tipologia_immagine_mobile, 'sinistra'); ?>>Sinistra</option>
                    </select>
                    </p>
                </div>
            </div> <!-- Fine RIGA 1 -->

            <!-- RIGA 2 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1;">
                    <p><label><strong>Immagine</strong></label><br/></p>
                    <div class="banner-image-preview" style="margin-bottom: 10px;">
                        <?php if (!empty($immagine)): ?>
                            <img id="banner-image-preview" src="<?php echo esc_url($immagine); ?>" alt="" style="max-width: 350px; height: auto; display: block;">
                        <?php else: ?>
                            <img id="banner-image-preview" src="#" alt="" style="max-width: 350px; height: auto; display: none;">
                        <?php endif; ?>
                    </div>
                    <input type="text" id="banner-image-url" name="immagine" value="<?php echo esc_attr($immagine); ?>" style="width:100%; margin-bottom:10px;">
                    <button type="button" class="button select-banner-image">Seleziona immagine</button>
                    <button type="button" class="button remove-banner-image" style="display:none;">Rimuovi immagine</button>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Titolo</strong></label><br/>
                    <input type="text" name="titolo" value="<?php echo esc_attr($titolo); ?>" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Titolo</strong></label><br/>
                    <input type="text" name="colore_titolo" value="<?php echo esc_attr($colore_titolo); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 2 -->

            <!-- RIGA 3 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1;">
                    <p><label><strong>Descrizione</strong></label><br/>
                    <textarea name="descrizione" rows="4" style="width:100%;"><?php echo esc_textarea($descrizione); ?></textarea>
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Descrizione</strong></label><br/>
                    <input type="text" name="colore_descrizione" value="<?php echo esc_attr($colore_descrizione); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Testo Bottone</strong></label><br/>
                    <input type="text" name="testo_bottone" value="<?php echo esc_attr($testo_bottone); ?>" style="width:100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 3 -->

            <!-- RIGA 4 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1;">
                    <p><label><strong>Link Bottone</strong></label><br/>
                    <input type="url" name="link_bottone" value="<?php echo esc_attr($link_bottone); ?>" placeholder="https://..." style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Bottone</strong></label><br/>
                    <input type="text" name="colore_bottone" value="<?php echo esc_attr($colore_bottone); ?>" placeholder="#FFFFFF" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Testo Bottone</strong></label><br/>
                    <input type="text" name="colore_testo_bottone" value="<?php echo esc_attr($colore_testo_bottone); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 4 -->

            <!-- RIGA 5 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1;">
                    <p><label><strong>Apertura Link</strong></label><br/>
                    <select name="apertura_link" style="width:100%;">
                        <option value="">-- Seleziona --</option>
                        <option value="stessa finestra" <?php selected($apertura_link, 'stessa finestra'); ?>>Stessa finestra</option>
                        <option value="nuova finestra"  <?php selected($apertura_link, 'nuova finestra'); ?>>Nuova finestra</option>
                    </select>
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Bordo Bottone</strong></label><br/>
                    <input type="text" name="colore_bordo_bottone" value="<?php echo esc_attr($colore_bordo_bottone); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Border Radius Bottone</strong></label><br/>
                    <input type="text" name="border_radius_bottone" value="<?php echo esc_attr($border_radius_bottone); ?>" placeholder="5px / 50% / etc." style="width:100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 5 -->

            <!-- RIGA 6 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1;">
                    <p><label><strong>Colore Hover BG Bottone</strong></label><br/>
                    <input type="text" name="colore_hover_bg_bottone" value="<?php echo esc_attr($colore_hover_bg_bottone); ?>" placeholder="#CCCCCC" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Hover Testo Bottone</strong></label><br/>
                    <input type="text" name="colore_hover_testo_bottone" value="<?php echo esc_attr($colore_hover_testo_bottone); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>

                <div style="flex:1;">
                    <p><label><strong>Colore Hover Bordo Bottone</strong></label><br/>
                    <input type="text" name="colore_hover_bordo_bottone" value="<?php echo esc_attr($colore_hover_bordo_bottone); ?>" placeholder="#000000" style="width:100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 6 -->

            <!-- RIGA 7 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex:1; display: flex; flex-direction: column; align-items: center;">
                    <p style="width: 100%; text-align: center;"><label><strong>Larghezza Banner</strong></label></p>
                    <input type="range" name="larghezza_banner" min="350" max="1200" value="<?php echo esc_attr($larghezza_banner); ?>" style="width: 70%; height: 8px;" 
                           oninput="document.getElementById('banner-width-output').textContent = this.value + 'px';" />
                    <span id="banner-width-output" style="font-size: 16px; margin-top: 10px;">
                        <?php echo esc_html($larghezza_banner . 'px'); ?>
                    </span>
                </div>
            </div> <!-- Fine RIGA 7 -->

            <!-- RIGA 8 -->
            <div style="display:flex; gap:20px; margin-top:20px;">
                <div style="flex: 1;">
                    <p><label><strong>Border Radius (px)</strong></label><br/>
                    <input type="text" name="border_radius_banner" value="<?php echo esc_attr($border_radius_banner); ?>" placeholder="5px" style="width: 100%;" />
                    </p>
                </div>

                <div style="flex: 1;">
                    <p><label><strong>Box Shadow</strong></label><br/>
                    <input type="text" name="box_shadow_banner" value="<?php echo esc_attr($box_shadow_banner); ?>" 
                           placeholder="0px 0px 4px 1px rgba(0, 0, 0, 0.1)" style="width: 100%;" />
                    </p>
                </div>
            </div> <!-- Fine RIGA 8 -->

            <?php submit_button($banner_id ? 'Aggiorna Banner' : 'Crea Banner'); ?>
        </form>
    </div>
    <?php
}


// Aggancio uno script SOLO se siamo nella pagina “Editor Banner”
add_action('admin_enqueue_scripts', 'bannerlytics_editor_enqueue_scripts');
function bannerlytics_editor_enqueue_scripts($hook_suffix) {
    // Controllo che la pagina corrente sia la “Editor Banner”
    if (isset($_GET['page']) && $_GET['page'] === 'bannerlytics-editor-banner') {
        // Carico la media library di WP (necessaria per “Seleziona immagine”)
        wp_enqueue_media();
        
        // Carico lo script personalizzato
        // e la cartella assets è “plugin-root/assets/”, allora:
        wp_enqueue_script(
            'bannerlytics-preview',
            plugin_dir_url(__FILE__) . '../assets/js/metabox-preview.js',
            array('jquery'),
            '1.0',
            true
        );
    }
}