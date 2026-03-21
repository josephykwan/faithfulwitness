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

    fw_seed_placeholder_resources();
    fw_seed_placeholder_partners();
    fw_seed_placeholder_story();
    fw_seed_placeholder_media_hits();
    fw_seed_placeholder_events();
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
