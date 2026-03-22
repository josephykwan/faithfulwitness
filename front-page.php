<?php
/**
 * Front Page / Homepage
 *
 * Sections:
 * 1. Hero
 * 2. "We Choose" values strip
 * 3. Three Commitments
 * 4. Who Is This For (audience entry points)
 * 5. Network Map teaser
 * 6. Campaign Highlight
 * 7. Scripture Anchor (Acts 4:20)
 * 8. Partner Logos
 */
get_header();

/**
 * Get a homepage setting — prefers ACF Options when available, falls back to Customizer.
 *
 * @param string $acf_key      ACF field name on the options page.
 * @param string $mod_key      Customizer theme_mod key.
 * @param mixed  $default      Default value when neither is set.
 * @return mixed
 */
function fw_hp_option( $acf_key, $mod_key, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $val = get_field( $acf_key, 'option' );
        if ( $val !== null && $val !== '' && $val !== false ) {
            return $val;
        }
    }
    return get_theme_mod( $mod_key, $default );
}
?>

<!-- ============================================================
     1. HERO
     ============================================================ -->
<?php
$hero_image = fw_hp_option( 'fw_hero_bg', 'fw_hero_bg_url', 'https://faithfulwitness.us/wp-content/uploads/2026/03/Faithful-Witness-Campaign-Header-3.png' );
?>
<section class="hero">
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');"></div>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php echo esc_html( fw_hp_option( 'fw_hero_eyebrow', 'fw_hero_eyebrow', __( 'A Gospel-Centered Campaign', 'faithfulwitness' ) ) ); ?></span>
            <h1><?php echo esc_html( fw_hp_option( 'fw_hero_title', 'fw_hero_title', __( 'A Gospel-Centered Response for a Divided Time', 'faithfulwitness' ) ) ); ?></h1>
            <p><?php echo esc_html( fw_hp_option( 'fw_hero_subtitle', 'fw_hero_subtitle', __( 'Forming the Church for courageous, nonviolent, Gospel-rooted engagement around immigration.', 'faithfulwitness' ) ) ); ?></p>
            <div class="hero__actions">
                <a href="<?php echo esc_url( fw_hp_option( 'fw_hero_cta1_url', 'fw_hero_cta1_url', 'https://mailchi.mp/ccda/join-the-faithful-witness-campaign' ) ); ?>"
                   class="btn btn--accent btn--lg"
                   target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( fw_hp_option( 'fw_hero_cta1_label', 'fw_hero_cta1_label', __( 'Join the Campaign', 'faithfulwitness' ) ) ); ?>
                </a>
                <a href="<?php echo esc_url( fw_hp_option( 'fw_hero_cta2_url', 'fw_hero_cta2_url', home_url( '/network' ) ) ); ?>"
                   class="btn btn--outline-white btn--lg">
                    <?php echo esc_html( fw_hp_option( 'fw_hero_cta2_label', 'fw_hero_cta2_label', __( 'Find Your Network', 'faithfulwitness' ) ) ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     2. SCRIPTURE BAND — Acts 4:20 (moved up, after hero)
     ============================================================ -->
<section class="scripture-section" id="scripture">
    <div class="container">
        <div class="scripture-quote">
            <p class="scripture-quote__text">
                "<?php echo esc_html( fw_hp_option( 'fw_scripture_text', 'fw_scripture_text', __( 'As for us, we cannot help speaking about what we have seen and heard.', 'faithfulwitness' ) ) ); ?>"
            </p>
            <span class="scripture-quote__ref">
                — <?php echo esc_html( fw_hp_option( 'fw_scripture_ref', 'fw_scripture_ref', __( 'Acts 4:20', 'faithfulwitness' ) ) ); ?>
            </span>
        </div>
    </div>
</section>

<!-- ============================================================
     3. AUDIENCE — Where are you in this?
     ============================================================ -->
<section class="audience-section section section--alt" id="who-is-this-for">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Where are you in this?', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'There is a place for you here.', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'The Faithful Witness Campaign meets you wherever you are — whether you\'re still discerning, ready to act, or navigating this reality yourself.', 'faithfulwitness' ); ?></p>
        </div>

        <?php
        $audience_cards = [
            [
                'label'     => fw_hp_option( 'fw_audience1_label',     '', __( 'Still discerning', 'faithfulwitness' ) ),
                'title'     => fw_hp_option( 'fw_audience1_title',     '', __( 'Still forming your views?', 'faithfulwitness' ) ),
                'desc'      => fw_hp_option( 'fw_audience1_desc',      '', __( 'This campaign offers formation and space to learn, pray, and engage thoughtfully — without pressure.', 'faithfulwitness' ) ),
                'btn_label' => fw_hp_option( 'fw_audience1_btn_label', '', __( 'Start with resources →', 'faithfulwitness' ) ),
                'btn_url'   => fw_hp_option( 'fw_audience1_btn_url',   '', home_url( '/resources' ) ),
            ],
            [
                'label'     => fw_hp_option( 'fw_audience2_label',     '', __( 'Ready to act', 'faithfulwitness' ) ),
                'title'     => fw_hp_option( 'fw_audience2_title',     '', __( 'Your congregation is ready.', 'faithfulwitness' ) ),
                'desc'      => fw_hp_option( 'fw_audience2_desc',      '', __( 'Find a local movement, get equipped, and join congregations across the country taking faithful action.', 'faithfulwitness' ) ),
                'btn_label' => fw_hp_option( 'fw_audience2_btn_label', '', __( 'Find your local movement →', 'faithfulwitness' ) ),
                'btn_url'   => fw_hp_option( 'fw_audience2_btn_url',   '', home_url( '/network' ) ),
            ],
            [
                'label'     => fw_hp_option( 'fw_audience3_label',     '', __( 'Directly affected', 'faithfulwitness' ) ),
                'title'     => fw_hp_option( 'fw_audience3_title',     '', __( 'You are not alone.', 'faithfulwitness' ) ),
                'desc'      => fw_hp_option( 'fw_audience3_desc',      '', __( 'You are our neighbors, our brothers and sisters in Christ. Find legal, pastoral, and practical support near you.', 'faithfulwitness' ) ),
                'btn_label' => fw_hp_option( 'fw_audience3_btn_label', '', __( 'Find support near you →', 'faithfulwitness' ) ),
                'btn_url'   => fw_hp_option( 'fw_audience3_btn_url',   '', home_url( '/find-support' ) ),
            ],
        ];
        ?>
        <div class="audience-cards">
            <?php foreach ( $audience_cards as $ac ) : ?>
            <div class="audience-card">
                <div class="audience-card__label"><?php echo esc_html( $ac['label'] ); ?></div>
                <h3 class="audience-card__title"><?php echo esc_html( $ac['title'] ); ?></h3>
                <p class="audience-card__desc"><?php echo esc_html( $ac['desc'] ); ?></p>
                <a href="<?php echo esc_url( $ac['btn_url'] ); ?>" style="font-size:12px;color:var(--color-text);">
                    <?php echo esc_html( $ac['btn_label'] ); ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     4. RIGHT NOW — Featured initiative / moment card
     ============================================================ -->
<?php
$featured_initiative = get_posts( [
    'post_type'      => 'fw_initiative',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
$fi = ! empty( $featured_initiative ) ? $featured_initiative[0] : null;
$moment_headline = $fi ? get_the_title( $fi ) : fw_hp_option( 'fw_campaign_headline', 'fw_campaign_title', __( 'Pentecost Action — May 15–24', 'faithfulwitness' ) );
$moment_desc     = $fi ? wp_trim_words( get_the_excerpt( $fi ), 35, '…' ) : fw_hp_option( 'fw_campaign_description', 'fw_campaign_desc', __( 'We are called to wait, yet actively engage in prophetic witness that proclaims and demonstrates the goodness of God. Join congregations across the country during these 10 days.', 'faithfulwitness' ) );
$moment_url      = $fi ? get_permalink( $fi ) : home_url( '/initiatives' );
?>
<section class="section" id="right-now">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Right now', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'A moment that calls for faithful witness.', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'In a season marked by fear, polarization, and deep uncertainty around immigration, many Christians are asking: How do we follow Jesus faithfully when the stakes feel personal and complex?', 'faithfulwitness' ); ?></p>
        </div>
        <div class="moment-card">
            <span class="moment-card__label"><?php esc_html_e( 'Current initiative', 'faithfulwitness' ); ?></span>
            <h3><?php echo esc_html( $moment_headline ); ?></h3>
            <p><?php echo esc_html( $moment_desc ); ?></p>
            <a href="<?php echo esc_url( $moment_url ); ?>" style="font-size:12px;color:var(--color-accent-dark);">
                <?php esc_html_e( 'Learn how to participate →', 'faithfulwitness' ); ?>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     5. THREE COMMITMENTS — how we show up
     ============================================================ -->

<!-- ============================================================
     5. THREE COMMITMENTS — how we show up
     ============================================================ -->
<section class="commitments-section section section--alt" id="commitments">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Our three commitments', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'How we show up together.', 'faithfulwitness' ); ?></h2>
        </div>

        <div class="commitment-cards">

            <?php
            $commitments = [
                [
                    'num'   => '01',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
                    'title' => fw_hp_option( 'fw_commit1_title', '', __( 'Local Grassroots Movements & Formation', 'faithfulwitness' ) ),
                    'desc'  => fw_hp_option( 'fw_commit1_desc',  '', __( 'Cultivating faithful leadership in local communities — rooting people in Gospel values before, during, and after action.', 'faithfulwitness' ) ),
                    'url'   => fw_hp_option( 'fw_commit1_url',   '', home_url( '/spiritual-formation' ) ),
                ],
                [
                    'num'   => '02',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
                    'title' => fw_hp_option( 'fw_commit2_title', '', __( 'Presence & Accompaniment', 'faithfulwitness' ) ),
                    'desc'  => fw_hp_option( 'fw_commit2_desc',  '', __( 'Walking with those navigating fear and uncertainty — court accompaniment, Know Your Rights trainings, food and transportation assistance, and pastoral care.', 'faithfulwitness' ) ),
                    'url'   => fw_hp_option( 'fw_commit2_url',   '', home_url( '/know-your-rights' ) ),
                ],
                [
                    'num'   => '03',
                    'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28"><polyline points="22 8 22 16"/><path d="M2 12v-2a2 2 0 0 1 2-2h10l6-4v16l-6-4H4a2 2 0 0 1-2-2v-2z"/></svg>',
                    'title' => fw_hp_option( 'fw_commit3_title', '', __( 'Public Witness in the Public Square', 'faithfulwitness' ) ),
                    'desc'  => fw_hp_option( 'fw_commit3_desc',  '', __( 'Speaking with one moral voice on policy that upholds human dignity, due process, humanitarian protections, and nonviolence.', 'faithfulwitness' ) ),
                    'url'   => fw_hp_option( 'fw_commit3_url',   '', home_url( '/take-action' ) ),
                ],
            ];
            foreach ( $commitments as $c ) : ?>
            <article class="commitment-card">
                <div class="commitment-card__icon" aria-hidden="true"><?php echo $c['icon']; // pre-sanitized SVG ?></div>
                <span class="commitment-card__number"><?php echo esc_html( $c['num'] ); ?></span>
                <h3 class="commitment-card__title"><?php echo esc_html( $c['title'] ); ?></h3>
                <p class="commitment-card__desc"><?php echo esc_html( $c['desc'] ); ?></p>
                <a href="<?php echo esc_url( $c['url'] ); ?>" class="commitment-card__link">
                    <?php esc_html_e( 'Learn more', 'faithfulwitness' ); ?> →
                </a>
            </article>
            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- ============================================================
     6. WE CHOOSE — Values Grid (2 × 2 boxes)
     ============================================================ -->
<section class="values-strip section" id="values">
    <div class="container">
        <p class="values-strip__heading"><?php esc_html_e( 'We choose', 'faithfulwitness' ); ?></p>
        <h2 style="margin-bottom:var(--space-4);"><?php esc_html_e( 'Something different is possible.', 'faithfulwitness' ); ?></h2>
        <div class="values-grid">
            <?php
            $values = [
                [
                    'label' => fw_hp_option( 'fw_value1_over', '', __( 'Instead of despair —', 'faithfulwitness' ) ),
                    'word'  => fw_hp_option( 'fw_value1_word', '', __( 'Hope', 'faithfulwitness' ) ),
                ],
                [
                    'label' => fw_hp_option( 'fw_value2_over', '', __( 'Instead of silence —', 'faithfulwitness' ) ),
                    'word'  => fw_hp_option( 'fw_value2_word', '', __( 'Courage', 'faithfulwitness' ) ),
                ],
                [
                    'label' => fw_hp_option( 'fw_value3_over', '', __( 'Instead of fear —', 'faithfulwitness' ) ),
                    'word'  => fw_hp_option( 'fw_value3_word', '', __( 'Nonviolence', 'faithfulwitness' ) ),
                ],
                [
                    'label' => fw_hp_option( 'fw_value4_over', '', __( 'Instead of division —', 'faithfulwitness' ) ),
                    'word'  => fw_hp_option( 'fw_value4_word', '', __( 'Love', 'faithfulwitness' ) ),
                ],
            ];
            foreach ( $values as $v ) : ?>
            <div class="value-card">
                <span class="value-card__label"><?php echo esc_html( $v['label'] ); ?></span>
                <span class="value-card__word"><?php echo esc_html( $v['word'] ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     7. RESOURCE PREVIEW
     ============================================================ -->
<section class="section section--alt" id="resource-preview">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Resources', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Everything your congregation needs.', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'From Know Your Rights guides to sermon outlines to pastoral care manuals. All resources are free.', 'faithfulwitness' ); ?></p>
        </div>
        <div class="feat-strip">
            <p class="feat-strip__eyebrow"><?php esc_html_e( 'Start here — curated for this moment', 'faithfulwitness' ); ?></p>
            <div class="feat-strip__grid">
                <?php
                $featured_resources = get_posts( [
                    'post_type'      => 'fw_resource',
                    'post_status'    => 'publish',
                    'posts_per_page' => 4,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ] );
                if ( ! empty( $featured_resources ) ) :
                    foreach ( $featured_resources as $fr ) :
                        $types  = wp_get_post_terms( $fr->ID, 'fw_resource_type', [ 'fields' => 'names' ] );
                        $r_url  = get_post_meta( $fr->ID, 'fw_resource_url', true );
                        $link   = $r_url ?: get_permalink( $fr );
                        $f_type = ! empty( $types ) && ! is_wp_error( $types ) ? $types[0] : __( 'Resource', 'faithfulwitness' );
                ?>
                <a href="<?php echo esc_url( $link ); ?>" class="feat-card" <?php echo $r_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <span class="feat-card__type"><?php echo esc_html( $f_type ); ?></span>
                    <h4><?php echo esc_html( get_the_title( $fr ) ); ?></h4>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $fr ), 12, '…' ) ); ?></p>
                    <span class="feat-card__link"><?php esc_html_e( 'Read online →', 'faithfulwitness' ); ?></span>
                </a>
                <?php endforeach; else : ?>
                <div class="feat-card">
                    <span class="feat-card__type"><?php esc_html_e( 'Tool · Know Your Rights', 'faithfulwitness' ); ?></span>
                    <h4><?php esc_html_e( 'Know Your Rights: Congregation Guide', 'faithfulwitness' ); ?></h4>
                    <p><?php esc_html_e( 'Essential legal info for churches supporting immigrant neighbors.', 'faithfulwitness' ); ?></p>
                    <span class="feat-card__link"><?php esc_html_e( 'Read online →', 'faithfulwitness' ); ?></span>
                </div>
                <div class="feat-card">
                    <span class="feat-card__type"><?php esc_html_e( 'Toolkit · Court Accompaniment', 'faithfulwitness' ); ?></span>
                    <h4><?php esc_html_e( 'Court Accompaniment Checklist', 'faithfulwitness' ); ?></h4>
                    <p><?php esc_html_e( 'Step-by-step prep for accompanying families to hearings.', 'faithfulwitness' ); ?></p>
                    <span class="feat-card__link"><?php esc_html_e( 'Read online →', 'faithfulwitness' ); ?></span>
                </div>
                <div class="feat-card">
                    <span class="feat-card__type"><?php esc_html_e( 'Prayer Guide · Formation', 'faithfulwitness' ); ?></span>
                    <h4><?php esc_html_e( 'Prayer for Uncertain Times', 'faithfulwitness' ); ?></h4>
                    <p><?php esc_html_e( 'Liturgy and practices for congregations navigating fear.', 'faithfulwitness' ); ?></p>
                    <span class="feat-card__link"><?php esc_html_e( 'Read online →', 'faithfulwitness' ); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <a href="<?php echo esc_url( home_url( '/resources' ) ); ?>" class="btn btn--primary btn--sm">
            <?php esc_html_e( 'Explore all resources →', 'faithfulwitness' ); ?>
        </a>
    </div>
</section>

<!-- ============================================================
     8. MAILCHIMP SIGNUP — Stay Connected
     ============================================================ -->
<?php fw_render_mailchimp_signup_section(); ?>

<!-- ============================================================
     9. NETWORK MAP TEASER
     ============================================================ -->
<section class="map-teaser-section" id="network">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Local Network', 'faithfulwitness' ); ?></span>
                <h2><?php esc_html_e( 'Find your local network', 'faithfulwitness' ); ?></h2>
                <p style="color:var(--color-text-light);font-size:var(--text-lg);max-width:560px;">
                    <?php esc_html_e( 'Faithful witnesses are organizing in communities across the country. Find a group near you.', 'faithfulwitness' ); ?>
                </p>
            </div>
            <a href="<?php echo esc_url( home_url( '/network' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Browse all local groups →', 'faithfulwitness' ); ?>
            </a>
        </div>

        <div class="map-embed-placeholder">
            <?php
            $map_embed = get_theme_mod( 'fw_homepage_map_embed', '' );
            if ( $map_embed ) :
                echo wp_kses( $map_embed, [ 'iframe' => [ 'src' => [], 'width' => [], 'height' => [], 'allowfullscreen' => [], 'loading' => [], 'referrerpolicy' => [] ] ] );
            else : ?>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d13166019.636825098!2d-95.71289!3d37.09024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sus!4v1710000000000!5m2!1sen!2sus"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="<?php esc_attr_e( 'Faithful Witness organizing groups map', 'faithfulwitness' ); ?>">
            </iframe>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     7B. UPCOMING EVENTS WIDGET (mini)
     ============================================================ -->
<?php
$next_events = get_posts( [
    'post_type'      => 'fw_event',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
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

if ( ! empty( $next_events ) ) : ?>
<section class="events-widget-section section section--alt" id="upcoming-events">
    <div class="container">
        <div class="section-header" style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:1.5rem;margin-bottom:var(--space-8);">
            <div>
                <span class="eyebrow"><?php esc_html_e( 'Get Involved', 'faithfulwitness' ); ?></span>
                <h2><?php esc_html_e( 'Upcoming Events', 'faithfulwitness' ); ?></h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/events' ) ); ?>" class="btn btn--outline">
                <?php esc_html_e( 'View all events →', 'faithfulwitness' ); ?>
            </a>
        </div>
        <div class="events-widget-list">
            <?php foreach ( $next_events as $ev ) :
                $ev_date    = get_post_meta( $ev->ID, 'fw_event_date', true );
                $ev_ts      = $ev_date ? strtotime( $ev_date ) : null;
                $ev_virtual = get_post_meta( $ev->ID, 'fw_event_virtual', true );
                $ev_city    = get_post_meta( $ev->ID, 'fw_event_city', true );
                $ev_state   = get_post_meta( $ev->ID, 'fw_event_state', true );
                $ev_reg     = get_post_meta( $ev->ID, 'fw_event_registration_link', true );
                $ev_cats    = wp_get_post_terms( $ev->ID, 'fw_event_category', [ 'fields' => 'names' ] );
            ?>
            <div class="events-widget-item">
                <div class="events-widget-item__date">
                    <?php if ( $ev_ts ) : ?>
                    <span class="events-widget-item__month"><?php echo esc_html( date_i18n( 'M', $ev_ts ) ); ?></span>
                    <span class="events-widget-item__day"><?php echo esc_html( date_i18n( 'j', $ev_ts ) ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="events-widget-item__body">
                    <?php if ( ! empty( $ev_cats ) && ! is_wp_error( $ev_cats ) ) : ?>
                    <span class="tag tag--primary" style="font-size:11px;"><?php echo esc_html( $ev_cats[0] ); ?></span>
                    <?php endif; ?>
                    <strong class="events-widget-item__title"><?php echo esc_html( get_the_title( $ev ) ); ?></strong>
                    <span class="events-widget-item__loc">
                        <?php if ( $ev_virtual === '1' ) : ?>
                        <?php esc_html_e( 'Virtual', 'faithfulwitness' ); ?>
                        <?php elseif ( $ev_city ) : ?>
                        <?php echo esc_html( implode( ', ', array_filter( [ $ev_city, $ev_state ] ) ) ); ?>
                        <?php endif; ?>
                    </span>
                </div>
                <a href="<?php echo esc_url( $ev_reg ?: get_permalink( $ev ) ); ?>"
                   class="events-widget-item__cta btn btn--sm btn--primary"
                   <?php echo $ev_reg ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <?php esc_html_e( 'Register →', 'faithfulwitness' ); ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ============================================================
     10. PARTNER LOGOS
     ============================================================ -->
<section class="partners-section" id="partners">
    <div class="container">
        <p class="partners-label"><?php esc_html_e( 'In partnership with:', 'faithfulwitness' ); ?></p>
        <div class="partner-logos">
            <?php
            // Priority: 1) ACF Pro repeater (fw_partners), 2) individual ACF option fields, 3) Customizer mods.
            $acf_partners = function_exists( 'get_field' ) ? get_field( 'fw_partners', 'option' ) : null;
            if ( ! empty( $acf_partners ) && is_array( $acf_partners ) ) {
                $partners = array_map( function( $row ) {
                    return [
                        'name' => $row['fw_partner_name'] ?? '',
                        'url'  => $row['fw_partner_url']  ?? '',
                        'logo' => $row['fw_partner_logo'] ?? '',
                    ];
                }, $acf_partners );
            } elseif ( function_exists( 'get_field' ) && get_field( 'fw_partner1_name', 'option' ) ) {
                $partners = [];
                for ( $i = 1; $i <= 4; $i++ ) {
                    $name = get_field( "fw_partner{$i}_name", 'option' );
                    if ( ! $name ) continue;
                    $partners[] = [
                        'name' => $name,
                        'url'  => get_field( "fw_partner{$i}_url", 'option' ) ?: '',
                        'logo' => get_field( "fw_partner{$i}_logo_url", 'option' ) ?: '',
                    ];
                }
            } else {
                $partners = [
                    [
                        'name' => 'NaLEC',
                        'url'  => 'https://nalec.org',
                        'logo' => get_theme_mod( 'fw_partner_nalec_logo', '' ),
                    ],
                    [
                        'name' => 'CCDA',
                        'url'  => 'https://ccda.org',
                        'logo' => get_theme_mod( 'fw_partner_ccda_logo', '' ),
                    ],
                    [
                        'name' => 'World Relief',
                        'url'  => 'https://worldrelief.org',
                        'logo' => get_theme_mod( 'fw_partner_worldrelief_logo', '' ),
                    ],
                    [
                        'name' => 'Undivided',
                        'url'  => 'https://undivided.us',
                        'logo' => get_theme_mod( 'fw_partner_undivided_logo', '' ),
                    ],
                ];
            }
            foreach ( $partners as $partner ) : ?>
            <a href="<?php echo esc_url( $partner['url'] ); ?>"
               class="partner-logo-link"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="<?php echo esc_attr( $partner['name'] ); ?>">
                <?php if ( $partner['logo'] ) : ?>
                <img src="<?php echo esc_url( $partner['logo'] ); ?>"
                     alt="<?php echo esc_attr( $partner['name'] ); ?>"
                     loading="lazy">
                <?php else : ?>
                <?php echo esc_html( $partner['name'] ); ?>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer();
