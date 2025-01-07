<?php
if (!defined('ABSPATH')) {
    exit;
}

// Aggiunge la colonna "Shortcode"
add_filter('manage_banner_posts_columns', 'webperformer_banner_table_head');
function webperformer_banner_table_head($defaults) {
    $defaults['shortcode'] = 'Shortcode';
    return $defaults;
}

// Popola la colonna
add_action('manage_banner_posts_custom_column', 'webperformer_banner_table_content_shortcode', 10, 2);
function webperformer_banner_table_content_shortcode($column_name, $post_id) {
    if (get_post_type($post_id) === 'banner') {
        if ($column_name === 'shortcode') {
            echo '[banner id="'.$post_id.'"]';
        }
    }
}
