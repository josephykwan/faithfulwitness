<?php
/**
 * Page Setup — Faithful Witness
 *
 * Auto-creates the required WordPress pages on theme activation
 * (or first admin load after theme switch) if they don't already exist.
 * Runs on after_switch_theme and also on admin_init as a fallback.
 *
 * Only creates pages that are missing — never overwrites existing content.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * List of pages to create.
 * Each entry: slug → [ title, template, content ]
 */
function fw_required_pages() {
    return [
        'take-action' => [
            'title'    => __( 'Take Action', 'faithfulwitness' ),
            'template' => 'page-templates/template-take-action.php',
            'content'  => '',
        ],
        'resources' => [
            'title'    => __( 'Resource Library', 'faithfulwitness' ),
            'template' => 'page-templates/template-resources.php',
            'content'  => '',
        ],
        'know-your-rights' => [
            'title'    => __( 'Know Your Rights', 'faithfulwitness' ),
            'template' => 'page-templates/template-know-your-rights.php',
            'content'  => '',
        ],
        'network' => [
            'title'    => __( 'Find Your Network', 'faithfulwitness' ),
            'template' => 'page-templates/template-network.php',
            'content'  => '',
        ],
        'stories' => [
            'title'    => __( 'Stories of Faithful Witness', 'faithfulwitness' ),
            'template' => 'page-templates/template-stories.php',
            'content'  => '',
        ],
        'spiritual-formation' => [
            'title'    => __( 'Spiritual Formation', 'faithfulwitness' ),
            'template' => 'page-templates/template-spiritual-formation.php',
            'content'  => '',
        ],
    ];
}

/**
 * Create missing pages.
 * Safe to run multiple times — checks for existence first.
 */
function fw_create_missing_pages() {
    // Only run in admin context and only for users who can publish pages.
    if ( ! is_admin() || ! current_user_can( 'publish_pages' ) ) return;

    // Guard: only run once per 24h to avoid slowing admin.
    $last_run = get_option( 'fw_page_setup_last_run', 0 );
    if ( time() - $last_run < DAY_IN_SECONDS ) return;
    update_option( 'fw_page_setup_last_run', time() );

    foreach ( fw_required_pages() as $slug => $data ) {
        // Skip if a page with this slug already exists.
        $existing = get_page_by_path( $slug, OBJECT, 'page' );
        if ( $existing ) continue;

        // Create the page.
        $page_id = wp_insert_post( [
            'post_title'   => $data['title'],
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => $data['content'],
            'post_author'  => get_current_user_id(),
        ], true );

        if ( is_wp_error( $page_id ) ) continue;

        // Assign the page template.
        update_post_meta( $page_id, '_wp_page_template', $data['template'] );
    }

    fw_seed_placeholder_resources();
    fw_seed_placeholder_partners();
    fw_seed_placeholder_story();
}
add_action( 'admin_init', 'fw_create_missing_pages' );

// Also run on theme switch (covers the very first activation).
add_action( 'after_switch_theme', function () {
    delete_option( 'fw_page_setup_last_run' ); // Force re-run on switch
    fw_create_missing_pages();
} );

// ============================================================
// SEED PLACEHOLDER RESOURCES
// ============================================================
function fw_seed_placeholder_resources() {
    // Skip if any resources already exist.
    $count = wp_count_posts( 'fw_resource' );
    if ( isset( $count->publish ) && (int) $count->publish > 0 ) return;

    $resources = [
        [
            'title'      => __( 'Know Your Rights: A Guide for Congregations', 'faithfulwitness' ),
            'excerpt'    => __( 'A practical guide for faith communities on immigrant rights, Know Your Rights trainings, and how to support congregation members navigating the immigration system.', 'faithfulwitness' ),
            'type'       => 'pdf-guide',
            'issue_area' => 'know-your-rights',
            'audience'   => 'congregations',
        ],
        [
            'title'      => __( 'Court Accompaniment Basics', 'faithfulwitness' ),
            'excerpt'    => __( 'Step-by-step guidance for faith community members on how to accompany immigrants to immigration court hearings safely and effectively.', 'faithfulwitness' ),
            'type'       => 'toolkit',
            'issue_area' => 'immigration-due-process',
            'audience'   => 'church-leaders',
        ],
        [
            'title'      => __( 'Prayer for Uncertain Times', 'faithfulwitness' ),
            'excerpt'    => __( 'A collection of prayers, laments, and liturgical resources for congregations walking through fear and uncertainty alongside immigrant neighbors.', 'faithfulwitness' ),
            'type'       => 'prayer-guide',
            'issue_area' => 'spiritual-formation',
            'audience'   => 'individual-christians',
        ],
        [
            'title'      => __( 'Understanding Due Process: What Every Christian Should Know', 'faithfulwitness' ),
            'excerpt'    => __( 'A plain-language explainer on due process protections, why they matter for everyone, and how Christians can advocate for fair treatment in the immigration system.', 'faithfulwitness' ),
            'type'       => 'article',
            'issue_area' => 'policy-advocacy',
            'audience'   => 'congregations',
        ],
        [
            'title'      => __( 'Pastoral Care Under Pressure', 'faithfulwitness' ),
            'excerpt'    => __( 'Practical tools and frameworks for pastors and church leaders providing pastoral care to immigrant families navigating enforcement, detention, and deportation fear.', 'faithfulwitness' ),
            'type'       => 'toolkit',
            'issue_area' => 'spiritual-formation',
            'audience'   => 'church-leaders',
        ],
    ];

    // Ensure taxonomy terms exist before assigning.
    $type_terms = [
        'pdf-guide'    => __( 'PDF Guide', 'faithfulwitness' ),
        'article'      => __( 'Article', 'faithfulwitness' ),
        'toolkit'      => __( 'Toolkit', 'faithfulwitness' ),
        'prayer-guide' => __( 'Prayer Guide', 'faithfulwitness' ),
    ];
    foreach ( $type_terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'fw_resource_type' ) ) {
            wp_insert_term( $name, 'fw_resource_type', [ 'slug' => $slug ] );
        }
    }

    $issue_terms = [
        'immigration-due-process'  => __( 'Immigration & Due Process', 'faithfulwitness' ),
        'sanctuary-sacred-spaces'  => __( 'Sanctuary & Sacred Spaces', 'faithfulwitness' ),
        'know-your-rights'         => __( 'Know Your Rights', 'faithfulwitness' ),
        'spiritual-formation'      => __( 'Spiritual Formation', 'faithfulwitness' ),
        'policy-advocacy'          => __( 'Policy Advocacy', 'faithfulwitness' ),
    ];
    foreach ( $issue_terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'fw_resource_issue_area' ) ) {
            wp_insert_term( $name, 'fw_resource_issue_area', [ 'slug' => $slug ] );
        }
    }

    $audience_terms = [
        'congregations'            => __( 'Congregations', 'faithfulwitness' ),
        'individual-christians'    => __( 'Individual Christians', 'faithfulwitness' ),
        'church-leaders'           => __( 'Church Leaders', 'faithfulwitness' ),
        'directly-affected'        => __( 'Directly Affected Individuals', 'faithfulwitness' ),
    ];
    foreach ( $audience_terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'fw_resource_audience' ) ) {
            wp_insert_term( $name, 'fw_resource_audience', [ 'slug' => $slug ] );
        }
    }

    foreach ( $resources as $r ) {
        $post_id = wp_insert_post( [
            'post_title'   => $r['title'],
            'post_excerpt' => $r['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'fw_resource',
            'post_author'  => 1,
        ] );
        if ( is_wp_error( $post_id ) ) continue;
        wp_set_post_terms( $post_id, [ $r['type'] ],       'fw_resource_type' );
        wp_set_post_terms( $post_id, [ $r['issue_area'] ], 'fw_resource_issue_area' );
        wp_set_post_terms( $post_id, [ $r['audience'] ],   'fw_resource_audience' );
    }
}

// ============================================================
// SEED PLACEHOLDER PARTNER ORGANIZATIONS
// ============================================================
function fw_seed_placeholder_partners() {
    $count = wp_count_posts( 'fw_organizing_group' );
    if ( isset( $count->publish ) && (int) $count->publish > 0 ) return;

    $partners = [
        [
            'title'   => 'NaLEC',
            'excerpt' => __( 'The National Latino Evangelical Coalition — a national network of Latino evangelical leaders advancing immigration justice and church formation.', 'faithfulwitness' ),
            'website' => 'https://nalec.org',
            'email'   => '',
            'city'    => 'National',
            'state'   => '',
        ],
        [
            'title'   => 'CCDA',
            'excerpt' => __( 'The Christian Community Development Association — a national network supporting community development, immigrant support, and church-based organizing.', 'faithfulwitness' ),
            'website' => 'https://ccda.org',
            'email'   => '',
            'city'    => 'National',
            'state'   => '',
        ],
        [
            'title'   => 'World Relief',
            'excerpt' => __( 'World Relief works with local churches for refugee resettlement, immigrant integration, and church mobilization around immigration justice.', 'faithfulwitness' ),
            'website' => 'https://worldrelief.org',
            'email'   => '',
            'city'    => 'National',
            'state'   => '',
        ],
        [
            'title'   => 'Undivided',
            'excerpt' => __( 'Undivided is a curriculum and movement helping churches navigate racial reconciliation and immigration — together, not divided.', 'faithfulwitness' ),
            'website' => 'https://undivided.us',
            'email'   => '',
            'city'    => 'National',
            'state'   => '',
        ],
    ];

    foreach ( $partners as $p ) {
        $post_id = wp_insert_post( [
            'post_title'   => $p['title'],
            'post_excerpt' => $p['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'fw_organizing_group',
            'post_author'  => 1,
        ] );
        if ( is_wp_error( $post_id ) ) continue;
        update_post_meta( $post_id, 'fw_website_url', $p['website'] );
        update_post_meta( $post_id, 'fw_contact_email', $p['email'] );
        update_post_meta( $post_id, 'fw_city', $p['city'] );
        update_post_meta( $post_id, 'fw_state_abbr', $p['state'] );
    }
}

// ============================================================
// SEED PLACEHOLDER STORY POST
// ============================================================
function fw_seed_placeholder_story() {
    // Check if the placeholder story already exists.
    $existing = get_page_by_path( 'what-we-saw-at-the-courthouse', OBJECT, 'post' );
    if ( $existing ) return;

    wp_insert_post( [
        'post_title'   => __( 'What we saw at the courthouse', 'faithfulwitness' ),
        'post_name'    => 'what-we-saw-at-the-courthouse',
        'post_excerpt' => __( 'Three volunteers from our congregation attended immigration court on a Tuesday morning. What we witnessed changed how we understand the words "faithful witness."', 'faithfulwitness' ),
        'post_content' => '<p>' . __( 'This is a placeholder story. Replace this content with a real story from the field. Stories of faithful witness — from churches, families, and leaders navigating this moment together — are at the heart of this campaign.', 'faithfulwitness' ) . '</p>',
        'post_status'  => 'publish',
        'post_type'    => 'post',
        'post_author'  => 1,
    ] );
}
