<?php
/**
 * Single: Organizing Group
 */
get_header();
while ( have_posts() ) : the_post();
    $city    = get_post_meta( get_the_ID(), 'fw_city', true );
    $state   = get_post_meta( get_the_ID(), 'fw_state_abbr', true );
    $lat     = get_post_meta( get_the_ID(), 'fw_lat', true );
    $lng     = get_post_meta( get_the_ID(), 'fw_lng', true );
    $email   = get_post_meta( get_the_ID(), 'fw_contact_email', true );
    $phone   = get_post_meta( get_the_ID(), 'fw_contact_phone', true );
    $website = get_post_meta( get_the_ID(), 'fw_website_url', true );
    $ig      = get_post_meta( get_the_ID(), 'fw_instagram_url', true );
    $fb      = get_post_meta( get_the_ID(), 'fw_facebook_url', true );
    $tw      = get_post_meta( get_the_ID(), 'fw_twitter_url', true );

    // Events tied to this group
    $group_events = get_posts( [
        'post_type'      => 'fw_event',
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        'meta_key'       => 'fw_related_organizing_group_id',
        'meta_value'     => get_the_ID(),
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
    ] );
    $group_events = array_filter( $group_events, fn( $ev ) => fw_is_upcoming_event( $ev->ID ) );
?>

<div class="hero hero--short">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php fw_breadcrumbs(); ?>
            <span class="hero__eyebrow"><?php echo esc_html( implode( ', ', array_filter( [ $city, $state ] ) ) ); ?></span>
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="content-with-sidebar">

            <div class="entry-content">
                <?php the_content(); ?>

                <?php if ( $lat && $lng ) : ?>
                <!-- Mini map for this group -->
                <div id="group-mini-map" style="height:300px;border-radius:var(--border-radius-lg);overflow:hidden;margin-top:var(--space-8);" aria-label="<?php esc_attr_e( 'Location map', 'faithfulwitness' ); ?>"></div>
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof L === 'undefined') return;
                    var map = L.map('group-mini-map').setView([<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                        maxZoom: 19,
                    }).addTo(map);
                    L.marker([<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>])
                        .addTo(map)
                        .bindPopup('<?php echo esc_js( get_the_title() ); ?>');
                });
                </script>
                <?php endif; ?>
            </div>

            <aside class="sidebar">

                <!-- Contact info -->
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Contact', 'faithfulwitness' ); ?></div>
                    <ul style="list-style:none;padding:0;">
                        <?php if ( $city || $state ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <span style="font-weight:600;">📍 </span>
                            <?php echo esc_html( implode( ', ', array_filter( [ $city, $state ] ) ) ); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ( $email ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <a href="mailto:<?php echo esc_attr( $email ); ?>">✉ <?php echo esc_html( $email ); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if ( $phone ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">📞 <?php echo esc_html( $phone ); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if ( $website ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer">🌐 <?php esc_html_e( 'Website', 'faithfulwitness' ); ?> ↗</a>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <!-- Social links -->
                    <?php if ( $ig || $fb || $tw ) : ?>
                    <div class="social-links" style="justify-content:flex-start;">
                        <?php if ( $ig ) : ?>
                            <a href="<?php echo esc_url( $ig ); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Instagram" style="background:rgba(27,79,114,.1);color:var(--color-primary);">IG</a>
                        <?php endif; ?>
                        <?php if ( $fb ) : ?>
                            <a href="<?php echo esc_url( $fb ); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Facebook" style="background:rgba(27,79,114,.1);color:var(--color-primary);">FB</a>
                        <?php endif; ?>
                        <?php if ( $tw ) : ?>
                            <a href="<?php echo esc_url( $tw ); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="Twitter / X" style="background:rgba(27,79,114,.1);color:var(--color-primary);">X</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-widget" style="background:var(--color-primary);color:#fff;">
                    <p style="font-weight:700;color:#fff;margin-bottom:1rem;"><?php esc_html_e( 'Ready to Organize?', 'faithfulwitness' ); ?></p>
                    <?php if ( $email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="btn btn--accent" style="width:100%;justify-content:center;">
                        <?php esc_html_e( 'Get in Touch', 'faithfulwitness' ); ?>
                    </a>
                    <?php elseif ( $website ) : ?>
                    <a href="<?php echo esc_url( $website ); ?>" class="btn btn--accent" target="_blank" rel="noopener noreferrer" style="width:100%;justify-content:center;">
                        <?php esc_html_e( 'Visit Website', 'faithfulwitness' ); ?>
                    </a>
                    <?php endif; ?>
                </div>

            </aside>

        </div>
    </div>
</section>

<!-- Group's Upcoming Events -->
<?php if ( ! empty( $group_events ) ) : ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Events', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Upcoming Events', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ( $group_events as $ev ) :
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $ev ] );
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer();
