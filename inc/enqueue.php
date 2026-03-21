<?php
/**
 * Enqueue scripts and styles
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function fw_enqueue_assets() {
    $ver = wp_get_theme()->get( 'Version' );

    // Google Fonts — Inter + Merriweather
    wp_enqueue_style( 'fw-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:ital,wght@0,700;0,900;1,700&display=swap',
        [], null );

    // Main stylesheet
    wp_enqueue_style( 'fw-style', get_stylesheet_uri(), [ 'fw-google-fonts' ], $ver );

    // Additional CSS
    wp_enqueue_style( 'fw-main', get_template_directory_uri() . '/assets/css/main.css', [ 'fw-style' ], $ver );

    // Main JS
    wp_enqueue_script( 'fw-main', get_template_directory_uri() . '/assets/js/main.js', [], $ver, true );

    // Page template styles — loaded for all new page templates
    $page_templates = [
        'page-templates/template-take-action.php',
        'page-templates/template-know-your-rights.php',
        'page-templates/template-network.php',
        'page-templates/template-stories.php',
        'page-templates/template-spiritual-formation.php',
    ];
    foreach ( $page_templates as $tpl ) {
        if ( is_page_template( $tpl ) ) {
            wp_enqueue_style( 'fw-pages', get_template_directory_uri() . '/assets/css/pages.css', [ 'fw-style' ], $ver );
            break;
        }
    }

    // Also load pages.css on stories archive (uses story-card styles)
    if ( is_home() || is_category() ) {
        wp_enqueue_style( 'fw-pages', get_template_directory_uri() . '/assets/css/pages.css', [ 'fw-style' ], $ver );
    }

    // Map page
    if ( is_page_template( 'page-templates/template-map.php' ) || is_singular( 'fw_organizing_group' ) || is_post_type_archive( 'fw_organizing_group' ) ) {
        // Leaflet CSS + JS (CDN)
        wp_enqueue_style( 'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
            [], '1.9.4' );
        wp_enqueue_script( 'leaflet',
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            [], '1.9.4', true );

        // Leaflet marker cluster
        wp_enqueue_style( 'leaflet-cluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css',
            [ 'leaflet' ], '1.5.3' );
        wp_enqueue_style( 'leaflet-cluster-default',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css',
            [ 'leaflet-cluster' ], '1.5.3' );
        wp_enqueue_script( 'leaflet-cluster',
            'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js',
            [ 'leaflet' ], '1.5.3', true );

        wp_enqueue_style( 'fw-map', get_template_directory_uri() . '/assets/css/map.css', [ 'leaflet' ], $ver );
        wp_enqueue_script( 'fw-map', get_template_directory_uri() . '/assets/js/map.js', [ 'leaflet-cluster' ], $ver, true );

        // Pass organizing groups data to JS
        wp_localize_script( 'fw-map', 'fwMapData', fw_get_organizing_groups_for_map() );
    }

    // Resource library page
    if ( is_page_template( 'page-templates/template-resources.php' ) || is_post_type_archive( 'fw_resource' ) ) {
        wp_enqueue_style( 'fw-resources', get_template_directory_uri() . '/assets/css/resources.css', [ 'fw-style' ], $ver );
        wp_enqueue_script( 'fw-resources', get_template_directory_uri() . '/assets/js/resources-filter.js', [], $ver, true );
    }
}
add_action( 'wp_enqueue_scripts', 'fw_enqueue_assets' );

/**
 * Build JSON data for the map JS.
 * Returns array with 'groups' key containing all organizing group data.
 */
function fw_get_organizing_groups_for_map() {
    $query = new WP_Query( [
        'post_type'      => 'fw_organizing_group',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ] );

    $groups = [];
    foreach ( $query->posts as $post ) {
        $lat = get_post_meta( $post->ID, 'fw_lat', true );
        $lng = get_post_meta( $post->ID, 'fw_lng', true );
        if ( ! $lat || ! $lng ) continue;

        $groups[] = [
            'id'            => $post->ID,
            'title'         => get_the_title( $post ),
            'url'           => get_permalink( $post ),
            'city'          => get_post_meta( $post->ID, 'fw_city', true ),
            'state'         => get_post_meta( $post->ID, 'fw_state_abbr', true ),
            'lat'           => (float) $lat,
            'lng'           => (float) $lng,
            'email'         => get_post_meta( $post->ID, 'fw_contact_email', true ),
            'phone'         => get_post_meta( $post->ID, 'fw_contact_phone', true ),
            'website'       => get_post_meta( $post->ID, 'fw_website_url', true ),
            'instagram'     => get_post_meta( $post->ID, 'fw_instagram_url', true ),
            'facebook'      => get_post_meta( $post->ID, 'fw_facebook_url', true ),
            'twitter'       => get_post_meta( $post->ID, 'fw_twitter_url', true ),
            'excerpt'       => wp_trim_words( get_the_excerpt( $post ), 20, '…' ),
            'thumbnail'     => get_the_post_thumbnail_url( $post, 'thumbnail' ) ?: '',
        ];
    }
    wp_reset_postdata();

    return [ 'groups' => $groups, 'ajaxUrl' => admin_url( 'admin-ajax.php' ) ];
}
