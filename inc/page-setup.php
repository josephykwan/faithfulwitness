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
        'news' => [
            'title'    => __( 'News & Media', 'faithfulwitness' ),
            'template' => 'page-templates/template-news.php',
            'content'  => '',
        ],
        'events' => [
            'title'    => __( 'Events & Gatherings', 'faithfulwitness' ),
            'template' => 'page-templates/template-events.php',
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

    fw_seed_story_categories();
    fw_seed_placeholder_resources();
    fw_seed_placeholder_partners();
    fw_seed_placeholder_story();
    fw_seed_placeholder_media_hits();
    fw_seed_placeholder_events();
    fw_seed_kyr_initiative();
    fw_seed_example_resource();
    fw_seed_admin_guide_pages();
    fw_seed_primary_nav_menu();
    fw_seed_team_user();
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

    $featured_count = 0;
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
        // Mark first 3 resources as featured
        if ( $featured_count < 3 ) {
            update_post_meta( $post_id, 'fw_resource_featured', '1' );
            $featured_count++;
        }
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
// SEED PLACEHOLDER MEDIA HITS
// ============================================================
function fw_seed_placeholder_media_hits() {
    $count = wp_count_posts( 'fw_media_hit' );
    if ( isset( $count->publish ) && (int) $count->publish > 0 ) return;

    $hits = [
        [
            'title'      => __( 'Faith groups mobilize around immigration enforcement', 'faithfulwitness' ),
            'outlet'     => 'Christianity Today',
            'url'        => '',
            'pub_date'   => gmdate( 'Y-m-d', strtotime( '-30 days' ) ),
            'pull_quote' => __( '"Churches across the country are asking what it means to be faithful witnesses in this moment — and more are answering the call."', 'faithfulwitness' ),
            'logo_url'   => '',
        ],
        [
            'title'      => __( 'Evangelical coalition calls for dignity in immigration debate', 'faithfulwitness' ),
            'outlet'     => 'Religion News Service',
            'url'        => '',
            'pub_date'   => gmdate( 'Y-m-d', strtotime( '-14 days' ) ),
            'pull_quote' => __( '"The Faithful Witness campaign is training churches to respond with Gospel values rather than partisan talking points."', 'faithfulwitness' ),
            'logo_url'   => '',
        ],
    ];

    foreach ( $hits as $h ) {
        $post_id = wp_insert_post( [
            'post_title'  => $h['title'],
            'post_status' => 'publish',
            'post_type'   => 'fw_media_hit',
            'post_author' => 1,
        ] );
        if ( is_wp_error( $post_id ) ) continue;
        update_post_meta( $post_id, 'fw_media_outlet',    $h['outlet'] );
        update_post_meta( $post_id, 'fw_media_url',       $h['url'] );
        update_post_meta( $post_id, 'fw_media_pub_date',  $h['pub_date'] );
        update_post_meta( $post_id, 'fw_media_pull_quote', $h['pull_quote'] );
        update_post_meta( $post_id, 'fw_media_outlet_logo_url', $h['logo_url'] );
    }

    // Also create a "Press Release" category and one sample press release
    $cat_id = term_exists( 'press-release', 'category' );
    if ( ! $cat_id ) {
        $cat_id = wp_insert_term( __( 'Press Release', 'faithfulwitness' ), 'category', [ 'slug' => 'press-release' ] );
        $cat_id = is_array( $cat_id ) ? $cat_id['term_id'] : 0;
    } else {
        $cat_id = is_array( $cat_id ) ? $cat_id['term_id'] : $cat_id;
    }

    $existing_pr = get_page_by_path( 'faithful-witness-statement-on-due-process', OBJECT, 'post' );
    if ( ! $existing_pr && $cat_id ) {
        $pr_id = wp_insert_post( [
            'post_title'   => __( 'Faithful Witness Statement on Due Process and Human Dignity', 'faithfulwitness' ),
            'post_name'    => 'faithful-witness-statement-on-due-process',
            'post_excerpt' => __( 'A statement from the Faithful Witness campaign affirming our commitment to due process, human dignity, and Gospel-centered engagement on immigration.', 'faithfulwitness' ),
            'post_content' => '<p>' . __( 'This is a placeholder press release. Replace with the actual statement text.', 'faithfulwitness' ) . '</p>',
            'post_status'  => 'publish',
            'post_type'    => 'post',
            'post_author'  => 1,
        ] );
        if ( $pr_id && ! is_wp_error( $pr_id ) ) {
            wp_set_post_categories( $pr_id, [ (int) $cat_id ] );
        }
    }
}

// ============================================================
// SEED PLACEHOLDER EVENTS
// ============================================================
function fw_seed_placeholder_events() {
    $count = wp_count_posts( 'fw_event' );
    if ( isset( $count->publish ) && (int) $count->publish > 0 ) return;

    // Ensure event category terms exist
    $event_cat_terms = [
        'kyr-training'         => __( 'Know Your Rights Training', 'faithfulwitness' ),
        'prayer-gathering'     => __( 'Prayer Gathering', 'faithfulwitness' ),
        'court-accompaniment'  => __( 'Court Accompaniment', 'faithfulwitness' ),
        'community-formation'  => __( 'Community Formation', 'faithfulwitness' ),
        'public-witness'       => __( 'Public Witness', 'faithfulwitness' ),
        'webinar'              => __( 'Webinar', 'faithfulwitness' ),
    ];
    foreach ( $event_cat_terms as $slug => $name ) {
        if ( ! term_exists( $slug, 'fw_event_category' ) ) {
            wp_insert_term( $name, 'fw_event_category', [ 'slug' => $slug ] );
        }
    }

    $today = gmdate( 'Y-m-d' );

    $events = [
        [
            'title'    => __( 'Know Your Rights Training — Chicago', 'faithfulwitness' ),
            'excerpt'  => __( 'An in-person Know Your Rights training for immigrants and their families, hosted by local faith communities. Learn your legal rights and how your church can support you.', 'faithfulwitness' ),
            'date'     => gmdate( 'Y-m-d', strtotime( '+7 days' ) ),
            'time'     => '10:00',
            'end_time' => '12:00',
            'virtual'  => '0',
            'location' => 'Lakeview Community Church',
            'city'     => 'Chicago',
            'state'    => 'IL',
            'reg_link' => '',
            'category' => 'kyr-training',
        ],
        [
            'title'    => __( 'Prayer Gathering for Immigrant Families', 'faithfulwitness' ),
            'excerpt'  => __( 'A virtual prayer gathering for churches and individuals to pray together for immigrant families, detained individuals, and those walking through fear.', 'faithfulwitness' ),
            'date'     => gmdate( 'Y-m-d', strtotime( '+14 days' ) ),
            'time'     => '19:00',
            'end_time' => '20:00',
            'virtual'  => '1',
            'location' => '',
            'city'     => '',
            'state'    => '',
            'reg_link' => '',
            'category' => 'prayer-gathering',
        ],
        [
            'title'    => __( 'Faithful Witness Orientation Webinar', 'faithfulwitness' ),
            'excerpt'  => __( 'New to the campaign? Join this orientation webinar to learn what Faithful Witness is, how it works, and how your congregation can get involved.', 'faithfulwitness' ),
            'date'     => gmdate( 'Y-m-d', strtotime( '+21 days' ) ),
            'time'     => '19:30',
            'end_time' => '20:30',
            'virtual'  => '1',
            'location' => '',
            'city'     => '',
            'state'    => '',
            'reg_link' => 'https://mailchi.mp/ccda/join-the-faithful-witness-campaign',
            'category' => 'webinar',
        ],
    ];

    foreach ( $events as $e ) {
        $post_id = wp_insert_post( [
            'post_title'   => $e['title'],
            'post_excerpt' => $e['excerpt'],
            'post_status'  => 'publish',
            'post_type'    => 'fw_event',
            'post_author'  => 1,
        ] );
        if ( is_wp_error( $post_id ) ) continue;
        update_post_meta( $post_id, 'fw_event_date',              $e['date'] );
        update_post_meta( $post_id, 'fw_event_time',              $e['time'] );
        update_post_meta( $post_id, 'fw_event_end_time',          $e['end_time'] );
        update_post_meta( $post_id, 'fw_event_virtual',           $e['virtual'] );
        update_post_meta( $post_id, 'fw_event_location_name',     $e['location'] );
        update_post_meta( $post_id, 'fw_event_city',              $e['city'] );
        update_post_meta( $post_id, 'fw_event_state',             $e['state'] );
        update_post_meta( $post_id, 'fw_event_registration_link', $e['reg_link'] );
        update_post_meta( $post_id, 'fw_event_scope',             'national' );
        wp_set_post_terms( $post_id, [ $e['category'] ], 'fw_event_category' );
    }
}

// ============================================================
// SEED STORY TYPE CATEGORIES
// ============================================================
function fw_seed_story_categories() {
    $cats = [
        'church-story'       => __( 'Church Story', 'faithfulwitness' ),
        'immigrant-voice'    => __( 'Immigrant Voice', 'faithfulwitness' ),
        'pastor-reflection'  => __( 'Pastor Reflection', 'faithfulwitness' ),
        'policy-advocacy'    => __( 'Policy & Advocacy', 'faithfulwitness' ),
    ];
    foreach ( $cats as $slug => $name ) {
        if ( ! term_exists( $slug, 'category' ) ) {
            wp_insert_term( $name, 'category', [ 'slug' => $slug ] );
        }
    }
}

// ============================================================
// SEED KNOW YOUR RIGHTS INITIATIVE
// ============================================================
function fw_seed_kyr_initiative() {
    $existing = get_page_by_path( 'know-your-rights-initiative', OBJECT, 'fw_initiative' );
    if ( $existing ) return;

    $post_id = wp_insert_post( [
        'post_title'   => __( 'Know Your Rights', 'faithfulwitness' ),
        'post_name'    => 'know-your-rights-initiative',
        'post_excerpt' => __( 'Equipping faith communities to understand and share legal rights with immigrant neighbors through Know Your Rights trainings, court accompaniment, and direct legal education.', 'faithfulwitness' ),
        'post_content' => '<p>' . __( 'This initiative trains churches and congregations to host Know Your Rights workshops, provide court accompaniment, and serve as informed community advocates for immigrant families navigating the legal system.', 'faithfulwitness' ) . '</p>',
        'post_status'  => 'publish',
        'post_type'    => 'fw_initiative',
        'post_author'  => 1,
    ] );

    if ( ! is_wp_error( $post_id ) ) {
        update_post_meta( $post_id, 'fw_initiative_status',    'active' );
        update_post_meta( $post_id, 'fw_initiative_cta_label', __( 'Get Trained', 'faithfulwitness' ) );
        update_post_meta( $post_id, 'fw_initiative_cta_url',   home_url( '/events' ) );
    }
}

// ============================================================
// SEED EXAMPLE FULLY-BUILT RESOURCE
// ============================================================
function fw_seed_example_resource() {
    $existing = get_page_by_path( 'know-your-rights-congregation-guide', OBJECT, 'fw_resource' );
    if ( $existing ) return;

    // Find the KYR initiative ID if it exists.
    $kyr = get_page_by_path( 'know-your-rights-initiative', OBJECT, 'fw_initiative' );
    $kyr_id = $kyr ? $kyr->ID : 0;

    $post_id = wp_insert_post( [
        'post_title'   => __( 'Know Your Rights: Congregation Training Guide', 'faithfulwitness' ),
        'post_name'    => 'know-your-rights-congregation-guide',
        'post_excerpt' => __( 'A complete facilitator guide for hosting a Know Your Rights workshop in your congregation. Includes agenda, talking points, handouts, and follow-up resources.', 'faithfulwitness' ),
        'post_content' => '<p>' . __( 'This guide walks church leaders through hosting a two-hour Know Your Rights training for congregation members and their immigrant neighbors. Includes a step-by-step facilitation guide, printable handouts, and local resource referral template.', 'faithfulwitness' ) . '</p><p>' . __( '<strong>What you\'ll find inside:</strong></p><ul><li>Opening prayer and framing (15 min)</li><li>What are your legal rights? (30 min)</li><li>ICE encounters: what to do (20 min)</li><li>Court accompaniment overview (15 min)</li><li>Community Q&A (30 min)</li><li>Closing and next steps (10 min)</li></ul>', 'faithfulwitness' ) . '</p>',
        'post_status'  => 'publish',
        'post_type'    => 'fw_resource',
        'post_author'  => 1,
    ] );

    if ( is_wp_error( $post_id ) ) return;

    // Assign taxonomies — ensure terms exist first.
    if ( ! term_exists( 'pdf-guide', 'fw_resource_type' ) ) {
        wp_insert_term( __( 'PDF Guide', 'faithfulwitness' ), 'fw_resource_type', [ 'slug' => 'pdf-guide' ] );
    }
    if ( ! term_exists( 'know-your-rights', 'fw_resource_issue_area' ) ) {
        wp_insert_term( __( 'Know Your Rights', 'faithfulwitness' ), 'fw_resource_issue_area', [ 'slug' => 'know-your-rights' ] );
    }
    if ( ! term_exists( 'congregations', 'fw_resource_audience' ) ) {
        wp_insert_term( __( 'Congregations', 'faithfulwitness' ), 'fw_resource_audience', [ 'slug' => 'congregations' ] );
    }

    wp_set_post_terms( $post_id, [ 'pdf-guide' ],       'fw_resource_type' );
    wp_set_post_terms( $post_id, [ 'know-your-rights' ], 'fw_resource_issue_area' );
    wp_set_post_terms( $post_id, [ 'congregations' ],    'fw_resource_audience' );

    update_post_meta( $post_id, 'fw_resource_featured',     '1' );
    update_post_meta( $post_id, 'fw_resource_button_label', __( 'Download Guide', 'faithfulwitness' ) );

    if ( $kyr_id ) {
        update_post_meta( $post_id, 'fw_related_initiative_id', $kyr_id );
    }
}

// ============================================================
// SEED ADMIN GUIDE PAGES (private)
// ============================================================
function fw_seed_admin_guide_pages() {
    // Only create if none exist.
    $existing = get_page_by_path( 'admin-guide-content-management', OBJECT, 'page' );
    if ( $existing ) return;

    $guide_pages = [
        [
            'title'   => __( 'Admin Guide: Content Management', 'faithfulwitness' ),
            'slug'    => 'admin-guide-content-management',
            'content' => fw_admin_guide_index_content(),
        ],
        [
            'title'   => __( 'Admin Guide: Adding Resources', 'faithfulwitness' ),
            'slug'    => 'admin-guide-adding-resources',
            'content' => fw_admin_guide_resources_content(),
        ],
        [
            'title'   => __( 'Admin Guide: Adding Events', 'faithfulwitness' ),
            'slug'    => 'admin-guide-adding-events',
            'content' => fw_admin_guide_events_content(),
        ],
        [
            'title'   => __( 'Admin Guide: Adding Stories', 'faithfulwitness' ),
            'slug'    => 'admin-guide-adding-stories',
            'content' => fw_admin_guide_stories_content(),
        ],
        [
            'title'   => __( 'Admin Guide: Network Partners & Map', 'faithfulwitness' ),
            'slug'    => 'admin-guide-network-partners',
            'content' => fw_admin_guide_partners_content(),
        ],
    ];

    $index_id = 0;
    foreach ( $guide_pages as $i => $guide ) {
        $post_id = wp_insert_post( [
            'post_title'   => $guide['title'],
            'post_name'    => $guide['slug'],
            'post_content' => $guide['content'],
            'post_status'  => 'private',
            'post_type'    => 'page',
            'post_author'  => 1,
        ] );
        if ( ! is_wp_error( $post_id ) && $i === 0 ) {
            $index_id = $post_id;
        }
        // Set child guides under the index page.
        if ( ! is_wp_error( $post_id ) && $i > 0 && $index_id ) {
            wp_update_post( [ 'ID' => $post_id, 'post_parent' => $index_id ] );
        }
    }
}

/** Content for admin guide index page. */
function fw_admin_guide_index_content() {
    return '<h2>Welcome to the Faithful Witness Content Dashboard</h2>
<p>This guide explains how to add and manage all content on the Faithful Witness website. You do <strong>not</strong> need to edit any theme files to manage content — everything is handled through these admin forms.</p>
<h3>Quick Links</h3>
<ul>
<li><a href="' . esc_url( home_url( '/admin-guide-adding-resources' ) ) . '">Adding Resources to the Resource Library</a></li>
<li><a href="' . esc_url( home_url( '/admin-guide-adding-events' ) ) . '">Adding Events to the Events Calendar</a></li>
<li><a href="' . esc_url( home_url( '/admin-guide-adding-stories' ) ) . '">Adding Stories</a></li>
<li><a href="' . esc_url( home_url( '/admin-guide-network-partners' ) ) . '">Managing Network Partners &amp; the Map</a></li>
</ul>
<h3>Content Types at a Glance</h3>
<table>
<thead><tr><th>Content Type</th><th>Where it appears</th><th>How to add</th></tr></thead>
<tbody>
<tr><td><strong>Resources</strong></td><td>Resource Library (/resources)</td><td>Faithful Witness → + Add a Resource</td></tr>
<tr><td><strong>Events</strong></td><td>Events Calendar (/events) + Homepage widget</td><td>Faithful Witness → + Add an Event</td></tr>
<tr><td><strong>Stories</strong></td><td>Stories page (/stories) + Blog</td><td>Faithful Witness → + Add a Story</td></tr>
<tr><td><strong>Media Hits</strong></td><td>News &amp; Media (/news)</td><td>Faithful Witness → + Add a Media Hit</td></tr>
<tr><td><strong>Network Partners</strong></td><td>Find Your Network (/network) + map</td><td>Faithful Witness → Network Partners</td></tr>
</tbody>
</table>';
}

/** Content for resources admin guide. */
function fw_admin_guide_resources_content() {
    return '<h2>How to Add a Resource</h2>
<p>Resources appear on the <strong>Resource Library</strong> page (/resources). They can be PDF downloads, external articles, toolkits, or prayer guides.</p>
<h3>Step-by-Step</h3>
<ol>
<li>Go to <strong>Faithful Witness → + Add a Resource</strong> in the sidebar.</li>
<li>Enter the resource <strong>Title</strong> (e.g. "Know Your Rights: A Guide for Congregations").</li>
<li>Add a short <strong>Description</strong> in the Excerpt box — this appears as the card summary on the library page.</li>
<li>In the <strong>Resource Details</strong> box, fill in:
  <ul>
  <li><strong>External Link</strong> — paste the URL if the resource lives on another site (e.g. a PDF hosted on Google Drive).</li>
  <li><strong>Downloadable File</strong> — if uploading directly, use the Media Library to upload the PDF, copy the attachment ID, and paste it here.</li>
  <li><strong>Button Label</strong> — customize the call-to-action button (e.g. "Download Guide", "Read Article"). Leave blank for the default.</li>
  <li><strong>Feature this resource</strong> — check this to show the resource in the "Start Here" section at the top of the library.</li>
  </ul>
</li>
<li>In the right sidebar, assign:
  <ul>
  <li><strong>Resource Type</strong> — select PDF Guide, Article, Toolkit, or Prayer Guide.</li>
  <li><strong>Issue Area</strong> — the topic (e.g. Know Your Rights, Spiritual Formation).</li>
  <li><strong>Audience</strong> — who the resource is for (Congregations, Church Leaders, etc.).</li>
  </ul>
</li>
<li>Click <strong>Publish</strong>.</li>
</ol>
<p><strong>Tip:</strong> Resources without a file or external link will link to their own post page — make sure the post has content in that case.</p>';
}

/** Content for events admin guide. */
function fw_admin_guide_events_content() {
    return '<h2>How to Add an Event</h2>
<p>Events appear on the <strong>Events Calendar</strong> (/events) and in the homepage upcoming events widget (next 3 events).</p>
<h3>Step-by-Step</h3>
<ol>
<li>Go to <strong>Faithful Witness → + Add an Event</strong>.</li>
<li>Enter the event <strong>Title</strong> (e.g. "Know Your Rights Training — Chicago").</li>
<li>Add a short <strong>Description</strong> in the Excerpt box.</li>
<li>In the <strong>Event Details</strong> box, fill in:
  <ul>
  <li><strong>Start Date</strong> and <strong>End Date</strong> (if multi-day).</li>
  <li><strong>Start Time</strong> and <strong>End Time</strong>.</li>
  <li><strong>Location Name</strong>, <strong>Street Address</strong>, <strong>City</strong>, <strong>State</strong>.</li>
  <li><strong>Registration Link</strong> — paste a Mailchimp, Eventbrite, or Zoom link here.</li>
  <li><strong>Event Scope</strong> — National or Local.</li>
  <li><strong>Virtual</strong> — check this for online events, then paste the virtual event link.</li>
  </ul>
</li>
<li>In the right sidebar, assign an <strong>Event Category</strong> (Prayer Gathering, KYR Training, Webinar, etc.).</li>
<li>Click <strong>Publish</strong>.</li>
</ol>
<p><strong>Tip:</strong> Past events (where the date is before today) are automatically hidden from the homepage widget but remain visible on the Events Calendar page.</p>';
}

/** Content for stories admin guide. */
function fw_admin_guide_stories_content() {
    return '<h2>How to Add a Story</h2>
<p>Stories are regular WordPress blog posts that appear on the <strong>Stories</strong> page (/stories). They are personal narratives from churches, immigrant families, pastors, and advocates.</p>
<h3>Step-by-Step</h3>
<ol>
<li>Go to <strong>Faithful Witness → + Add a Story</strong>.</li>
<li>Enter the story <strong>Title</strong> — use the first-person voice if possible (e.g. "What We Saw at the Courthouse").</li>
<li>Write or paste the full story in the main content editor.</li>
<li>Add a short <strong>Excerpt</strong> — this appears as the preview text on the Stories page.</li>
<li>In the <strong>Story Details</strong> sidebar panel, fill in:
  <ul>
  <li><strong>Author Name</strong> — the person\'s real name or pseudonym.</li>
  <li><strong>Author\'s Church</strong> — congregation name.</li>
  <li><strong>City</strong> — where the story takes place.</li>
  </ul>
</li>
<li>In the right sidebar, assign a <strong>Category</strong>:
  <ul>
  <li>Church Story</li>
  <li>Immigrant Voice</li>
  <li>Pastor Reflection</li>
  <li>Policy &amp; Advocacy</li>
  </ul>
</li>
<li>Add a <strong>Featured Image</strong> if you have one (recommended: horizontal photo, at least 1200px wide).</li>
<li>Click <strong>Publish</strong>.</li>
</ol>';
}

/** Content for network partners admin guide. */
function fw_admin_guide_partners_content() {
    return '<h2>How to Add a Network Partner (and Map Pin)</h2>
<p>Network Partners appear on the <strong>Find Your Network</strong> page (/network) as cards with map pins. Each partner needs location coordinates to appear on the map.</p>
<h3>Step-by-Step</h3>
<ol>
<li>Go to <strong>Faithful Witness → Network Partners</strong>.</li>
<li>Click <strong>Add New Organizing Group</strong>.</li>
<li>Enter the organization <strong>Name</strong> as the post title.</li>
<li>Add a short <strong>Description</strong> in the Excerpt box.</li>
<li>In the <strong>Organizing Group Details</strong> box, fill in:
  <ul>
  <li><strong>City</strong> and <strong>State</strong>.</li>
  <li><strong>Latitude</strong> and <strong>Longitude</strong> — find these by searching the city or address on <a href="https://www.google.com/maps" target="_blank">Google Maps</a>, right-clicking the location, and copying the coordinates.</li>
  <li><strong>Contact Email</strong>, <strong>Phone</strong>, <strong>Website URL</strong>.</li>
  <li><strong>Social media URLs</strong> (Instagram, Facebook, Twitter).</li>
  <li><strong>Organization Type</strong> — select National Partner, Local Church, or Organizing Group. This controls the map pin color.</li>
  </ul>
</li>
<li>Click <strong>Publish</strong>.</li>
</ol>
<h3>Map Pin Colors</h3>
<ul>
<li><strong>Navy</strong> = National Partner</li>
<li><strong>Amber</strong> = Local Church</li>
<li><strong>Teal</strong> = Organizing Group</li>
</ul>
<p><strong>Important:</strong> The map will not show a pin unless both Latitude and Longitude are filled in.</p>';
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

// ============================================================
// SEED PRIMARY NAVIGATION MENU
// ============================================================
function fw_seed_primary_nav_menu() {
    // Only create if a "Primary Navigation" menu doesn't exist yet.
    if ( wp_get_nav_menu_object( 'Primary Navigation' ) ) return;

    $menu_id = wp_create_nav_menu( 'Primary Navigation' );
    if ( is_wp_error( $menu_id ) ) return;

    // Helper closure to add items.
    $add = function( $title, $url, $parent = 0 ) use ( $menu_id ) {
        return wp_update_nav_menu_item( $menu_id, 0, [
            'menu-item-title'   => $title,
            'menu-item-url'     => $url,
            'menu-item-status'  => 'publish',
            'menu-item-type'    => 'custom',
            'menu-item-parent-id' => $parent,
        ] );
    };

    // Top-level items
    $our_work   = $add( __( 'Our Work', 'faithfulwitness' ), '#' );
    $network    = $add( __( 'The Network', 'faithfulwitness' ), '#' );
    $resources  = $add( __( 'Resources', 'faithfulwitness' ), '#' );
    $add( __( 'Stories', 'faithfulwitness' ), home_url( '/stories' ) );
    $add( __( 'News & Media', 'faithfulwitness' ), home_url( '/news' ) );
    $add( __( 'Events', 'faithfulwitness' ), home_url( '/events' ) );
    $add( __( 'Take Action', 'faithfulwitness' ), home_url( '/take-action' ) );

    // Our Work sub-items
    $add( __( 'Three Commitments', 'faithfulwitness' ), home_url( '/#commitments' ), $our_work );
    $add( __( 'Campaigns', 'faithfulwitness' ),          home_url( '/take-action' ),   $our_work );

    // The Network sub-items
    $add( __( 'Find Local Groups', 'faithfulwitness' ),    home_url( '/network' ),  $network );
    $add( __( 'Partner Organizations', 'faithfulwitness' ), home_url( '/network' ), $network );

    // Resources sub-items
    $add( __( 'Resource Library', 'faithfulwitness' ),    home_url( '/resources' ),          $resources );
    $add( __( 'Know Your Rights', 'faithfulwitness' ),    home_url( '/know-your-rights' ),   $resources );
    $add( __( 'Spiritual Formation', 'faithfulwitness' ), home_url( '/spiritual-formation' ), $resources );

    // Assign the menu to the "primary" theme location.
    $locations = get_theme_mod( 'nav_menu_locations', [] );
    $locations['primary'] = $menu_id;
    set_theme_mod( 'nav_menu_locations', $locations );
}

// ============================================================
// SEED PLACEHOLDER TEAM USER (Content Manager)
// ============================================================
function fw_seed_team_user() {
    // Only create if the "team" user doesn't exist.
    if ( username_exists( 'team' ) ) return;

    $user_id = wp_create_user(
        'team',
        wp_generate_password( 16, true, true ), // Random password — admin must reset.
        'team@' . wp_parse_url( home_url(), PHP_URL_HOST )
    );

    if ( is_wp_error( $user_id ) ) return;

    $user = new WP_User( $user_id );
    $user->set_role( 'content_manager' );

    // Add a note in user meta so the welcome email is clear.
    update_user_meta( $user_id, 'fw_placeholder_user', true );
    update_user_meta( $user_id, 'description', __( 'Content Manager placeholder account. Reset the password before handing off to the team.', 'faithfulwitness' ) );
}
