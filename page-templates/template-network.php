<?php
/**
 * Template Name: Find Your Network
 *
 * Interactive map of organizing groups + searchable partner directory.
 * Inspired by Faith in Action's federation map + Moms Demand chapter finder.
 */

get_header(); ?>

<!-- Hero -->
<section class="network-hero" id="network-top">
    <div class="container">
        <span class="hero__eyebrow" style="color:var(--color-accent-light);"><?php esc_html_e( 'Community Network', 'faithfulwitness' ); ?></span>
        <h1><?php esc_html_e( 'You are not alone.', 'faithfulwitness' ); ?><br><?php esc_html_e( 'Find faithful witnesses near you.', 'faithfulwitness' ); ?></h1>
        <p><?php esc_html_e( 'Local organizing groups and partner organizations are working across the country. Connect, learn, and build together.', 'faithfulwitness' ); ?></p>
    </div>
</section>

<!-- Map Section -->
<section class="network-map-section section" id="map">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Interactive Map', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Organizing Groups Near You', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'The map below shows local organizing groups in the Faithful Witness network. Click a pin for contact info and a link to their page.', 'faithfulwitness' ); ?></p>
        </div>

        <!-- Leaflet interactive map -->
        <div id="fw-network-map" class="fw-network-map" aria-label="<?php esc_attr_e( 'Interactive map of partner organizations', 'faithfulwitness' ); ?>"></div>

        <!-- Map legend -->
        <div class="map-legend" aria-label="<?php esc_attr_e( 'Map pin legend', 'faithfulwitness' ); ?>">
            <span class="map-legend__item"><span class="map-legend__dot" style="background:#1B4F72;"></span><?php esc_html_e( 'National Partner', 'faithfulwitness' ); ?></span>
            <span class="map-legend__item"><span class="map-legend__dot" style="background:#D4750A;"></span><?php esc_html_e( 'Local Church', 'faithfulwitness' ); ?></span>
            <span class="map-legend__item"><span class="map-legend__dot" style="background:#1A5A6A;"></span><?php esc_html_e( 'Organizing Group', 'faithfulwitness' ); ?></span>
        </div>

        <p class="network-map-hint">
            <?php esc_html_e( 'Click a pin to learn more. Click a card below to highlight it on the map. To add your group, use the registration form at the bottom of this page.', 'faithfulwitness' ); ?>
        </p>
    </div>
</section>

<!-- Partner Organizations Directory -->
<section class="partners-directory section section--alt" id="partners">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Organizations', 'faithfulwitness' ); ?></span>
                <h2><?php esc_html_e( 'Partner Organizations', 'faithfulwitness' ); ?></h2>
                <p><?php esc_html_e( 'National and local organizations in the Faithful Witness network.', 'faithfulwitness' ); ?></p>
            </div>
            <!-- Simple client-side search -->
            <div style="min-width:240px;">
                <label for="partner-search" class="sr-only"><?php esc_html_e( 'Search organizations', 'faithfulwitness' ); ?></label>
                <input type="search"
                       id="partner-search"
                       class="form-control"
                       placeholder="<?php esc_attr_e( 'Search by name or city…', 'faithfulwitness' ); ?>"
                       autocomplete="off">
            </div>
        </div>

        <div class="partner-cards-grid" id="partner-cards-grid">
            <?php
            $groups = get_posts( [
                'post_type'      => 'fw_organizing_group',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ] );

            if ( ! empty( $groups ) ) :
                foreach ( $groups as $group ) :
                    $city       = get_post_meta( $group->ID, 'fw_city', true );
                    $state      = get_post_meta( $group->ID, 'fw_state_abbr', true );
                    $email      = get_post_meta( $group->ID, 'fw_contact_email', true );
                    $website    = get_post_meta( $group->ID, 'fw_website_url', true );
                    $fb         = get_post_meta( $group->ID, 'fw_facebook_url', true );
                    $ig         = get_post_meta( $group->ID, 'fw_instagram_url', true );
                    $tw         = get_post_meta( $group->ID, 'fw_twitter_url', true );
                    $terms      = wp_get_post_terms( $group->ID, 'fw_initiative_category', [ 'fields' => 'names' ] );
                    $location   = array_filter( [ $city, $state ] );
                    $loc_id     = sanitize_title( get_the_title( $group ) ); // matches JSON "id" field
            ?>
            <div class="partner-card"
                 data-name="<?php echo esc_attr( strtolower( get_the_title( $group ) . ' ' . implode( ' ', $location ) ) ); ?>"
                 data-location-id="<?php echo esc_attr( $loc_id ); ?>">
                <span class="partner-card__scope">
                    <?php echo esc_html( implode( ', ', $location ) ?: __( 'National Network', 'faithfulwitness' ) ); ?>
                </span>
                <h3 class="partner-card__name">
                    <?php if ( $website ) : ?>
                    <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer" style="text-decoration:none;color:inherit;">
                        <?php echo esc_html( get_the_title( $group ) ); ?>
                    </a>
                    <?php else : ?>
                    <?php echo esc_html( get_the_title( $group ) ); ?>
                    <?php endif; ?>
                </h3>
                <?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                <div class="partner-card__focus">
                    <?php foreach ( $terms as $term_name ) : ?>
                    <span class="tag tag--primary"><?php echo esc_html( $term_name ); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if ( $excerpt = get_the_excerpt( $group ) ) : ?>
                <p class="partner-card__desc"><?php echo esc_html( wp_trim_words( $excerpt, 25, '…' ) ); ?></p>
                <?php endif; ?>
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:var(--space-3);margin-top:auto;">
                    <?php if ( $website ) : ?>
                    <a href="<?php echo esc_url( $website ); ?>" class="partner-card__contact" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Website ↗', 'faithfulwitness' ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="partner-card__contact">
                        <?php echo esc_html( $email ); ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
                endforeach;
            else : ?>
            <div style="grid-column:1/-1;text-align:center;padding:3rem;">
                <p><?php esc_html_e( 'Partner organizations will appear here. Use the registration link below to add your group.', 'faithfulwitness' ); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Register CTA -->
<section class="network-register-cta section" id="register">
    <div class="container">
        <div style="max-width:560px;margin:0 auto;">
            <span class="eyebrow"><?php esc_html_e( 'Join the Network', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Register your church or group', 'faithfulwitness' ); ?></h2>
            <p style="color:var(--color-text-light);font-size:var(--text-lg);margin-bottom:var(--space-8);">
                <?php esc_html_e( 'Is your congregation or organization doing faithful witness work around immigration? Get connected with the national network and appear on this map.', 'faithfulwitness' ); ?>
            </p>
            <a href="<?php echo esc_url( get_theme_mod( 'fw_network_register_url', home_url( '/contact' ) ) ); ?>"
               class="btn btn--primary btn--lg">
                <?php esc_html_e( 'Register Your Group', 'faithfulwitness' ); ?>
            </a>
        </div>
    </div>
</section>

<script>
// Simple client-side partner search
( function () {
    const search = document.getElementById( 'partner-search' );
    if ( ! search ) return;
    const cards = document.querySelectorAll( '.partner-card' );
    search.addEventListener( 'input', function () {
        const q = search.value.toLowerCase().trim();
        cards.forEach( function ( card ) {
            const name = card.dataset.name || '';
            card.style.display = ( ! q || name.includes( q ) ) ? '' : 'none';
        } );
    } );
} )();
</script>

<?php get_footer();
