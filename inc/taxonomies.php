<?php
/**
 * Custom Taxonomies for Faithful Witness
 *
 * fw_initiative_category  — Shared category across initiatives, resources, posts
 * fw_resource_type        — Type of resource: article, guide, graphic, social
 * fw_state                — US state (for organizing groups + events)
 * fw_event_type           — local | national
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// INITIATIVE CATEGORY  (cross-CPT taxonomy)
// Applied to: fw_initiative, fw_resource, post, fw_event
// ============================================================
function fw_register_taxonomy_initiative_category() {
    $labels = [
        'name'              => __( 'Initiative Categories', 'faithfulwitness' ),
        'singular_name'     => __( 'Initiative Category', 'faithfulwitness' ),
        'search_items'      => __( 'Search Initiative Categories', 'faithfulwitness' ),
        'all_items'         => __( 'All Initiative Categories', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Initiative Category', 'faithfulwitness' ),
        'update_item'       => __( 'Update Initiative Category', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Initiative Category', 'faithfulwitness' ),
        'new_item_name'     => __( 'New Initiative Category Name', 'faithfulwitness' ),
        'menu_name'         => __( 'Initiative Categories', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_initiative_category', [
        'fw_initiative',
        'fw_resource',
        'post',
        'fw_event',
    ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'initiative-category' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_initiative_category' );

// ============================================================
// RESOURCE TYPE
// Applied to: fw_resource
// Values: article, daily-guide, graphic, social-media
// ============================================================
function fw_register_taxonomy_resource_type() {
    $labels = [
        'name'              => __( 'Resource Types', 'faithfulwitness' ),
        'singular_name'     => __( 'Resource Type', 'faithfulwitness' ),
        'search_items'      => __( 'Search Resource Types', 'faithfulwitness' ),
        'all_items'         => __( 'All Resource Types', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Resource Type', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Resource Type', 'faithfulwitness' ),
        'menu_name'         => __( 'Resource Types', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_resource_type', [ 'fw_resource' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'resource-type' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_resource_type' );

// ============================================================
// US STATE
// Applied to: fw_organizing_group, fw_event
// ============================================================
function fw_register_taxonomy_state() {
    $labels = [
        'name'              => __( 'States', 'faithfulwitness' ),
        'singular_name'     => __( 'State', 'faithfulwitness' ),
        'search_items'      => __( 'Search States', 'faithfulwitness' ),
        'all_items'         => __( 'All States', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit State', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New State', 'faithfulwitness' ),
        'menu_name'         => __( 'States', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_state', [ 'fw_organizing_group', 'fw_event' ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'state' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_state' );

// ============================================================
// EVENT TYPE
// Applied to: fw_event
// Values: local | national
// ============================================================
function fw_register_taxonomy_event_type() {
    $labels = [
        'name'              => __( 'Event Types', 'faithfulwitness' ),
        'singular_name'     => __( 'Event Type', 'faithfulwitness' ),
        'all_items'         => __( 'All Event Types', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Event Type', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Event Type', 'faithfulwitness' ),
        'menu_name'         => __( 'Event Types', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_event_type', [ 'fw_event' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'event-type' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_event_type' );

// ============================================================
// RESOURCE ISSUE AREA
// Applied to: fw_resource
// Values: immigration-due-process, sanctuary-sacred-spaces,
//         know-your-rights, spiritual-formation, policy-advocacy
// ============================================================
function fw_register_taxonomy_resource_issue_area() {
    $labels = [
        'name'              => __( 'Issue Areas', 'faithfulwitness' ),
        'singular_name'     => __( 'Issue Area', 'faithfulwitness' ),
        'search_items'      => __( 'Search Issue Areas', 'faithfulwitness' ),
        'all_items'         => __( 'All Issue Areas', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Issue Area', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Issue Area', 'faithfulwitness' ),
        'menu_name'         => __( 'Issue Areas', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_resource_issue_area', [ 'fw_resource' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'resource-issue' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_resource_issue_area' );

// ============================================================
// RESOURCE AUDIENCE
// Applied to: fw_resource
// Values: congregations, individual-christians,
//         church-leaders, directly-affected
// ============================================================
function fw_register_taxonomy_resource_audience() {
    $labels = [
        'name'              => __( 'Audiences', 'faithfulwitness' ),
        'singular_name'     => __( 'Audience', 'faithfulwitness' ),
        'search_items'      => __( 'Search Audiences', 'faithfulwitness' ),
        'all_items'         => __( 'All Audiences', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Audience', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Audience', 'faithfulwitness' ),
        'menu_name'         => __( 'Audiences', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_resource_audience', [ 'fw_resource' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'resource-audience' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_resource_audience' );

// ============================================================
// EVENT CATEGORY
// Applied to: fw_event
// Values: prayer-gathering, kyr-training, court-accompaniment,
//         community-formation, public-witness, webinar
// ============================================================
function fw_register_taxonomy_event_category() {
    $labels = [
        'name'              => __( 'Event Categories', 'faithfulwitness' ),
        'singular_name'     => __( 'Event Category', 'faithfulwitness' ),
        'all_items'         => __( 'All Event Categories', 'faithfulwitness' ),
        'edit_item'         => __( 'Edit Event Category', 'faithfulwitness' ),
        'add_new_item'      => __( 'Add New Event Category', 'faithfulwitness' ),
        'menu_name'         => __( 'Event Categories', 'faithfulwitness' ),
    ];

    register_taxonomy( 'fw_event_category', [ 'fw_event' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'event-category' ],
    ] );
}
add_action( 'init', 'fw_register_taxonomy_event_category' );
