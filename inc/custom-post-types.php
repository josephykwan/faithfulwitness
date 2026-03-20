<?php
/**
 * Custom Post Types for Faithful Witness
 *
 * Registers:
 *   fw_initiative     — Initiative pages
 *   fw_organizing_group — Local organizing groups (map pins)
 *   fw_resource       — Individual resources (articles, guides, graphics)
 *   fw_event          — Events (local or national)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// INITIATIVES
// ============================================================
function fw_register_cpt_initiative() {
    $labels = [
        'name'               => __( 'Initiatives', 'faithfulwitness' ),
        'singular_name'      => __( 'Initiative', 'faithfulwitness' ),
        'menu_name'          => __( 'Initiatives', 'faithfulwitness' ),
        'add_new_item'       => __( 'Add New Initiative', 'faithfulwitness' ),
        'edit_item'          => __( 'Edit Initiative', 'faithfulwitness' ),
        'new_item'           => __( 'New Initiative', 'faithfulwitness' ),
        'view_item'          => __( 'View Initiative', 'faithfulwitness' ),
        'search_items'       => __( 'Search Initiatives', 'faithfulwitness' ),
        'not_found'          => __( 'No initiatives found', 'faithfulwitness' ),
        'not_found_in_trash' => __( 'No initiatives found in trash', 'faithfulwitness' ),
    ];

    register_post_type( 'fw_initiative', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-flag',
        'menu_position'      => 5,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ],
        'rewrite'            => [ 'slug' => 'initiatives', 'with_front' => false ],
        'show_in_rest'       => true,
        'hierarchical'       => false,
    ] );
}
add_action( 'init', 'fw_register_cpt_initiative' );

// ============================================================
// ORGANIZING GROUPS
// ============================================================
function fw_register_cpt_organizing_group() {
    $labels = [
        'name'               => __( 'Organizing Groups', 'faithfulwitness' ),
        'singular_name'      => __( 'Organizing Group', 'faithfulwitness' ),
        'menu_name'          => __( 'Organizing Groups', 'faithfulwitness' ),
        'add_new_item'       => __( 'Add New Organizing Group', 'faithfulwitness' ),
        'edit_item'          => __( 'Edit Organizing Group', 'faithfulwitness' ),
        'new_item'           => __( 'New Organizing Group', 'faithfulwitness' ),
        'view_item'          => __( 'View Organizing Group', 'faithfulwitness' ),
        'search_items'       => __( 'Search Organizing Groups', 'faithfulwitness' ),
        'not_found'          => __( 'No organizing groups found', 'faithfulwitness' ),
    ];

    register_post_type( 'fw_organizing_group', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-location-alt',
        'menu_position'      => 6,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'organizing-groups', 'with_front' => false ],
        'show_in_rest'       => true,
    ] );
}
add_action( 'init', 'fw_register_cpt_organizing_group' );

// ============================================================
// RESOURCES
// ============================================================
function fw_register_cpt_resource() {
    $labels = [
        'name'               => __( 'Resources', 'faithfulwitness' ),
        'singular_name'      => __( 'Resource', 'faithfulwitness' ),
        'menu_name'          => __( 'Resources', 'faithfulwitness' ),
        'add_new_item'       => __( 'Add New Resource', 'faithfulwitness' ),
        'edit_item'          => __( 'Edit Resource', 'faithfulwitness' ),
        'new_item'           => __( 'New Resource', 'faithfulwitness' ),
        'view_item'          => __( 'View Resource', 'faithfulwitness' ),
        'search_items'       => __( 'Search Resources', 'faithfulwitness' ),
        'not_found'          => __( 'No resources found', 'faithfulwitness' ),
    ];

    register_post_type( 'fw_resource', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-portfolio',
        'menu_position'      => 7,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'resources', 'with_front' => false ],
        'show_in_rest'       => true,
    ] );
}
add_action( 'init', 'fw_register_cpt_resource' );

// ============================================================
// EVENTS
// ============================================================
function fw_register_cpt_event() {
    $labels = [
        'name'               => __( 'Events', 'faithfulwitness' ),
        'singular_name'      => __( 'Event', 'faithfulwitness' ),
        'menu_name'          => __( 'Events', 'faithfulwitness' ),
        'add_new_item'       => __( 'Add New Event', 'faithfulwitness' ),
        'edit_item'          => __( 'Edit Event', 'faithfulwitness' ),
        'new_item'           => __( 'New Event', 'faithfulwitness' ),
        'view_item'          => __( 'View Event', 'faithfulwitness' ),
        'search_items'       => __( 'Search Events', 'faithfulwitness' ),
        'not_found'          => __( 'No events found', 'faithfulwitness' ),
    ];

    register_post_type( 'fw_event', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 8,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'            => [ 'slug' => 'events', 'with_front' => false ],
        'show_in_rest'       => true,
    ] );
}
add_action( 'init', 'fw_register_cpt_event' );
