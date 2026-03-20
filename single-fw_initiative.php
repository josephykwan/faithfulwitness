<?php
/**
 * Single: Initiative
 *
 * Layout:
 * - Hero with featured image
 * - Main content + sidebar (CTA, related resources quick-nav)
 * - Resources grid (articles, guides, graphics, social media)
 * - Related blog posts
 * - Related events
 */

get_header();

while ( have_posts() ) : the_post();
    $status    = get_post_meta( get_the_ID(), 'fw_initiative_status', true ) ?: 'active';
    $cta_label = get_post_meta( get_the_ID(), 'fw_initiative_cta_label', true );
    $cta_url   = get_post_meta( get_the_ID(), 'fw_initiative_cta_url', true );
    $resources = fw_get_initiative_resources( get_the_ID() );
    $blog_posts = fw_get_initiative_posts( get_the_ID(), 3 );

    // Upcoming events tied to this initiative (by shared taxonomy)
    $init_term_ids = wp_get_post_terms( get_the_ID(), 'fw_initiative_category', [ 'fields' => 'ids' ] );
    $related_events = [];
    if ( ! empty( $init_term_ids ) && ! is_wp_error( $init_term_ids ) ) {
        $related_events = get_posts( [
            'post_type'      => 'fw_event',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'meta_key'       => 'fw_event_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'tax_query'      => [
                [
                    'taxonomy' => 'fw_initiative_category',
                    'field'    => 'term_id',
                    'terms'    => $init_term_ids,
                ],
            ],
        ] );
        $related_events = array_filter( $related_events, fn( $ev ) => fw_is_upcoming_event( $ev->ID ) );
    }
?>

<!-- Hero -->
<div class="hero">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php fw_breadcrumbs(); ?>
            <span class="hero__eyebrow"><?php esc_html_e( 'Initiative', 'faithfulwitness' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $excerpt = get_the_excerpt() ) : ?>
            <p><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
            <?php if ( $cta_url ) : ?>
            <div class="hero__actions">
                <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--accent btn--lg">
                    <?php echo esc_html( $cta_label ?: __( 'Take Action', 'faithfulwitness' ) ); ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Main content + sidebar -->
<section class="section">
    <div class="container">
        <div class="content-with-sidebar">

            <!-- Main content -->
            <div class="initiative-content entry-content">
                <?php the_content(); ?>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar" aria-label="<?php esc_attr_e( 'Initiative sidebar', 'faithfulwitness' ); ?>">

                <!-- Status -->
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Initiative Status', 'faithfulwitness' ); ?></div>
                    <?php
                    $status_labels = [
                        'active'    => [ 'label' => __( 'Active', 'faithfulwitness' ),    'color' => '#1E8449' ],
                        'ongoing'   => [ 'label' => __( 'Ongoing', 'faithfulwitness' ),   'color' => '#1A5276' ],
                        'completed' => [ 'label' => __( 'Completed', 'faithfulwitness' ), 'color' => '#5D6D7E' ],
                        'paused'    => [ 'label' => __( 'Paused', 'faithfulwitness' ),    'color' => '#B7770D' ],
                    ];
                    $s = $status_labels[ $status ] ?? $status_labels['active'];
                    ?>
                    <span class="tag" style="background:<?php echo esc_attr( $s['color'] ); ?>1a; color:<?php echo esc_attr( $s['color'] ); ?>; font-size: var(--text-sm);">
                        <?php echo esc_html( $s['label'] ); ?>
                    </span>
                </div>

                <!-- Initiative categories -->
                <?php $cats = fw_get_initiative_tags( get_the_ID() ); if ( $cats ) : ?>
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Related Issues', 'faithfulwitness' ); ?></div>
                    <div style="display:flex; flex-wrap:wrap; gap: 0.5rem;">
                        <?php echo $cats; // phpcs:ignore ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Resources quick nav -->
                <?php if ( ! empty( $resources ) ) : ?>
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Resources', 'faithfulwitness' ); ?></div>
                    <?php
                    $type_groups = [];
                    foreach ( $resources as $res ) {
                        $types = wp_get_post_terms( $res->ID, 'fw_resource_type', [ 'fields' => 'names' ] );
                        $type  = ! empty( $types ) && ! is_wp_error( $types ) ? $types[0] : __( 'Other', 'faithfulwitness' );
                        $type_groups[ $type ][] = $res;
                    }
                    foreach ( $type_groups as $type_name => $type_resources ) : ?>
                        <p style="font-size:var(--text-xs);font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);margin-bottom:.5rem;">
                            <?php echo esc_html( $type_name ); ?> (<?php echo esc_html( count( $type_resources ) ); ?>)
                        </p>
                    <?php endforeach; ?>
                    <a href="#initiative-resources" class="btn btn--sm btn--outline" style="margin-top:.5rem;">
                        <?php esc_html_e( 'View all resources ↓', 'faithfulwitness' ); ?>
                    </a>
                </div>
                <?php endif; ?>

                <!-- CTA widget -->
                <?php if ( $cta_url ) : ?>
                <div class="sidebar-widget" style="background: var(--color-primary); color: #fff;">
                    <p style="font-weight:700; color:#fff; margin-bottom: 1rem;">
                        <?php echo esc_html( $cta_label ?: __( 'Take Action', 'faithfulwitness' ) ); ?>
                    </p>
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--accent" style="width:100%;justify-content:center;">
                        <?php esc_html_e( 'Get Involved', 'faithfulwitness' ); ?>
                    </a>
                </div>
                <?php endif; ?>

                <!-- Dynamic sidebar -->
                <?php if ( is_active_sidebar( 'initiative-sidebar' ) ) :
                    dynamic_sidebar( 'initiative-sidebar' );
                endif; ?>

            </aside>

        </div><!-- .content-with-sidebar -->
    </div>
</section>

<!-- Resources Section -->
<?php if ( ! empty( $resources ) ) : ?>
<section class="section section--alt" id="initiative-resources">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Resources', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Tools & Materials', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'Articles, guides, graphics, and social media assets for this initiative.', 'faithfulwitness' ); ?></p>
        </div>

        <!-- Resource type tabs -->
        <?php
        $type_tabs = [];
        foreach ( $resources as $res ) {
            $types = wp_get_post_terms( $res->ID, 'fw_resource_type' );
            if ( ! empty( $types ) && ! is_wp_error( $types ) ) {
                $type_tabs[ $types[0]->slug ] = $types[0]->name;
            }
        }
        if ( count( $type_tabs ) > 1 ) : ?>
        <div class="resource-type-tabs" style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:2rem;">
            <button class="filter-pill active" data-res-type="">
                <?php esc_html_e( 'All', 'faithfulwitness' ); ?>
            </button>
            <?php foreach ( $type_tabs as $slug => $name ) : ?>
            <button class="filter-pill" data-res-type="<?php echo esc_attr( $slug ); ?>">
                <?php echo esc_html( $name ); ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid--auto" id="initiative-resources-grid">
            <?php foreach ( $resources as $res ) : ?>
                <?php fw_render_resource_card( $res ); ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Related Blog Posts -->
<?php if ( ! empty( $blog_posts ) ) : ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Stories', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Related Stories', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ( $blog_posts as $blog_post ) :
                setup_postdata( $blog_post );
                get_template_part( 'template-parts/content', 'post' );
            endforeach;
            wp_reset_postdata(); ?>
        </div>
        <div style="text-align:center; margin-top: var(--space-8);">
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn--outline">
                <?php esc_html_e( 'Read All Stories', 'faithfulwitness' ); ?>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Related Events -->
<?php if ( ! empty( $related_events ) ) : ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Events', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Upcoming Events', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ( $related_events as $ev ) :
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $ev ] );
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer();
