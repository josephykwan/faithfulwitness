<?php
/**
 * Template Name: Events Page
 *
 * Displays upcoming events with tabs for National vs Local,
 * grouped by month, with optional state filter.
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
            <span class="hero__eyebrow"><?php esc_html_e( 'Join the Movement', 'faithfulwitness' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <?php if ( $excerpt = get_the_excerpt() ) : ?>
            <p><?php echo esc_html( $excerpt ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
// Pull all upcoming events
$all_events = get_posts( [
    'post_type'      => 'fw_event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'fw_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
] );

// Split upcoming vs past
$upcoming_events = [];
$past_events     = [];
$today           = strtotime( 'today' );

foreach ( $all_events as $ev ) {
    $date = get_post_meta( $ev->ID, 'fw_event_date', true );
    if ( ! $date || strtotime( $date ) >= $today ) {
        $upcoming_events[] = $ev;
    } else {
        $past_events[] = $ev;
    }
}

// Split by scope
$national_events = array_filter( $upcoming_events, fn( $ev ) => get_post_meta( $ev->ID, 'fw_event_scope', true ) !== 'local' );
$local_events    = array_filter( $upcoming_events, fn( $ev ) => get_post_meta( $ev->ID, 'fw_event_scope', true ) === 'local' );
?>

<section class="section events-section" id="events">
    <div class="container">

        <!-- Tabs -->
        <div class="events-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Event type', 'faithfulwitness' ); ?>">
            <button class="events-tab active" id="tab-all" role="tab" aria-selected="true" aria-controls="panel-all" data-tab="all">
                <?php esc_html_e( 'All Events', 'faithfulwitness' ); ?>
                <span class="events-tab__count"><?php echo esc_html( count( $upcoming_events ) ); ?></span>
            </button>
            <button class="events-tab" id="tab-national" role="tab" aria-selected="false" aria-controls="panel-national" data-tab="national">
                <?php esc_html_e( 'National', 'faithfulwitness' ); ?>
                <span class="events-tab__count"><?php echo esc_html( count( $national_events ) ); ?></span>
            </button>
            <button class="events-tab" id="tab-local" role="tab" aria-selected="false" aria-controls="panel-local" data-tab="local">
                <?php esc_html_e( 'Local', 'faithfulwitness' ); ?>
                <span class="events-tab__count"><?php echo esc_html( count( $local_events ) ); ?></span>
            </button>
        </div>

        <!-- State filter (affects local tab) -->
        <div class="events-filters" style="margin-bottom: var(--space-8);">
            <label for="events-state-filter" class="sr-only"><?php esc_html_e( 'Filter by state', 'faithfulwitness' ); ?></label>
            <select id="events-state-filter" class="form-control" style="max-width:200px;" aria-label="<?php esc_attr_e( 'Filter by state', 'faithfulwitness' ); ?>">
                <option value=""><?php esc_html_e( 'All states', 'faithfulwitness' ); ?></option>
                <?php
                $states = get_terms( [ 'taxonomy' => 'fw_state', 'hide_empty' => true ] );
                if ( $states && ! is_wp_error( $states ) ) :
                    foreach ( $states as $s ) :
                ?>
                    <option value="<?php echo esc_attr( $s->slug ); ?>"><?php echo esc_html( $s->name ); ?></option>
                <?php
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <?php if ( ! empty( $upcoming_events ) ) : ?>

            <!-- All events tab panel -->
            <div id="panel-all" role="tabpanel" aria-labelledby="tab-all" class="events-panel events-panel--active">
                <?php fw_render_events_by_month( $upcoming_events ); ?>
            </div>

            <!-- National events tab panel -->
            <div id="panel-national" role="tabpanel" aria-labelledby="tab-national" class="events-panel" hidden>
                <?php if ( ! empty( $national_events ) ) :
                    fw_render_events_by_month( $national_events );
                else : ?>
                    <p><?php esc_html_e( 'No upcoming national events.', 'faithfulwitness' ); ?></p>
                <?php endif; ?>
            </div>

            <!-- Local events tab panel -->
            <div id="panel-local" role="tabpanel" aria-labelledby="tab-local" class="events-panel" hidden>
                <?php if ( ! empty( $local_events ) ) :
                    fw_render_events_by_month( $local_events );
                else : ?>
                    <p><?php esc_html_e( 'No upcoming local events. Check back soon!', 'faithfulwitness' ); ?></p>
                <?php endif; ?>
            </div>

        <?php else : ?>
            <div style="text-align:center; padding: var(--space-16) 0;">
                <p style="font-size: var(--text-xl); color: var(--color-text-muted);">
                    <?php esc_html_e( 'No upcoming events at this time. Check back soon!', 'faithfulwitness' ); ?>
                </p>
            </div>
        <?php endif; ?>

    </div><!-- .container -->
</section>

<!-- Past Events -->
<?php if ( ! empty( $past_events ) ) : ?>
<section class="section section--alt" id="past-events">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Archive', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Past Events', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ( array_slice( array_reverse( $past_events ), 0, 6 ) as $ev ) :
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $ev ] );
            endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>

<?php
/**
 * Helper: render events grouped by month.
 *
 * @param WP_Post[] $events
 */
function fw_render_events_by_month( array $events ) {
    $by_month = [];
    foreach ( $events as $ev ) {
        $date  = get_post_meta( $ev->ID, 'fw_event_date', true );
        $month = $date ? date_i18n( 'F Y', strtotime( $date ) ) : __( 'Date TBD', 'faithfulwitness' );
        $by_month[ $month ][] = $ev;
    }

    foreach ( $by_month as $month_label => $month_events ) : ?>
    <div class="events-month-group event-group" data-month="<?php echo esc_attr( $month_label ); ?>">
        <h3 class="events-month-label"><?php echo esc_html( $month_label ); ?></h3>
        <div class="grid grid--3">
            <?php foreach ( $month_events as $ev ) :
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $ev ] );
            endforeach; ?>
        </div>
    </div>
    <?php endforeach;
}
