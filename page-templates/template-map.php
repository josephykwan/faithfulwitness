<?php
/**
 * Template Name: Organizing Map
 *
 * Displays an interactive Leaflet map of all local organizing groups
 * with a filterable sidebar list.
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
            <span class="hero__eyebrow"><?php esc_html_e( 'Find Your Community', 'faithfulwitness' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $excerpt = get_the_excerpt() ) : ?>
            <p><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Map Section -->
<section class="section section--map" style="padding: 0;">
    <div class="map-layout">

        <!-- Sidebar: filter + list -->
        <aside class="map-sidebar" id="map-sidebar" aria-label="<?php esc_attr_e( 'Organizing groups list', 'faithfulwitness' ); ?>">

            <div class="map-sidebar__inner">
                <!-- Search -->
                <div class="map-sidebar__search">
                    <label for="map-search" class="sr-only"><?php esc_html_e( 'Search by city or state', 'faithfulwitness' ); ?></label>
                    <input
                        type="search"
                        id="map-search"
                        class="form-control"
                        placeholder="<?php esc_attr_e( 'Search city or state…', 'faithfulwitness' ); ?>"
                        autocomplete="off"
                    >
                </div>

                <!-- State filter -->
                <div class="map-sidebar__filters">
                    <label for="map-state-filter" class="sr-only"><?php esc_html_e( 'Filter by state', 'faithfulwitness' ); ?></label>
                    <select id="map-state-filter" class="form-control">
                        <option value=""><?php esc_html_e( 'All states', 'faithfulwitness' ); ?></option>
                        <?php
                        $states = get_terms( [ 'taxonomy' => 'fw_state', 'hide_empty' => true ] );
                        if ( $states && ! is_wp_error( $states ) ) :
                            foreach ( $states as $state ) :
                        ?>
                            <option value="<?php echo esc_attr( $state->slug ); ?>"><?php echo esc_html( $state->name ); ?></option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <!-- Count -->
                <p class="map-sidebar__count" id="map-group-count" aria-live="polite">
                    <?php
                    $total = wp_count_posts( 'fw_organizing_group' )->publish;
                    printf( esc_html( _n( '%d organizing group', '%d organizing groups', $total, 'faithfulwitness' ) ), esc_html( $total ) );
                    ?>
                </p>

                <!-- Group list (populated by JS) -->
                <ul class="map-group-list" id="map-group-list" role="list">
                    <!-- JS will render items here -->
                </ul>
            </div>
        </aside>

        <!-- Map container -->
        <div class="map-main" id="fw-map" aria-label="<?php esc_attr_e( 'Interactive map of organizing groups', 'faithfulwitness' ); ?>" role="application"></div>

    </div><!-- .map-layout -->
</section>

<!-- Page content below map -->
<?php if ( $content = apply_filters( 'the_content', get_the_content() ) ) : ?>
<section class="section">
    <div class="container container--narrow">
        <div class="entry-content">
            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Group cards grid (for non-JS fallback and SEO) -->
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'All Organizing Groups', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Find a Group Near You', 'faithfulwitness' ); ?></h2>
        </div>

        <?php
        $groups_query = new WP_Query( [
            'post_type'      => 'fw_organizing_group',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );
        if ( $groups_query->have_posts() ) : ?>
        <div class="grid grid--3" id="groups-grid">
            <?php while ( $groups_query->have_posts() ) : $groups_query->the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'organizing-group-card', [ 'post' => $post ] ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php else : ?>
        <p><?php esc_html_e( 'No organizing groups have been added yet. Check back soon!', 'faithfulwitness' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();
