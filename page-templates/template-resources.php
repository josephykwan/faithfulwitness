<?php
/**
 * Template Name: Resource Library
 *
 * Filterable resource grid with three taxonomy filters:
 *   - Resource Type (pill buttons)
 *   - Issue Area (pill buttons)
 *   - Audience (pill buttons)
 * Plus a search input. All filtering is client-side.
 */

get_header(); ?>

<!-- Hero -->
<div class="hero hero--short">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php esc_html_e( 'Tools for Organizing', 'faithfulwitness' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $excerpt = get_the_excerpt() ) : ?>
            <p><?php echo esc_html( $excerpt ); ?></p>
            <?php else : ?>
            <p><?php esc_html_e( 'Guides, toolkits, articles, and prayer resources for congregations, leaders, and individuals.', 'faithfulwitness' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ================================================================
     START HERE — Featured Resources
     ================================================================ -->
<?php
$featured_resources = get_posts( [
    'post_type'      => 'fw_resource',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [ [
        'key'     => 'fw_resource_featured',
        'value'   => '1',
        'compare' => '=',
    ] ],
] );

if ( ! empty( $featured_resources ) ) : ?>
<section class="section resources-featured-section" id="start-here">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="eyebrow"><?php esc_html_e( 'Start Here', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Curated for You', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'These three resources are the best place to begin. Chosen by our team for new and returning readers.', 'faithfulwitness' ); ?></p>
        </div>
        <div class="featured-resources-grid">
            <?php foreach ( $featured_resources as $fr ) :
                $type_tag  = fw_get_resource_type_tag( $fr->ID );
                $res_url   = get_post_meta( $fr->ID, 'fw_resource_url', true );
                $file_id   = get_post_meta( $fr->ID, 'fw_resource_file_id', true );
                $link      = $res_url ?: ( $file_id ? wp_get_attachment_url( $file_id ) : get_permalink( $fr ) );
            ?>
            <article class="featured-resource-card">
                <div class="featured-resource-card__star" aria-hidden="true">★</div>
                <?php if ( $type_tag ) echo $type_tag; // phpcs:ignore ?>
                <h3 class="featured-resource-card__title">
                    <a href="<?php echo esc_url( $link ); ?>"<?php echo $res_url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html( $fr->post_title ); ?>
                    </a>
                </h3>
                <?php if ( $fr->post_excerpt ) : ?>
                <p class="featured-resource-card__excerpt"><?php echo esc_html( wp_trim_words( $fr->post_excerpt, 25, '…' ) ); ?></p>
                <?php endif; ?>
                <a href="<?php echo esc_url( $link ); ?>" class="btn btn--sm btn--primary" <?php echo $res_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <?php esc_html_e( 'Access Resource →', 'faithfulwitness' ); ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Resource Library -->
<section class="section resources-library" id="resource-library">
    <div class="container">

        <!-- Search -->
        <div class="resources-toolbar" id="resources-toolbar">
            <div class="resources-toolbar__search">
                <label for="resource-search" class="sr-only"><?php esc_html_e( 'Search resources', 'faithfulwitness' ); ?></label>
                <input
                    type="search"
                    id="resource-search"
                    class="form-control"
                    placeholder="<?php esc_attr_e( 'Search resources…', 'faithfulwitness' ); ?>"
                    autocomplete="off"
                >
            </div>
        </div>

        <!-- Filter: Resource Type -->
        <div class="filter-group" style="margin-bottom:var(--space-4);">
            <p class="filter-group__label"><?php esc_html_e( 'Type', 'faithfulwitness' ); ?></p>
            <div class="resources-toolbar__filters" role="group" aria-label="<?php esc_attr_e( 'Filter by resource type', 'faithfulwitness' ); ?>">
                <button class="filter-pill active" data-filter-type="type" data-value="" aria-pressed="true">
                    <?php esc_html_e( 'All Types', 'faithfulwitness' ); ?>
                </button>
                <?php
                $resource_types = get_terms( [ 'taxonomy' => 'fw_resource_type', 'hide_empty' => false ] );
                if ( $resource_types && ! is_wp_error( $resource_types ) ) :
                    foreach ( $resource_types as $type ) :
                        $pill_class = [
                            'article'      => 'filter-pill--article',
                            'pdf-guide'    => 'filter-pill--guide',
                            'toolkit'      => 'filter-pill--graphic',
                            'prayer-guide' => 'filter-pill--social',
                            'daily-guide'  => 'filter-pill--guide',
                            'graphic'      => 'filter-pill--graphic',
                            'social-media' => 'filter-pill--social',
                        ][ $type->slug ] ?? '';
                ?>
                    <button class="filter-pill <?php echo esc_attr( $pill_class ); ?>"
                            data-filter-type="type"
                            data-value="<?php echo esc_attr( $type->slug ); ?>"
                            aria-pressed="false">
                        <?php echo esc_html( $type->name ); ?>
                        <span class="filter-pill__count"><?php echo esc_html( $type->count ); ?></span>
                    </button>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>

        <!-- Filter: Issue Area -->
        <?php
        $issue_areas = get_terms( [ 'taxonomy' => 'fw_resource_issue_area', 'hide_empty' => false ] );
        if ( $issue_areas && ! is_wp_error( $issue_areas ) && ! empty( $issue_areas ) ) : ?>
        <div class="filter-group" style="margin-bottom:var(--space-4);">
            <p class="filter-group__label"><?php esc_html_e( 'Issue Area', 'faithfulwitness' ); ?></p>
            <div class="resources-toolbar__filters" role="group" aria-label="<?php esc_attr_e( 'Filter by issue area', 'faithfulwitness' ); ?>">
                <button class="filter-pill active" data-filter-type="issue_area" data-value="" aria-pressed="true">
                    <?php esc_html_e( 'All Issues', 'faithfulwitness' ); ?>
                </button>
                <?php foreach ( $issue_areas as $issue ) : ?>
                <button class="filter-pill"
                        data-filter-type="issue_area"
                        data-value="<?php echo esc_attr( $issue->slug ); ?>"
                        aria-pressed="false">
                    <?php echo esc_html( $issue->name ); ?>
                    <span class="filter-pill__count"><?php echo esc_html( $issue->count ); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Filter: Audience -->
        <?php
        $audiences = get_terms( [ 'taxonomy' => 'fw_resource_audience', 'hide_empty' => false ] );
        if ( $audiences && ! is_wp_error( $audiences ) && ! empty( $audiences ) ) : ?>
        <div class="filter-group" style="margin-bottom:var(--space-6);padding-bottom:var(--space-6);border-bottom:1px solid var(--color-border);">
            <p class="filter-group__label"><?php esc_html_e( 'For', 'faithfulwitness' ); ?></p>
            <div class="resources-toolbar__filters" role="group" aria-label="<?php esc_attr_e( 'Filter by audience', 'faithfulwitness' ); ?>">
                <button class="filter-pill active" data-filter-type="audience" data-value="" aria-pressed="true">
                    <?php esc_html_e( 'Everyone', 'faithfulwitness' ); ?>
                </button>
                <?php foreach ( $audiences as $aud ) : ?>
                <button class="filter-pill"
                        data-filter-type="audience"
                        data-value="<?php echo esc_attr( $aud->slug ); ?>"
                        aria-pressed="false">
                    <?php echo esc_html( $aud->name ); ?>
                    <span class="filter-pill__count"><?php echo esc_html( $aud->count ); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Active filters bar -->
        <div class="resources-active-filters" id="active-filters" aria-live="polite" style="display:none;">
            <span class="resources-active-filters__label"><?php esc_html_e( 'Filtering by:', 'faithfulwitness' ); ?></span>
            <span id="active-filter-tags"></span>
            <button id="clear-filters" class="btn btn--sm btn--outline" style="margin-left:auto;">
                <?php esc_html_e( 'Clear all', 'faithfulwitness' ); ?>
            </button>
        </div>

        <!-- Count -->
        <p class="resources-count" id="resource-count" aria-live="polite" style="margin-bottom:var(--space-6);color:var(--color-text-muted);font-size:var(--text-sm);">
            <?php
            $total = wp_count_posts( 'fw_resource' )->publish;
            printf(
                esc_html( _n( 'Showing %d resource', 'Showing %d resources', $total, 'faithfulwitness' ) ),
                esc_html( $total )
            );
            ?>
        </p>

        <!-- Resource grid -->
        <div class="grid grid--auto resources-grid" id="resources-grid">
            <?php
            $resources_query = new WP_Query( [
                'post_type'      => 'fw_resource',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',
            ] );
            if ( $resources_query->have_posts() ) :
                while ( $resources_query->have_posts() ) : $resources_query->the_post();
                    fw_render_resource_card( $post );
                endwhile;
                wp_reset_postdata();
            else : ?>
            <div style="grid-column:1/-1;text-align:center;padding:3rem;">
                <p><?php esc_html_e( 'No resources have been added yet. Check back soon!', 'faithfulwitness' ); ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- No results message -->
        <div class="resources-no-results" id="resources-no-results" style="display:none;text-align:center;padding:3rem 0;">
            <p style="font-size:var(--text-xl);color:var(--color-text-muted);">
                <?php esc_html_e( 'No resources match your search. Try different filters.', 'faithfulwitness' ); ?>
            </p>
            <button id="reset-all-filters" class="btn btn--outline" style="margin-top:1rem;">
                <?php esc_html_e( 'Reset Filters', 'faithfulwitness' ); ?>
            </button>
        </div>

    </div>
</section>

<!-- ================================================================
     RESOURCE PACKS BY CAMPAIGN
     ================================================================ -->
<?php
$campaigns = get_posts( [
    'post_type'      => 'fw_initiative',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

$campaign_packs = [];
foreach ( $campaigns as $campaign ) {
    $campaign_resources = get_posts( [
        'post_type'      => 'fw_resource',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'meta_query'     => [ [
            'key'     => 'fw_related_initiative_id',
            'value'   => $campaign->ID,
            'compare' => '=',
        ] ],
    ] );
    if ( ! empty( $campaign_resources ) ) {
        $campaign_packs[] = [ 'campaign' => $campaign, 'resources' => $campaign_resources ];
    }
}

if ( ! empty( $campaign_packs ) ) : ?>
<section class="section section--alt campaign-packs-section" id="resource-packs">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Campaign Toolkits', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Resource Packs by Campaign', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'Everything you need to engage with a specific campaign — curated in one place.', 'faithfulwitness' ); ?></p>
        </div>

        <?php foreach ( $campaign_packs as $pack ) :
            $camp    = $pack['campaign'];
            $cta_url = get_post_meta( $camp->ID, 'fw_initiative_cta_url', true );
        ?>
        <div class="campaign-pack">
            <div class="campaign-pack__header">
                <div>
                    <h3 class="campaign-pack__title"><?php echo esc_html( $camp->post_title ); ?></h3>
                    <?php if ( $camp->post_excerpt ) : ?>
                    <p class="campaign-pack__desc"><?php echo esc_html( wp_trim_words( $camp->post_excerpt, 20, '…' ) ); ?></p>
                    <?php endif; ?>
                </div>
                <a href="<?php echo esc_url( $cta_url ?: get_permalink( $camp ) ); ?>" class="btn btn--outline btn--sm">
                    <?php esc_html_e( 'View Campaign →', 'faithfulwitness' ); ?>
                </a>
            </div>
            <div class="campaign-pack__resources">
                <?php foreach ( $pack['resources'] as $res ) :
                    $type_tag = fw_get_resource_type_tag( $res->ID );
                    $res_url  = get_post_meta( $res->ID, 'fw_resource_url', true );
                    $file_id  = get_post_meta( $res->ID, 'fw_resource_file_id', true );
                    $link     = $res_url ?: ( $file_id ? wp_get_attachment_url( $file_id ) : get_permalink( $res ) );
                ?>
                <article class="campaign-pack__card">
                    <?php if ( $type_tag ) echo $type_tag; // phpcs:ignore ?>
                    <h4 class="campaign-pack__card-title">
                        <a href="<?php echo esc_url( $link ); ?>"<?php echo $res_url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <?php echo esc_html( $res->post_title ); ?>
                        </a>
                    </h4>
                    <?php if ( $res->post_excerpt ) : ?>
                    <p class="campaign-pack__card-excerpt"><?php echo esc_html( wp_trim_words( $res->post_excerpt, 15, '…' ) ); ?></p>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- Request a Resource -->
<section class="resources-request-section section" id="request-resource">
    <div class="container" style="text-align:center;">
        <p style="font-size:var(--text-lg);color:var(--color-text-muted);">
            <?php esc_html_e( "Don't see what you need?", 'faithfulwitness' ); ?>
            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" style="color:var(--color-primary);font-weight:600;text-decoration:underline;">
                <?php esc_html_e( 'Let us know →', 'faithfulwitness' ); ?>
            </a>
        </p>
    </div>
</section>

<?php if ( $content = apply_filters( 'the_content', get_the_content() ) ) : ?>
<section class="section section--alt">
    <div class="container container--narrow">
        <div class="entry-content"><?php echo $content; // phpcs:ignore ?></div>
    </div>
</section>
<?php endif; ?>

<?php get_footer();
