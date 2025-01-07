<?php
if (!defined('ABSPATH')) {
    exit; // Protezione contro accessi diretti
}

// create shortcode
add_shortcode('banner', 'post_banner_shortcode');
function post_banner_shortcode($atts) {
    ob_start();

    // Stesse opzioni di prima: definisci i parametri di default dello shortcode
    $atts = shortcode_atts(
        array(
            'type'    => 'banner', // Il CPT di default
            'order'   => 'date',   // Ordine
            'orderby' => 'title',  // Campo di ordinamento
            'posts'   => 1,        // Numero di banner da mostrare
            'id'      => '',       // Se specificato, mostra un banner con ID particolare
        ),
        $atts
    );

    // Costruisco l'array di query
    $options = array(
        'post_type'      => $atts['type'],
        'order'          => $atts['order'],
        'orderby'        => $atts['orderby'],
        'posts_per_page' => $atts['posts'],
        'p'              => $atts['id'],
    );

    $query = new WP_Query($options);
    $string = '';

    if ($query->have_posts()) {
        while($query->have_posts()) {
            $query->the_post();

            // Recupero i campi dal post_meta (invece che da get_field())
            $post_id                     = get_the_ID();
            $colore_sfondo               = get_post_meta($post_id, '_colore_sfondo', true);
            $tipologia_immagine_desktop  = get_post_meta($post_id, '_tipologia_immagine_desktop', true);
            $tipologia_immagine_mobile   = get_post_meta($post_id, '_tipologia_immagine_mobile', true);
            $immagine                    = get_post_meta($post_id, '_immagine', true);
            $titolo                      = get_post_meta($post_id, '_titolo', true);
            $colore_titolo               = get_post_meta($post_id, '_colore_titolo', true);
            $descrizione                 = get_post_meta($post_id, '_descrizione', true);
            $colore_descrizione          = get_post_meta($post_id, '_colore_descrizione', true);
            $testo_bottone               = get_post_meta($post_id, '_testo_bottone', true);
            $link_bottone                = get_post_meta($post_id, '_link_bottone', true);
            $colore_bottone              = get_post_meta($post_id, '_colore_bottone', true);
            $colore_testo_bottone        = get_post_meta($post_id, '_colore_testo_bottone', true);
            $apertura_link               = get_post_meta($post_id, '_apertura_link', true);
            $colore_bordo_bottone        = get_post_meta($post_id, '_colore_bordo_bottone', true);
            $border_radius_bottone       = get_post_meta($post_id, '_border_radius_bottone', true);
            $colore_hover_bg_bottone     = get_post_meta($post_id, '_colore_hover_bg_bottone', true);
            $colore_hover_testo_bottone  = get_post_meta($post_id, '_colore_hover_testo_bottone', true);
            $colore_hover_bordo_bottone  = get_post_meta($post_id, '_colore_hover_bordo_bottone', true);

            // Imposta il target del link (nuova finestra / stessa finestra)
            $target = '';
            if ($apertura_link === 'nuova finestra') {
                $target = "target='__blank'";
            }

            // Stessa concatenazione di stringhe di prima:
            // (manteniamo il CSS inline, la gestione di "destra"/"sinistra" ecc.)
            $string .= '<style>
            .banner-'. $post_id .' .button {
                border: 1px solid '.esc_attr($colore_bordo_bottone).';
                border-radius: '.esc_attr($border_radius_bottone).';
            }
            .banner-'. $post_id .' .button:hover {
                background-color: '.esc_attr($colore_hover_bg_bottone).' !important;
                color: '.esc_attr($colore_hover_testo_bottone).' !important;
                border-color: '.esc_attr($colore_hover_bordo_bottone).' !important;
            }
            </style>';

            // Costruisco il markup HTML del banner
            $string .= '<div class="banner banner-'. $post_id .'" style="background:'.esc_attr($colore_sfondo).';">
                <div class="card-banner">
                    <div class="grid">
                        <div class="width-60">
                            <div class="position-relative '.esc_attr($tipologia_immagine_desktop).'">
                                <div class="text-center">
                                    <div class="title-banner text-left-m" style="color:'.esc_attr($colore_titolo).'; font-size: 30px; line-height: 1.2;">'
                                        . wp_kses_post($titolo) .
                                    '</div>
                                    <div class="margin-medium-bottom text-left-m" style="color:'.esc_attr($colore_descrizione).';">'
                                        . wp_kses_post($descrizione) .
                                    '</div>
                                    <div class="margin-medium-bottom text-center text-left-m">
                                        <a href="'.esc_url($link_bottone).'" '.$target.' class="banner-'. $post_id .' button"
                                            style="background:'.esc_attr($colore_bottone).'; color:'.esc_attr($colore_testo_bottone).';">'
                                            . esc_html($testo_bottone) .
                                        '</a>
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

            $string .= '<img src="'.esc_url($immagine).'" alt="" loading="lazy">';
            $string .= '</div>'; // .width-40
            $string .= '</div>'; // .grid
            $string .= '</div>'; // .card-banner
            $string .= '</div>'; // .banner
        }
    }

    wp_reset_postdata();
    return $string;
}
