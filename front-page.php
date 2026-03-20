<?php
/**
 * Front Page / Homepage
 *
 * Sections (all editable via WP Customizer or page content):
 * 1. Hero
 * 2. Impact stats bar
 * 3. Initiatives grid
 * 4. Map teaser
 * 5. Latest blog posts
 * 6. Events preview
 * 7. Newsletter signup
 */
get_header(); ?>

<!-- 1. Hero -->
<section class="hero">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <?php else : ?>
    <div class="hero__bg" style="background: linear-gradient(135deg, #1B4F72, #2980B9);"></div>
    <?php endif; ?>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php echo esc_html( get_theme_mod( 'fw_hero_eyebrow', __( 'Immigrant Justice', 'faithfulwitness' ) ) ); ?></span>
            <h1><?php echo esc_html( get_theme_mod( 'fw_hero_title', get_bloginfo( 'name' ) ) ); ?></h1>
            <p><?php echo esc_html( get_theme_mod( 'fw_hero_subtitle', get_bloginfo( 'description' ) ) ); ?></p>
            <div class="hero__actions">
                <?php
                $cta1_url   = get_theme_mod( 'fw_hero_cta1_url', '#initiatives' );
                $cta1_label = get_theme_mod( 'fw_hero_cta1_label', __( 'Our Initiatives', 'faithfulwitness' ) );
                $cta2_url   = get_theme_mod( 'fw_hero_cta2_url', '#map' );
                $cta2_label = get_theme_mod( 'fw_hero_cta2_label', __( 'Find a Local Group', 'faithfulwitness' ) );
                if ( $cta1_url ) : ?>
                <a href="<?php echo esc_url( $cta1_url ); ?>" class="btn btn--accent btn--lg"><?php echo esc_html( $cta1_label ); ?></a>
                <?php endif; ?>
                <?php if ( $cta2_url ) : ?>
                <a href="<?php echo esc_url( $cta2_url ); ?>" class="btn btn--outline-white btn--lg"><?php echo esc_html( $cta2_label ); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- 2. Impact Stats -->
<?php
$stats = [
    [ 'number' => get_theme_mod( 'fw_stat1_num', '50+' ),  'label' => get_theme_mod( 'fw_stat1_label', __( 'Cities organized', 'faithfulwitness' ) ) ],
    [ 'number' => get_theme_mod( 'fw_stat2_num', '10K+' ), 'label' => get_theme_mod( 'fw_stat2_label', __( 'Community members', 'faithfulwitness' ) ) ],
    [ 'number' => get_theme_mod( 'fw_stat3_num', '25+' ),  'label' => get_theme_mod( 'fw_stat3_label', __( 'Active initiatives', 'faithfulwitness' ) ) ],
    [ 'number' => get_theme_mod( 'fw_stat4_num', '5' ),    'label' => get_theme_mod( 'fw_stat4_label', __( 'Years of advocacy', 'faithfulwitness' ) ) ],
];
$show_stats = array_filter( $stats, fn( $s ) => $s['number'] );
if ( ! empty( $show_stats ) ) : ?>
<div class="stats-bar">
    <div class="container">
        <div class="stats-bar__grid">
            <?php foreach ( $show_stats as $stat ) : ?>
            <div class="stat-item">
                <span class="stat-item__number"><?php echo esc_html( $stat['number'] ); ?></span>
                <span class="stat-item__label"><?php echo esc_html( $stat['label'] ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- 3. Current Initiatives -->
<section class="section" id="initiatives">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="eyebrow"><?php esc_html_e( 'Our Work', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Current Initiatives', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'Active campaigns and organizing efforts for immigrant justice.', 'faithfulwitness' ); ?></p>
        </div>

        <?php
        $initiatives = get_posts( [
            'post_type'      => 'fw_initiative',
            'post_status'    => 'publish',
            'posts_per_page' => 6,
            'meta_query'     => [
                'relation' => 'OR',
                [ 'key' => 'fw_initiative_status', 'value' => 'active', 'compare' => '=' ],
                [ 'key' => 'fw_initiative_status', 'value' => 'ongoing', 'compare' => '=' ],
                [ 'key' => 'fw_initiative_status', 'compare' => 'NOT EXISTS' ],
            ],
        ] );
        if ( ! empty( $initiatives ) ) : ?>
        <div class="grid grid--3">
            <?php foreach ( $initiatives as $init ) :
                $cta_url   = get_post_meta( $init->ID, 'fw_initiative_cta_url', true );
                $cta_label = get_post_meta( $init->ID, 'fw_initiative_cta_label', true );
            ?>
            <article <?php post_class( 'card initiative-card', $init ); ?>>
                <?php if ( has_post_thumbnail( $init ) ) : ?>
                <div class="card__image">
                    <a href="<?php echo esc_url( get_permalink( $init ) ); ?>" tabindex="-1">
                        <?php echo get_the_post_thumbnail( $init, 'fw-card', [ 'loading' => 'lazy' ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="card__body">
                    <div class="card__meta"><?php echo fw_get_initiative_tags( $init->ID ); // phpcs:ignore ?></div>
                    <h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $init ) ); ?>"><?php echo esc_html( get_the_title( $init ) ); ?></a></h3>
                    <p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $init ), 20, '…' ) ); ?></p>
                    <div class="card__footer">
                        <a href="<?php echo esc_url( get_permalink( $init ) ); ?>" class="btn btn--sm btn--outline"><?php esc_html_e( 'Learn More', 'faithfulwitness' ); ?></a>
                        <?php if ( $cta_url ) : ?>
                        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--sm btn--accent"><?php echo esc_html( $cta_label ?: __( 'Act Now', 'faithfulwitness' ) ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:var(--space-8);">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'fw_initiative' ) ); ?>" class="btn btn--outline btn--lg">
                <?php esc_html_e( 'View All Initiatives', 'faithfulwitness' ); ?>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- 4. Map Teaser -->
<section class="section section--dark" id="map">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="eyebrow" style="color:var(--color-accent-light);"><?php esc_html_e( 'Community Network', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Find Organizing Groups Near You', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'Local groups are organizing across the country. Connect with your community.', 'faithfulwitness' ); ?></p>
        </div>
        <?php
        $map_page = get_posts( [ 'post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/template-map.php', 'posts_per_page' => 1 ] );
        $map_url  = ! empty( $map_page ) ? get_permalink( $map_page[0] ) : get_post_type_archive_link( 'fw_organizing_group' );
        ?>
        <div style="text-align:center;">
            <a href="<?php echo esc_url( $map_url ); ?>" class="btn btn--accent btn--lg">
                <?php esc_html_e( 'Explore the Map', 'faithfulwitness' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- 5. Latest Stories -->
<section class="section">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Blog', 'faithfulwitness' ); ?></span>
                <h2><?php esc_html_e( 'Latest Stories', 'faithfulwitness' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="btn btn--outline">
                <?php esc_html_e( 'All Stories', 'faithfulwitness' ); ?>
            </a>
        </div>
        <?php
        $posts = get_posts( [ 'posts_per_page' => 3 ] );
        if ( ! empty( $posts ) ) : ?>
        <div class="grid grid--3">
            <?php foreach ( $posts as $p ) :
                setup_postdata( $p );
                get_template_part( 'template-parts/content', 'post' );
            endforeach;
            wp_reset_postdata(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- 6. Upcoming Events Preview -->
<?php
$upcoming = get_posts( [
    'post_type'      => 'fw_event',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'meta_key'       => 'fw_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
] );
$upcoming = array_filter( $upcoming, fn( $ev ) => fw_is_upcoming_event( $ev->ID ) );
if ( ! empty( $upcoming ) ) : ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Events', 'faithfulwitness' ); ?></span>
                <h2><?php esc_html_e( 'Upcoming Events', 'faithfulwitness' ); ?></h2>
            </div>
            <?php
            $events_page = get_posts( [ 'post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/template-events.php', 'posts_per_page' => 1 ] );
            $events_url  = ! empty( $events_page ) ? get_permalink( $events_page[0] ) : get_post_type_archive_link( 'fw_event' );
            ?>
            <a href="<?php echo esc_url( $events_url ); ?>" class="btn btn--outline"><?php esc_html_e( 'All Events', 'faithfulwitness' ); ?></a>
        </div>
        <div class="grid grid--3">
            <?php foreach ( $upcoming as $ev ) :
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $ev ] );
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 7. Page content (WP editor blocks for flexibility) -->
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php if ( $content = get_the_content() ) : ?>
<section class="section">
    <div class="container">
        <div class="entry-content"><?php the_content(); ?></div>
    </div>
</section>
<?php endif; endwhile; endif; ?>

<!-- Newsletter / CTA -->
<section class="section section--dark">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto;">
            <span class="eyebrow" style="color:var(--color-accent-light);"><?php esc_html_e( 'Stay Connected', 'faithfulwitness' ); ?></span>
            <h2 style="color:#fff;"><?php echo esc_html( get_theme_mod( 'fw_newsletter_title', __( 'Join Our Community', 'faithfulwitness' ) ) ); ?></h2>
            <p style="opacity:.9;"><?php echo esc_html( get_theme_mod( 'fw_newsletter_text', __( 'Get updates on our campaigns, resources, and ways to take action.', 'faithfulwitness' ) ) ); ?></p>
            <?php
            $form_url = get_theme_mod( 'fw_newsletter_url', '' );
            if ( $form_url ) : ?>
            <a href="<?php echo esc_url( $form_url ); ?>" class="btn btn--accent btn--lg" style="margin-top:var(--space-6);">
                <?php esc_html_e( 'Sign Up', 'faithfulwitness' ); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer();
