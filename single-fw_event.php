<?php
/**
 * Single: Event
 */
get_header();
while ( have_posts() ) : the_post();
    $date       = fw_get_event_date( get_the_ID() );
    $time       = fw_get_event_time( get_the_ID() );
    $location   = fw_get_event_location( get_the_ID() );
    $scope      = get_post_meta( get_the_ID(), 'fw_event_scope', true ) ?: 'national';
    $virtual    = get_post_meta( get_the_ID(), 'fw_event_virtual', true );
    $virt_link  = get_post_meta( get_the_ID(), 'fw_event_virtual_link', true );
    $reg_link   = get_post_meta( get_the_ID(), 'fw_event_registration_link', true );
    $address    = get_post_meta( get_the_ID(), 'fw_event_address', true );
    $city       = get_post_meta( get_the_ID(), 'fw_event_city', true );
    $state      = get_post_meta( get_the_ID(), 'fw_event_state', true );
    $loc_name   = get_post_meta( get_the_ID(), 'fw_event_location_name', true );
    $group_id   = get_post_meta( get_the_ID(), 'fw_related_organizing_group_id', true );
    $scope_label = $scope === 'national' ? __( 'National Event', 'faithfulwitness' ) : __( 'Local Event', 'faithfulwitness' );
    $scope_class = $scope === 'national' ? 'tag--national' : 'tag--local';
    $is_upcoming = fw_is_upcoming_event( get_the_ID() );
?>

<div class="hero hero--short">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php fw_breadcrumbs(); ?>
            <div class="hero__eyebrow">
                <span class="tag <?php echo esc_attr( $scope_class ); ?>" style="color:#fff;background:rgba(255,255,255,.2);"><?php echo esc_html( $scope_label ); ?></span>
            </div>
            <h1><?php the_title(); ?></h1>
            <?php if ( $reg_link && $is_upcoming ) : ?>
            <div class="hero__actions">
                <a href="<?php echo esc_url( $reg_link ); ?>" class="btn btn--accent btn--lg" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e( 'Register Now', 'faithfulwitness' ); ?> ↗
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="content-with-sidebar">

            <div class="entry-content">
                <?php if ( ! $is_upcoming ) : ?>
                <div style="background:#FEF9E7;border:1px solid #F9CA24;border-radius:var(--border-radius);padding:1rem 1.5rem;margin-bottom:2rem;">
                    <strong><?php esc_html_e( 'This event has passed.', 'faithfulwitness' ); ?></strong>
                </div>
                <?php endif; ?>
                <?php the_content(); ?>
            </div>

            <aside class="sidebar">

                <!-- Event details -->
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Event Details', 'faithfulwitness' ); ?></div>
                    <ul style="list-style:none;padding:0;line-height:1.8;">
                        <?php if ( $date ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <strong style="display:block;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);"><?php esc_html_e( 'Date', 'faithfulwitness' ); ?></strong>
                            <?php echo esc_html( $date ); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ( $time ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <strong style="display:block;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);"><?php esc_html_e( 'Time', 'faithfulwitness' ); ?></strong>
                            <?php echo esc_html( $time ); ?>
                        </li>
                        <?php endif; ?>
                        <?php if ( $virtual === '1' ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <strong style="display:block;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);"><?php esc_html_e( 'Format', 'faithfulwitness' ); ?></strong>
                            <?php esc_html_e( 'Online / Virtual', 'faithfulwitness' ); ?>
                            <?php if ( $virt_link ) : ?>
                            <br><a href="<?php echo esc_url( $virt_link ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Join Link', 'faithfulwitness' ); ?> ↗</a>
                            <?php endif; ?>
                        </li>
                        <?php elseif ( $location ) : ?>
                        <li style="margin-bottom:.75rem;">
                            <strong style="display:block;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);"><?php esc_html_e( 'Location', 'faithfulwitness' ); ?></strong>
                            <?php if ( $loc_name ) echo esc_html( $loc_name ) . '<br>'; ?>
                            <?php if ( $address ) echo esc_html( $address ) . '<br>'; ?>
                            <?php echo esc_html( implode( ', ', array_filter( [ $city, $state ] ) ) ); ?>
                        </li>
                        <?php endif; ?>
                        <?php
                        // Related organizing group
                        if ( $group_id ) :
                            $group = get_post( $group_id );
                        ?>
                        <li style="margin-bottom:.75rem;">
                            <strong style="display:block;font-size:var(--text-xs);text-transform:uppercase;letter-spacing:.05em;color:var(--color-text-muted);"><?php esc_html_e( 'Organized By', 'faithfulwitness' ); ?></strong>
                            <a href="<?php echo esc_url( get_permalink( $group ) ); ?>"><?php echo esc_html( get_the_title( $group ) ); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ( $reg_link && $is_upcoming ) : ?>
                    <a href="<?php echo esc_url( $reg_link ); ?>" class="btn btn--accent" style="width:100%;justify-content:center;margin-top:.5rem;" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Register Now', 'faithfulwitness' ); ?> ↗
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Related initiative tags -->
                <?php $init_tags = fw_get_initiative_tags( get_the_ID() ); if ( $init_tags ) : ?>
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Related Initiatives', 'faithfulwitness' ); ?></div>
                    <div style="display:flex;flex-wrap:wrap;gap:.5rem;"><?php echo $init_tags; // phpcs:ignore ?></div>
                </div>
                <?php endif; ?>

            </aside>

        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer();
