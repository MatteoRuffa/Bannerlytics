<?php
if ( ! defined('ABSPATH') ) {
    exit;
}

function webperformer_custom_banner(){
    $labels = array(
        'name'          => 'Banner',
        'singular_name' => 'Banner',
        'menu_name'     => 'Banners',
        'add_new'       => 'Aggiungi Nuovo',
        'all_items'     => 'Tutti i Banners',
        'add_new_item'  => 'Nuovo Banner',
        'edit_item'     => 'Modifica',
        'new_item'      => 'Nuovo Banner',
        'view_item'     => 'Visualizza',
        'search_item'   => 'Ricerca',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'has_archive'        => false,
        'publicly_queryable' => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'banner', 'with_front' => false),
        'capability_type'    => 'post',
        'hierarchical'       => true,
        'supports'           => array(
            'title',
            'editor',
            'excerpt',
            'comments',
            'custom-fields',
            'thumbnail',
            'page-attributes'
        ),
        'show_in_rest'       => true,
        'taxonomies'         => array('banner-types'),
        'menu_position'      => 12,
        'menu_icon'          => 'dashicons-welcome-widgets-menus',
        'exclude_from_search'=> false
    );

    register_post_type('banner', $args);

    // Richiama le rewrite rules quando registri un nuovo CPT.
    flush_rewrite_rules();
}

add_action('init', 'webperformer_custom_banner');
