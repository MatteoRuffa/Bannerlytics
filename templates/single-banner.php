<?php
// File: single-banner.php

get_header(); 
?>

<main id="maincontent" role="main" class="single-banner">
    <?php while ( have_posts() ) : the_post();

        // Recupero l'ID del post
        $id = get_the_ID();
        $titolo = get_the_title(); // Recupera direttamente il titolo del post
        $descrizione = get_post_meta($id, '_banner_description', true); // Recupera la descrizione dal meta
        $testo_bottone = get_post_meta($id, '_banner_button_text', true); // Recupera il testo del pulsante
        $link_bottone = '#'; // Placeholder per ora

        // Inizializzo una variabile per costruire il markup
        $string = '';

        // l'HTML 
        $string .= '<article class="banner-wrapper">';
        $string .= '<div class="section">';
        $string .= '<div class="banner">';

        // Titolo del banner
        $string .= '<h2 class="banner-title">' . esc_html($titolo) . '</h2>';

        // Descrizione del banner
        $string .= '<p class="banner-description">' . esc_html($descrizione) . '</p>';

        // Pulsante del banner
        if ( ! empty( $testo_bottone ) ) {
            $string .= '<div class="banner-button-wrapper">';
            $string .= '<a href="' . esc_url($link_bottone) . '" class="banner-button">' . esc_html($testo_bottone) . '</a>';
            $string .= '</div>';
        }

        $string .= '</div>'; 
        $string .= '</div>'; 
        $string .= '</article>';

        // Stampa l’output finale
        echo $string;

    endwhile; ?>
</main>

<?php get_footer(); ?>