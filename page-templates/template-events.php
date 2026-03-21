<?php
/**
 * Template Name: Events Calendar
 *
 * Calendar view (monthly grid) + List view toggle.
 * Event type filter using fw_event_category taxonomy.
 * Mini upcoming-events widget is also embedded on the homepage.
 */

get_header();

// ── Month navigation ──────────────────────────────────────────────────────
$year  = isset( $_GET['year'] )  ? (int) $_GET['year']  : (int) gmdate( 'Y' );
$month = isset( $_GET['month'] ) ? (int) $_GET['month'] : (int) gmdate( 'm' );
$year  = max( 2020, min( 2035, $year ) );
$month = max( 1,    min( 12,   $month ) );

$first_ts      = mktime( 0, 0, 0, $month, 1, $year );
$days_in_month = (int) gmdate( 't', $first_ts );
$first_weekday = (int) gmdate( 'w', $first_ts ); // 0=Sunday
$month_label   = date_i18n( 'F Y', $first_ts );

$base_url  = get_permalink();
$prev_month = $month === 1 ? 12 : $month - 1;
$prev_year  = $month === 1 ? $year - 1 : $year;
$next_month = $month === 12 ? 1 : $month + 1;
$next_year  = $month === 12 ? $year + 1 : $year;
$prev_url   = add_query_arg( [ 'year' => $prev_year, 'month' => $prev_month ], $base_url );
$next_url   = add_query_arg( [ 'year' => $next_year, 'month' => $next_month ], $base_url );

// ── Fetch all upcoming events (for list view) ─────────────────────────────
$all_upcoming = get_posts( [
    'post_type'      => 'fw_event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'fw_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [ [
        'key'     => 'fw_event_date',
        'value'   => gmdate( 'Y-m-d' ),
        'compare' => '>=',
        'type'    => 'DATE',
    ] ],
] );

// ── Fetch month events (for calendar view) ────────────────────────────────
$month_start = sprintf( '%04d-%02d-01', $year, $month );
$month_end   = sprintf( '%04d-%02d-%02d', $year, $month, $days_in_month );
$month_events_raw = get_posts( [
    'post_type'      => 'fw_event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'fw_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [ [
        'key'     => 'fw_event_date',
        'value'   => [ $month_start, $month_end ],
        'compare' => 'BETWEEN',
        'type'    => 'DATE',
    ] ],
] );

$events_by_day = [];
foreach ( $month_events_raw as $ev ) {
    $d = (int) date_i18n( 'j', strtotime( get_post_meta( $ev->ID, 'fw_event_date', true ) ) );
    $events_by_day[ $d ][] = $ev;
}

$today_day = ( $year === (int) gmdate( 'Y' ) && $month === (int) gmdate( 'm' ) ) ? (int) gmdate( 'j' ) : -1;
?>

<!-- Hero -->
<section class="events-hero" id="events-top">
    <div class="container">
        <span class="hero__eyebrow" style="color:var(--color-accent-light);"><?php esc_html_e( 'Community & Training', 'faithfulwitness' ); ?></span>
        <h1><?php esc_html_e( 'Upcoming Events & Gatherings', 'faithfulwitness' ); ?></h1>
        <p><?php esc_html_e( 'Prayer gatherings, Know Your Rights trainings, court accompaniment, and more — in-person and virtual.', 'faithfulwitness' ); ?></p>
    </div>
</section>

<!-- Controls: Filter + View Toggle -->
<div class="events-controls-bar">
    <div class="container">
        <div class="events-controls">

            <div class="events-filter-group" role="group" aria-label="<?php esc_attr_e( 'Filter by event type', 'faithfulwitness' ); ?>">
                <button class="filter-pill active" data-event-type="" aria-pressed="true">
                    <?php esc_html_e( 'All Events', 'faithfulwitness' ); ?>
                </button>
                <?php
                $event_cats = get_terms( [ 'taxonomy' => 'fw_event_category', 'hide_empty' => false ] );
                if ( $event_cats && ! is_wp_error( $event_cats ) ) :
                    foreach ( $event_cats as $cat ) : ?>
                <button class="filter-pill" data-event-type="<?php echo esc_attr( $cat->slug ); ?>" aria-pressed="false">
                    <?php echo esc_html( $cat->name ); ?>
                </button>
                    <?php endforeach;
                endif; ?>
            </div>

            <div class="events-view-toggle" role="group" aria-label="<?php esc_attr_e( 'Toggle calendar or list view', 'faithfulwitness' ); ?>">
                <button id="btn-calendar-view" class="events-view-btn events-view-btn--active" aria-pressed="true">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?php esc_html_e( 'Calendar', 'faithfulwitness' ); ?>
                </button>
                <button id="btn-list-view" class="events-view-btn" aria-pressed="false">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                    <?php esc_html_e( 'List', 'faithfulwitness' ); ?>
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ================================================================
     CALENDAR VIEW
     ================================================================ -->
<section class="events-calendar-section section" id="events-calendar-view">
    <div class="container">

        <div class="calendar-nav">
            <a href="<?php echo esc_url( $prev_url ); ?>" class="calendar-nav__btn">← <?php esc_html_e( 'Prev', 'faithfulwitness' ); ?></a>
            <h2 class="calendar-nav__month"><?php echo esc_html( $month_label ); ?></h2>
            <a href="<?php echo esc_url( $next_url ); ?>" class="calendar-nav__btn"><?php esc_html_e( 'Next', 'faithfulwitness' ); ?> →</a>
        </div>

        <div class="calendar-grid" role="grid">
            <?php foreach ( [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ] as $dname ) : ?>
            <div class="calendar-header-cell" role="columnheader"><?php echo esc_html( $dname ); ?></div>
            <?php endforeach; ?>

            <?php for ( $i = 0; $i < $first_weekday; $i++ ) : ?>
            <div class="calendar-day calendar-day--empty" role="gridcell"></div>
            <?php endfor; ?>

            <?php for ( $day = 1; $day <= $days_in_month; $day++ ) :
                $is_today   = ( $day === $today_day );
                $has_events = isset( $events_by_day[ $day ] );
                $cls  = 'calendar-day';
                if ( $is_today )   $cls .= ' calendar-day--today';
                if ( $has_events ) $cls .= ' calendar-day--has-events';
            ?>
            <div class="<?php echo esc_attr( $cls ); ?>" role="gridcell">
                <span class="calendar-day__num"><?php echo esc_html( $day ); ?></span>
                <?php if ( $has_events ) : ?>
                <div class="calendar-day__events">
                    <?php foreach ( $events_by_day[ $day ] as $ev ) :
                        $ev_cats = wp_get_post_terms( $ev->ID, 'fw_event_category', [ 'fields' => 'slugs' ] );
                        $cat_str = is_array( $ev_cats ) ? implode( ',', $ev_cats ) : '';
                    ?>
                    <a href="<?php echo esc_url( get_permalink( $ev ) ); ?>"
                       class="calendar-event-dot"
                       data-categories="<?php echo esc_attr( $cat_str ); ?>"
                       title="<?php echo esc_attr( get_the_title( $ev ) ); ?>">
                        <span class="sr-only"><?php echo esc_html( get_the_title( $ev ) ); ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endfor; ?>
        </div>

        <?php if ( empty( $month_events_raw ) ) : ?>
        <p class="calendar-empty-note">
            <?php esc_html_e( 'No events this month. Use the arrows to navigate, or switch to List view for all upcoming events.', 'faithfulwitness' ); ?>
        </p>
        <?php endif; ?>

    </div>
</section>

<!-- ================================================================
     LIST VIEW
     ================================================================ -->
<section class="events-list-section section" id="events-list-view" style="display:none;">
    <div class="container">

        <?php if ( ! empty( $all_upcoming ) ) :
            foreach ( $all_upcoming as $ev ) :
                $ev_date     = get_post_meta( $ev->ID, 'fw_event_date', true );
                $ev_time     = get_post_meta( $ev->ID, 'fw_event_time', true );
                $ev_end_time = get_post_meta( $ev->ID, 'fw_event_end_time', true );
                $ev_virtual  = get_post_meta( $ev->ID, 'fw_event_virtual', true );
                $ev_location = get_post_meta( $ev->ID, 'fw_event_location_name', true );
                $ev_city     = get_post_meta( $ev->ID, 'fw_event_city', true );
                $ev_state    = get_post_meta( $ev->ID, 'fw_event_state', true );
                $ev_reg_link = get_post_meta( $ev->ID, 'fw_event_registration_link', true );
                $ev_cats     = wp_get_post_terms( $ev->ID, 'fw_event_category' );
                $cat_slugs   = is_array( $ev_cats ) && ! is_wp_error( $ev_cats ) ? wp_list_pluck( $ev_cats, 'slug' ) : [];
                $cat_names   = is_array( $ev_cats ) && ! is_wp_error( $ev_cats ) ? wp_list_pluck( $ev_cats, 'name' ) : [];
                $ev_ts       = $ev_date ? strtotime( $ev_date ) : null;
        ?>
        <article class="event-card" data-categories="<?php echo esc_attr( implode( ',', $cat_slugs ) ); ?>">

            <div class="event-card__date-badge" aria-hidden="true">
                <?php if ( $ev_ts ) : ?>
                <span class="event-card__month"><?php echo esc_html( date_i18n( 'M', $ev_ts ) ); ?></span>
                <span class="event-card__day"><?php echo esc_html( date_i18n( 'j', $ev_ts ) ); ?></span>
                <?php endif; ?>
            </div>

            <div class="event-card__body">
                <?php if ( ! empty( $cat_names ) ) : ?>
                <div class="event-card__tags">
                    <?php foreach ( $cat_names as $cname ) : ?>
                    <span class="tag tag--primary"><?php echo esc_html( $cname ); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <h3 class="event-card__title">
                    <a href="<?php echo esc_url( get_permalink( $ev ) ); ?>"><?php echo esc_html( get_the_title( $ev ) ); ?></a>
                </h3>

                <div class="event-card__meta">
                    <?php if ( $ev_ts ) : ?>
                    <span class="event-card__meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <time datetime="<?php echo esc_attr( $ev_date ); ?>"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $ev_ts ) ); ?></time>
                        <?php if ( $ev_time ) : ?>
                        · <?php echo esc_html( date_i18n( get_option( 'time_format' ), strtotime( $ev_date . ' ' . $ev_time ) ) ); ?><?php if ( $ev_end_time ) echo ' – ' . esc_html( date_i18n( get_option( 'time_format' ), strtotime( $ev_date . ' ' . $ev_end_time ) ) ); ?>
                        <?php endif; ?>
                    </span>
                    <?php endif; ?>
                    <span class="event-card__meta-item">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php if ( $ev_virtual === '1' ) : ?>
                        <?php esc_html_e( 'Virtual', 'faithfulwitness' ); ?>
                        <?php else : ?>
                        <?php
                        $loc_parts = array_filter( [ $ev_location, $ev_city, $ev_state ] );
                        echo esc_html( implode( ', ', $loc_parts ) ?: __( 'Location TBD', 'faithfulwitness' ) );
                        ?>
                        <?php endif; ?>
                    </span>
                </div>

                <?php if ( $desc = get_the_excerpt( $ev ) ) : ?>
                <p class="event-card__excerpt"><?php echo esc_html( wp_trim_words( $desc, 20, '…' ) ); ?></p>
                <?php endif; ?>
            </div>

            <div class="event-card__actions">
                <?php if ( $ev_reg_link ) : ?>
                <a href="<?php echo esc_url( $ev_reg_link ); ?>" class="btn btn--primary btn--sm" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e( 'Register / RSVP →', 'faithfulwitness' ); ?>
                </a>
                <?php else : ?>
                <a href="<?php echo esc_url( get_permalink( $ev ) ); ?>" class="btn btn--outline btn--sm">
                    <?php esc_html_e( 'Learn More →', 'faithfulwitness' ); ?>
                </a>
                <?php endif; ?>
            </div>

        </article>
        <?php
            endforeach;
        else : ?>
        <div class="events-empty">
            <p><?php esc_html_e( 'No upcoming events at this time. Check back soon, or join the campaign to receive event announcements.', 'faithfulwitness' ); ?></p>
            <a href="https://mailchi.mp/ccda/join-the-faithful-witness-campaign" class="btn btn--primary" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Join the Campaign', 'faithfulwitness' ); ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php if ( $content = apply_filters( 'the_content', get_the_content() ) ) : ?>
<section class="section section--alt">
    <div class="container container--narrow">
        <div class="entry-content"><?php echo $content; // phpcs:ignore ?></div>
    </div>
</section>
<?php endif; ?>

<script>
( function () {
    var btnCal   = document.getElementById( 'btn-calendar-view' );
    var btnList  = document.getElementById( 'btn-list-view' );
    var calView  = document.getElementById( 'events-calendar-view' );
    var listView = document.getElementById( 'events-list-view' );
    var filterBtns = document.querySelectorAll( '.events-filter-group .filter-pill' );
    var activeType = '';

    function showCalendar() {
        calView.style.display  = '';
        listView.style.display = 'none';
        btnCal.classList.add( 'events-view-btn--active' );
        btnList.classList.remove( 'events-view-btn--active' );
        btnCal.setAttribute( 'aria-pressed', 'true' );
        btnList.setAttribute( 'aria-pressed', 'false' );
        applyFilter();
    }

    function showList() {
        calView.style.display  = 'none';
        listView.style.display = '';
        btnCal.classList.remove( 'events-view-btn--active' );
        btnList.classList.add( 'events-view-btn--active' );
        btnCal.setAttribute( 'aria-pressed', 'false' );
        btnList.setAttribute( 'aria-pressed', 'true' );
        applyFilter();
    }

    function applyFilter() {
        // Filter list-view cards
        document.querySelectorAll( '.event-card' ).forEach( function ( card ) {
            var cats = ( card.dataset.categories || '' ).split( ',' ).map( function (s) { return s.trim(); } );
            card.style.display = ( ! activeType || cats.includes( activeType ) ) ? '' : 'none';
        } );
        // Dim calendar dots
        document.querySelectorAll( '.calendar-event-dot' ).forEach( function ( dot ) {
            var cats = ( dot.dataset.categories || '' ).split( ',' ).map( function (s) { return s.trim(); } );
            dot.style.opacity = ( ! activeType || cats.includes( activeType ) ) ? '1' : '0.2';
        } );
    }

    if ( btnCal )  btnCal.addEventListener(  'click', showCalendar );
    if ( btnList ) btnList.addEventListener( 'click', showList );

    filterBtns.forEach( function ( btn ) {
        btn.addEventListener( 'click', function () {
            activeType = btn.dataset.eventType || '';
            filterBtns.forEach( function (b) {
                var match = ( b.dataset.eventType || '' ) === activeType;
                b.classList.toggle( 'active', match );
                b.setAttribute( 'aria-pressed', match ? 'true' : 'false' );
            } );
            applyFilter();
        } );
    } );
} )();
</script>

<?php get_footer();
