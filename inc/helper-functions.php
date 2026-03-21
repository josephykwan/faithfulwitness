<?php
/**
 * Helper functions for Faithful Witness theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Output breadcrumbs for the current page.
 */
function fw_breadcrumbs() {
    $sep  = '<span class="sep" aria-hidden="true">›</span>';
    $home = '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'faithfulwitness' ) . '</a>';
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'faithfulwitness' ) . '">';
    echo $home; // phpcs:ignore
    if ( is_singular( 'fw_initiative' ) ) {
        echo $sep; // phpcs:ignore
        echo '<a href="' . esc_url( get_post_type_archive_link( 'fw_initiative' ) ) . '">' . esc_html__( 'Initiatives', 'faithfulwitness' ) . '</a>';
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    } elseif ( is_singular( 'fw_resource' ) ) {
        echo $sep; // phpcs:ignore
        echo '<a href="' . esc_url( get_post_type_archive_link( 'fw_resource' ) ) . '">' . esc_html__( 'Resources', 'faithfulwitness' ) . '</a>';
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    } elseif ( is_singular( 'fw_event' ) ) {
        echo $sep; // phpcs:ignore
        echo '<a href="' . esc_url( get_post_type_archive_link( 'fw_event' ) ) . '">' . esc_html__( 'Events', 'faithfulwitness' ) . '</a>';
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    } elseif ( is_singular( 'fw_organizing_group' ) ) {
        echo $sep; // phpcs:ignore
        echo '<a href="' . esc_url( get_post_type_archive_link( 'fw_organizing_group' ) ) . '">' . esc_html__( 'Organizing Groups', 'faithfulwitness' ) . '</a>';
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    } elseif ( is_singular( 'post' ) ) {
        echo $sep; // phpcs:ignore
        echo '<a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'Blog', 'faithfulwitness' ) . '</a>';
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    } elseif ( is_page() ) {
        if ( $parent = wp_get_post_parent_id( get_the_ID() ) ) {
            echo $sep; // phpcs:ignore
            echo '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( get_the_title( $parent ) ) . '</a>';
        }
        echo $sep . '<span class="current">' . esc_html( get_the_title() ) . '</span>'; // phpcs:ignore
    }
    echo '</nav>';
}

/**
 * Get resource type tag HTML.
 *
 * @param int $post_id
 * @return string
 */
function fw_get_resource_type_tag( $post_id ) {
    $types = wp_get_post_terms( $post_id, 'fw_resource_type' );
    if ( empty( $types ) || is_wp_error( $types ) ) return '';
    $type  = $types[0];
    $slug  = $type->slug;
    $class_map = [
        'article'      => 'tag--article',
        'daily-guide'  => 'tag--guide',
        'graphic'      => 'tag--graphic',
        'social-media' => 'tag--social',
    ];
    $class = $class_map[ $slug ] ?? '';
    return '<span class="tag ' . esc_attr( $class ) . '">' . esc_html( $type->name ) . '</span>';
}

/**
 * Get initiative category tag HTML for a post.
 *
 * @param int    $post_id
 * @param string $prefix  Optional wrapper class
 * @return string
 */
function fw_get_initiative_tags( $post_id, $prefix = '' ) {
    $terms = wp_get_post_terms( $post_id, 'fw_initiative_category' );
    if ( empty( $terms ) || is_wp_error( $terms ) ) return '';
    $out = '';
    foreach ( $terms as $term ) {
        $out .= '<a href="' . esc_url( get_term_link( $term ) ) . '" class="tag tag--primary ' . esc_attr( $prefix ) . '">' . esc_html( $term->name ) . '</a>';
    }
    return $out;
}

/**
 * Get formatted event date range string.
 *
 * @param int $post_id
 * @return string
 */
function fw_get_event_date( $post_id ) {
    $start = get_post_meta( $post_id, 'fw_event_date', true );
    $end   = get_post_meta( $post_id, 'fw_event_end_date', true );
    if ( ! $start ) return '';
    $start_fmt = date_i18n( get_option( 'date_format' ), strtotime( $start ) );
    if ( $end && $end !== $start ) {
        $end_fmt = date_i18n( get_option( 'date_format' ), strtotime( $end ) );
        return $start_fmt . ' – ' . $end_fmt;
    }
    return $start_fmt;
}

/**
 * Get formatted event time string.
 *
 * @param int $post_id
 * @return string
 */
function fw_get_event_time( $post_id ) {
    $time     = get_post_meta( $post_id, 'fw_event_time', true );
    $end_time = get_post_meta( $post_id, 'fw_event_end_time', true );
    if ( ! $time ) return '';
    $time_fmt = date_i18n( get_option( 'time_format' ), strtotime( $time ) );
    if ( $end_time ) {
        $end_fmt = date_i18n( get_option( 'time_format' ), strtotime( $end_time ) );
        return $time_fmt . ' – ' . $end_fmt;
    }
    return $time_fmt;
}

/**
 * Get event location string.
 *
 * @param int $post_id
 * @return string
 */
function fw_get_event_location( $post_id ) {
    $virtual = get_post_meta( $post_id, 'fw_event_virtual', true );
    if ( $virtual === '1' ) {
        return __( 'Online / Virtual', 'faithfulwitness' );
    }
    $parts = array_filter( [
        get_post_meta( $post_id, 'fw_event_location_name', true ),
        get_post_meta( $post_id, 'fw_event_city', true ),
        get_post_meta( $post_id, 'fw_event_state', true ),
    ] );
    return implode( ', ', $parts );
}

/**
 * Check if an event is upcoming (today or future).
 *
 * @param int $post_id
 * @return bool
 */
function fw_is_upcoming_event( $post_id ) {
    $date = get_post_meta( $post_id, 'fw_event_date', true );
    if ( ! $date ) return true; // no date set, show it
    return strtotime( $date ) >= strtotime( 'today' );
}

/**
 * Get resources related to an initiative.
 *
 * @param int $initiative_id
 * @param int $limit
 * @return WP_Post[]
 */
function fw_get_initiative_resources( $initiative_id, $limit = -1 ) {
    return get_posts( [
        'post_type'      => 'fw_resource',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'meta_key'       => 'fw_related_initiative_id',
        'meta_value'     => $initiative_id,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

/**
 * Get blog posts related to an initiative (by shared taxonomy term).
 *
 * @param int $initiative_id
 * @param int $limit
 * @return WP_Post[]
 */
function fw_get_initiative_posts( $initiative_id, $limit = 3 ) {
    $terms = wp_get_post_terms( $initiative_id, 'fw_initiative_category', [ 'fields' => 'ids' ] );
    if ( empty( $terms ) || is_wp_error( $terms ) ) return [];
    return get_posts( [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => $limit,
        'tax_query'      => [
            [
                'taxonomy' => 'fw_initiative_category',
                'field'    => 'term_id',
                'terms'    => $terms,
            ],
        ],
    ] );
}

/**
 * Render a resource card (used by multiple templates).
 *
 * @param WP_Post $post
 */
function fw_render_resource_card( $post ) {
    $type_tag     = fw_get_resource_type_tag( $post->ID );
    $init_tags    = fw_get_initiative_tags( $post->ID );
    $resource_url = get_post_meta( $post->ID, 'fw_resource_url', true );
    $file_id      = get_post_meta( $post->ID, 'fw_resource_file_id', true );
    $file_url     = $file_id ? wp_get_attachment_url( (int) $file_id ) : '';
    $permalink    = $resource_url ?: $file_url ?: get_permalink( $post );
    $external     = ( $resource_url && $resource_url !== get_permalink( $post ) );

    $resource_types = wp_get_post_terms( $post->ID, 'fw_resource_type', [ 'fields' => 'slugs' ] );
    $type_slug      = ! empty( $resource_types ) ? $resource_types[0] : '';

    $data_attrs = 'data-resource-id="' . esc_attr( $post->ID ) . '"';
    $data_attrs .= ' data-type="' . esc_attr( $type_slug ) . '"';
    $init_ids = wp_get_post_terms( $post->ID, 'fw_initiative_category', [ 'fields' => 'ids' ] );
    $data_attrs .= ' data-initiative="' . esc_attr( implode( ',', $init_ids ) ) . '"';

    $issue_slugs = wp_get_post_terms( $post->ID, 'fw_resource_issue_area', [ 'fields' => 'slugs' ] );
    $data_attrs .= ' data-issue-area="' . esc_attr( implode( ',', is_array( $issue_slugs ) ? $issue_slugs : [] ) ) . '"';

    $audience_slugs = wp_get_post_terms( $post->ID, 'fw_resource_audience', [ 'fields' => 'slugs' ] );
    $data_attrs .= ' data-audience="' . esc_attr( implode( ',', is_array( $audience_slugs ) ? $audience_slugs : [] ) ) . '"';
    ?>
    <article class="resource-card card" <?php echo $data_attrs; // phpcs:ignore ?>>
        <?php if ( has_post_thumbnail( $post ) ) : ?>
        <div class="card__image">
            <a href="<?php echo esc_url( $permalink ); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                <?php echo get_the_post_thumbnail( $post, 'medium', [ 'loading' => 'lazy' ] ); ?>
            </a>
        </div>
        <?php else : ?>
        <div class="resource-card__icon-wrap">
            <?php echo fw_get_resource_type_icon( $type_slug ); // phpcs:ignore ?>
        </div>
        <?php endif; ?>
        <div class="card__body">
            <div class="card__meta">
                <?php echo $type_tag; // phpcs:ignore ?>
                <?php echo $init_tags; // phpcs:ignore ?>
            </div>
            <h3 class="card__title">
                <a href="<?php echo esc_url( $permalink ); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html( get_the_title( $post ) ); ?>
                </a>
            </h3>
            <p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $post ), 20, '…' ) ); ?></p>
            <div class="card__footer">
                <a class="btn btn--sm btn--outline" href="<?php echo esc_url( $permalink ); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <?php echo $file_url ? esc_html__( 'Download', 'faithfulwitness' ) : ( $external ? esc_html__( 'Read Article', 'faithfulwitness' ) : esc_html__( 'View', 'faithfulwitness' ) ); ?>
                    <?php if ( $external ) : ?><span aria-hidden="true">↗</span><?php endif; ?>
                </a>
            </div>
        </div>
    </article>
    <?php
}

/**
 * Get an SVG icon for a resource type.
 *
 * @param string $type_slug
 * @return string HTML
 */
function fw_get_resource_type_icon( $type_slug ) {
    $icons = [
        'article'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>',
        'daily-guide'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        'graphic'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        'social-media' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
    ];
    $icon  = $icons[ $type_slug ] ?? $icons['article'];
    $color_map = [
        'article'      => '#1E8449',
        'daily-guide'  => '#6C3483',
        'graphic'      => '#C0392B',
        'social-media' => '#1A5276',
    ];
    $color = $color_map[ $type_slug ] ?? '#1B4F72';
    return '<div class="resource-card__icon" style="color:' . esc_attr( $color ) . '">' . $icon . '</div>';
}
