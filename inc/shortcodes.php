<?php
/**
 * Shortcodes for Faithful Witness
 *
 * [fw_resources]                         — All resources (full library)
 * [fw_resources type="graphic"]          — Filtered by resource type slug
 * [fw_resources initiative="123"]        — Filtered by initiative ID
 * [fw_resources type="guide" limit="6"]  — Combined with a limit
 * [fw_resources initiative="123" show_filters="false"] — No filter UI
 *
 * Usage: Embed on any page/post to pull a specific subset of resources
 * with the Atlassian-playbook card layout.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function fw_shortcode_resources( $atts ) {
    $atts = shortcode_atts( [
        'type'         => '',     // resource type slug
        'initiative'   => '',     // initiative post ID or term ID
        'limit'        => -1,
        'columns'      => 3,      // 2, 3, or 4
        'show_filters' => 'false', // 'true' or 'false'
        'show_search'  => 'false',
        'title'        => '',
    ], $atts, 'fw_resources' );

    $query_args = [
        'post_type'      => 'fw_resource',
        'post_status'    => 'publish',
        'posts_per_page' => (int) $atts['limit'],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    // Filter by resource type
    if ( $atts['type'] ) {
        $query_args['tax_query'][] = [
            'taxonomy' => 'fw_resource_type',
            'field'    => 'slug',
            'terms'    => sanitize_text_field( $atts['type'] ),
        ];
    }

    // Filter by initiative (by related_initiative_id meta)
    if ( $atts['initiative'] ) {
        $init_id = (int) $atts['initiative'];
        $query_args['meta_key']   = 'fw_related_initiative_id';
        $query_args['meta_value'] = $init_id;
    }

    $resources = new WP_Query( $query_args );
    if ( ! $resources->have_posts() ) return '';

    $show_filters = filter_var( $atts['show_filters'], FILTER_VALIDATE_BOOLEAN );
    $show_search  = filter_var( $atts['show_search'], FILTER_VALIDATE_BOOLEAN );
    $cols         = max( 2, min( 4, (int) $atts['columns'] ) );

    ob_start();

    wp_enqueue_style( 'fw-resources' );
    wp_enqueue_script( 'fw-resources' );
    ?>
    <div class="fw-resources-embed">
        <?php if ( $atts['title'] ) : ?>
        <h3 style="margin-bottom:1rem;"><?php echo esc_html( $atts['title'] ); ?></h3>
        <?php endif; ?>

        <?php if ( $show_search || $show_filters ) : ?>
        <div class="resources-toolbar" style="margin-bottom:1.5rem;">
            <?php if ( $show_search ) : ?>
            <div class="resources-toolbar__search">
                <input type="search" class="form-control" id="resource-search-embed-<?php echo esc_attr( uniqid() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'faithfulwitness' ); ?>">
            </div>
            <?php endif; ?>
            <?php if ( $show_filters ) : ?>
            <div class="resources-toolbar__filters">
                <button class="filter-pill active" data-filter-type="type" data-value="" aria-pressed="true">
                    <?php esc_html_e( 'All', 'faithfulwitness' ); ?>
                </button>
                <?php
                $types = get_terms( [ 'taxonomy' => 'fw_resource_type', 'hide_empty' => true ] );
                if ( $types && ! is_wp_error( $types ) ) :
                    foreach ( $types as $type ) : ?>
                    <button class="filter-pill" data-filter-type="type" data-value="<?php echo esc_attr( $type->slug ); ?>" aria-pressed="false">
                        <?php echo esc_html( $type->name ); ?>
                    </button>
                <?php endforeach; endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid--<?php echo esc_attr( $cols ); ?> resources-grid">
            <?php while ( $resources->have_posts() ) : $resources->the_post();
                fw_render_resource_card( $post );
            endwhile;
            wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'fw_resources', 'fw_shortcode_resources' );
