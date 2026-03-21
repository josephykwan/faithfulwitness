<?php
/**
 * Faithful Witness — functions.php
 *
 * Theme setup and includes.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ============================================================
// THEME SETUP
// ============================================================
function fw_theme_setup() {
    load_theme_textdomain( 'faithfulwitness', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ] );

    // Image sizes
    add_image_size( 'fw-hero',    1600, 600, true );
    add_image_size( 'fw-card',    600,  400, true );
    add_image_size( 'fw-square',  400,  400, true );
    add_image_size( 'fw-thumb',   300,  200, true );

    // Nav menus
    register_nav_menus( [
        'primary'      => __( 'Primary Navigation', 'faithfulwitness' ),
        'footer-1'     => __( 'Footer Column 1 — Our Work',    'faithfulwitness' ),
        'footer-2'     => __( 'Footer Column 2 — Get Involved','faithfulwitness' ),
        'footer-3'     => __( 'Footer Column 3 — Resources',   'faithfulwitness' ),
        'footer-legal' => __( 'Footer Legal Links',             'faithfulwitness' ),
    ] );
}
add_action( 'after_setup_theme', 'fw_theme_setup' );

// ============================================================
// WIDGET AREAS
// ============================================================
function fw_register_sidebars() {
    register_sidebar( [
        'name'          => __( 'Blog Sidebar', 'faithfulwitness' ),
        'id'            => 'blog-sidebar',
        'before_widget' => '<div class="sidebar-widget" id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="sidebar-widget__title">',
        'after_title'   => '</h3>',
    ] );
    register_sidebar( [
        'name'          => __( 'Initiative Sidebar', 'faithfulwitness' ),
        'id'            => 'initiative-sidebar',
        'before_widget' => '<div class="sidebar-widget" id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="sidebar-widget__title">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'fw_register_sidebars' );

// ============================================================
// INCLUDES
// ============================================================
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/helper-functions.php';
require_once get_template_directory() . '/inc/shortcodes.php';
require_once get_template_directory() . '/inc/page-setup.php';
require_once get_template_directory() . '/inc/acf-fields.php';
require_once get_template_directory() . '/inc/admin-menu.php';

// ============================================================
// CONTENT WIDTH
// ============================================================
if ( ! isset( $content_width ) ) {
    $content_width = 1200;
}

// ============================================================
// EXCERPT LENGTH
// ============================================================
function fw_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'fw_excerpt_length' );

function fw_excerpt_more( $more ) {
    return '…';
}
add_filter( 'excerpt_more', 'fw_excerpt_more' );

// ============================================================
// CLEAN UP wp_head
// ============================================================
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// ============================================================
// REST API — expose meta fields
// ============================================================
function fw_register_rest_meta() {
    $og_fields = [
        'fw_city', 'fw_state_abbr', 'fw_lat', 'fw_lng',
        'fw_contact_email', 'fw_contact_phone', 'fw_website_url',
        'fw_instagram_url', 'fw_facebook_url', 'fw_twitter_url', 'fw_org_type',
    ];
    foreach ( $og_fields as $field ) {
        register_post_meta( 'fw_organizing_group', $field, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => '__return_true',
        ] );
    }

    // Media hit fields
    $hit_fields = [ 'fw_media_outlet', 'fw_media_url', 'fw_media_pub_date', 'fw_media_pull_quote', 'fw_media_outlet_logo_url' ];
    foreach ( $hit_fields as $field ) {
        register_post_meta( 'fw_media_hit', $field, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => 'string',
            'auth_callback' => '__return_true',
        ] );
    }
}
add_action( 'init', 'fw_register_rest_meta' );

// ============================================================
// CUSTOM PAGE TEMPLATES — register via filter
// ============================================================
function fw_register_page_templates( $templates ) {
    $templates['page-templates/template-map.php']                = __( 'Organizing Map',       'faithfulwitness' );
    $templates['page-templates/template-resources.php']          = __( 'Resource Library',     'faithfulwitness' );
    $templates['page-templates/template-take-action.php']        = __( 'Take Action',           'faithfulwitness' );
    $templates['page-templates/template-know-your-rights.php']   = __( 'Know Your Rights',      'faithfulwitness' );
    $templates['page-templates/template-network.php']            = __( 'Find Your Network',     'faithfulwitness' );
    $templates['page-templates/template-stories.php']            = __( 'Stories',               'faithfulwitness' );
    $templates['page-templates/template-spiritual-formation.php']= __( 'Spiritual Formation',   'faithfulwitness' );
    $templates['page-templates/template-news.php']               = __( 'News & Media',           'faithfulwitness' );
    $templates['page-templates/template-events.php']             = __( 'Events Calendar',        'faithfulwitness' ); // replaces old "Events Page" registration
    return $templates;
}
add_filter( 'theme_page_templates', 'fw_register_page_templates' );

// ============================================================
// FLUSH REWRITE RULES ON ACTIVATION
// ============================================================
function fw_flush_rewrites() {
    fw_register_cpt_initiative();
    fw_register_cpt_organizing_group();
    fw_register_cpt_resource();
    fw_register_cpt_event();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'fw_flush_rewrites' );

// ============================================================
// ADMIN COLUMNS — Event date in events list
// ============================================================
function fw_event_columns( $columns ) {
    $new = [];
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['fw_event_date']  = __( 'Date', 'faithfulwitness' );
            $new['fw_event_scope'] = __( 'Scope', 'faithfulwitness' );
        }
    }
    return $new;
}
add_filter( 'manage_fw_event_posts_columns', 'fw_event_columns' );

function fw_event_column_content( $column, $post_id ) {
    if ( $column === 'fw_event_date' ) {
        echo esc_html( fw_get_event_date( $post_id ) );
    }
    if ( $column === 'fw_event_scope' ) {
        $scope = get_post_meta( $post_id, 'fw_event_scope', true );
        $class = $scope === 'national' ? 'tag--national' : 'tag--local';
        echo '<span class="tag ' . esc_attr( $class ) . '">' . esc_html( ucfirst( $scope ?: 'national' ) ) . '</span>';
    }
}
add_action( 'manage_fw_event_posts_custom_column', 'fw_event_column_content', 10, 2 );

// ============================================================
// ADMIN COLUMNS — Organizing group city/state
// ============================================================
function fw_og_columns( $columns ) {
    $new = [];
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( $key === 'title' ) {
            $new['fw_city']      = __( 'City', 'faithfulwitness' );
            $new['fw_state_abbr'] = __( 'State', 'faithfulwitness' );
        }
    }
    return $new;
}
add_filter( 'manage_fw_organizing_group_posts_columns', 'fw_og_columns' );

function fw_og_column_content( $column, $post_id ) {
    if ( $column === 'fw_city' )      echo esc_html( get_post_meta( $post_id, 'fw_city', true ) );
    if ( $column === 'fw_state_abbr' ) echo esc_html( get_post_meta( $post_id, 'fw_state_abbr', true ) );
}
add_action( 'manage_fw_organizing_group_posts_custom_column', 'fw_og_column_content', 10, 2 );
