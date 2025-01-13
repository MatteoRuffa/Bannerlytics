<?php
get_header(); 
?>

<main id="maincontent" role="main" class="single-banner">
    <?php while ( have_posts() ) : the_post();

        // Recupero l'ID del post
        $id = get_the_ID();
        $colore_sfondo               = get_post_meta($id, '_colore_sfondo', true);
        $tipologia_immagine_desktop  = get_post_meta($id, '_tipologia_immagine_desktop', true);
        $tipologia_immagine_mobile   = get_post_meta($id, '_tipologia_immagine_mobile', true);
        $immagine                    = get_post_meta($id, '_immagine', true);
        $titolo                      = get_post_meta($id, '_titolo', true);
        $colore_titolo               = get_post_meta($id, '_colore_titolo', true);
        $descrizione                 = get_post_meta($id, '_descrizione', true);
        $colore_descrizione          = get_post_meta($id, '_colore_descrizione', true);
        $testo_bottone               = get_post_meta($id, '_testo_bottone', true);
        $link_bottone                = get_post_meta($id, '_link_bottone', true);
        $colore_bottone              = get_post_meta($id, '_colore_bottone', true);
        $colore_testo_bottone        = get_post_meta($id, '_colore_testo_bottone', true);
        $apertura_link               = get_post_meta($id, '_apertura_link', true);

        // Campi aggiuntivi per lo stile del bottone
        $colore_bordo_bottone       = get_post_meta($id, '_colore_bordo_bottone', true);
        $border_radius_bottone      = get_post_meta($id, '_border_radius_bottone', true);
        $colore_hover_bg_bottone    = get_post_meta($id, '_colore_hover_bg_bottone', true);
        $colore_hover_testo_bottone = get_post_meta($id, '_colore_hover_testo_bottone', true);
        $colore_hover_bordo_bottone = get_post_meta($id, '_colore_hover_bordo_bottone', true);

        // Campo aggiuntivo per layout banner
        $larghezza_banner           = get_post_meta($id, '_larghezza_banner', true) ?: 960;
        $border_radius_banner       = get_post_meta($id, '_border_radius_banner', true) ?: 5;
        $box_shadow_banner          = get_post_meta($id, '_box_shadow_banner', true) ?: '10px';

        // Se l'utente sceglie "nuova finestra"
        $target = ($apertura_link === 'nuova finestra') ? "target='_blank'" : '';

        // Verifica dei valori non nulli prima dell'uso
        $colore_hover_bordo_bottone = $colore_hover_bordo_bottone ?: '#000';

        // Stessa concatenazione di stringhe di prima:
        $string = '<style>
        .banner-' . esc_attr($id) . ' .button {
            border: 1px solid ' . esc_attr($colore_bordo_bottone) . ';
            border-radius: ' . esc_attr($border_radius_bottone) . ';
        }
        .banner-' . esc_attr($id) . ' .button:hover {
            background-color: ' . esc_attr($colore_hover_bg_bottone) . ' !important;
            color: ' . esc_attr($colore_hover_testo_bottone) . ' !important;
            border-color: ' . esc_attr($colore_hover_bordo_bottone) . ' !important;
        }
        </style>';

        // Costruisco il markup HTML del banner
        $string .= '<div class="banner banner-' . esc_attr($id) . '" style="background:' . esc_attr($colore_sfondo) . '; width:' . esc_attr($larghezza_banner) . 'px; margin: 0 auto; border-radius:' . esc_attr($border_radius_banner) . '; box-shadow:' . esc_attr($box_shadow_banner) . ';">
            <div class="card-banner">
                <div class="grid">
                    <div class="width-60">
                        <div class="position-relative ' . esc_attr($tipologia_immagine_desktop) . '">
                            <div class="text-center">
                                <div class="title-banner text-left-m" style="color:' . esc_attr($colore_titolo) . '; font-size: 30px; line-height: 1.2;">
                                    ' . wp_kses_post($titolo) . '
                                </div>
                                <div class="margin-medium-bottom text-left-m" style="color:' . esc_attr($colore_descrizione) . ';">
                                    ' . wp_kses_post($descrizione) . '
                                </div>
                                <div class="margin-medium-bottom text-center text-left-m">
                                    <a href="' . esc_url($link_bottone) . '" ' . $target . ' class="banner-' . esc_attr($id) . ' button"
                                        style="background:' . esc_attr($colore_bottone) . '; color:' . esc_attr($colore_testo_bottone) . ';">
                                        ' . esc_html($testo_bottone) . '
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>';

        // Gestione dell'immagine a destra/sinistra in base ai campi
        if ($tipologia_immagine_desktop === 'destra' && $tipologia_immagine_mobile === 'destra') {
            $string .= '<div class="width-40">';
        } elseif ($tipologia_immagine_desktop === 'destra' && $tipologia_immagine_mobile === 'sinistra') {
            $string .= '<div class="width-40 first last-m">';
        } elseif ($tipologia_immagine_desktop === 'sinistra' && $tipologia_immagine_mobile === 'sinistra') {
            $string .= '<div class="width-40 first">';
        } elseif ($tipologia_immagine_desktop === 'sinistra' && $tipologia_immagine_mobile === 'destra') {
            $string .= '<div class="width-40 first-m">';
        }

        $string .= '<img src="' . esc_url($immagine) . '" alt="Banner image" loading="lazy" class="banner-image">';
        $string .= '</div>'; 
        $string .= '</div>'; 
        $string .= '</div>'; 
        $string .= '</div>'; 

        // Stampa l'output finale
        echo $string;

    endwhile; ?>
</main>

<?php get_footer(); ?>
