<?php
/**
 * Template part: event card
 * Expects $args['post'] to be set or uses global $post.
 */
$event = $args['post'] ?? $GLOBALS['post'];
if ( ! $event instanceof WP_Post ) return;

$scope        = get_post_meta( $event->ID, 'fw_event_scope', true ) ?: 'national';
$date_str     = fw_get_event_date( $event->ID );
$time_str     = fw_get_event_time( $event->ID );
$location     = fw_get_event_location( $event->ID );
$reg_link     = get_post_meta( $event->ID, 'fw_event_registration_link', true );
$virtual      = get_post_meta( $event->ID, 'fw_event_virtual', true );
$scope_class  = $scope === 'national' ? 'tag--national' : 'tag--local';
$scope_label  = $scope === 'national' ? __( 'National', 'faithfulwitness' ) : __( 'Local', 'faithfulwitness' );
?>
<article id="event-<?php echo esc_attr( $event->ID ); ?>" class="event-card card" data-scope="<?php echo esc_attr( $scope ); ?>">

    <?php if ( has_post_thumbnail( $event ) ) : ?>
    <div class="card__image">
        <a href="<?php echo esc_url( get_permalink( $event ) ); ?>" tabindex="-1">
            <?php echo get_the_post_thumbnail( $event, 'fw-card', [ 'loading' => 'lazy' ] ); ?>
        </a>
    </div>
    <?php else : ?>
    <div class="event-card__date-block">
        <?php
        $date_raw = get_post_meta( $event->ID, 'fw_event_date', true );
        if ( $date_raw ) :
            $ts = strtotime( $date_raw );
        ?>
        <span class="event-card__month"><?php echo esc_html( date_i18n( 'M', $ts ) ); ?></span>
        <span class="event-card__day"><?php echo esc_html( date_i18n( 'j', $ts ) ); ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="card__body">
        <div class="card__meta">
            <span class="tag <?php echo esc_attr( $scope_class ); ?>"><?php echo esc_html( $scope_label ); ?></span>
            <?php if ( $virtual === '1' ) : ?>
                <span class="tag" style="background:#EBF5FB;color:#1A5276;"><?php esc_html_e( 'Virtual', 'faithfulwitness' ); ?></span>
            <?php endif; ?>
            <?php echo fw_get_initiative_tags( $event->ID ); // phpcs:ignore ?>
        </div>

        <h3 class="card__title">
            <a href="<?php echo esc_url( get_permalink( $event ) ); ?>"><?php echo esc_html( get_the_title( $event ) ); ?></a>
        </h3>

        <div class="event-card__details">
            <?php if ( $date_str ) : ?>
            <div class="event-card__detail">
                <span class="event-card__detail-icon" aria-hidden="true">📅</span>
                <span><?php echo esc_html( $date_str ); ?><?php echo $time_str ? ' · ' . esc_html( $time_str ) : ''; ?></span>
            </div>
            <?php endif; ?>
            <?php if ( $location ) : ?>
            <div class="event-card__detail">
                <span class="event-card__detail-icon" aria-hidden="true">📍</span>
                <span><?php echo esc_html( $location ); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="card__footer">
            <a href="<?php echo esc_url( get_permalink( $event ) ); ?>" class="btn btn--sm btn--outline">
                <?php esc_html_e( 'Details', 'faithfulwitness' ); ?>
            </a>
            <?php if ( $reg_link ) : ?>
            <a href="<?php echo esc_url( $reg_link ); ?>" class="btn btn--sm btn--accent" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Register', 'faithfulwitness' ); ?> ↗
            </a>
            <?php endif; ?>
        </div>
    </div>

</article>
