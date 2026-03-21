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
get_header(); ?>

<!-- ============================================================
     1. HERO
     ============================================================ -->
<?php
$hero_image = get_theme_mod(
    'fw_hero_bg_url',
    'https://faithfulwitness.us/wp-content/uploads/2026/03/Faithful-Witness-Campaign-Header-3.png'
);
?>
<section class="hero">
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( $hero_image ); ?>');"></div>
    <div class="hero__overlay"></div>
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php echo esc_html( get_theme_mod( 'fw_hero_eyebrow', __( 'A Gospel-Centered Campaign', 'faithfulwitness' ) ) ); ?></span>
            <h1><?php echo esc_html( get_theme_mod( 'fw_hero_title', __( 'A Gospel-Centered Response for a Divided Time', 'faithfulwitness' ) ) ); ?></h1>
            <p><?php echo esc_html( get_theme_mod( 'fw_hero_subtitle', __( 'Forming the Church for courageous, nonviolent, Gospel-rooted engagement around immigration.', 'faithfulwitness' ) ) ); ?></p>
            <div class="hero__actions">
                <a href="<?php echo esc_url( get_theme_mod( 'fw_hero_cta1_url', 'https://mailchi.mp/ccda/join-the-faithful-witness-campaign' ) ); ?>"
                   class="btn btn--accent btn--lg"
                   target="_blank" rel="noopener noreferrer">
                    <?php echo esc_html( get_theme_mod( 'fw_hero_cta1_label', __( 'Join the Campaign', 'faithfulwitness' ) ) ); ?>
                </a>
                <a href="<?php echo esc_url( get_theme_mod( 'fw_hero_cta2_url', home_url( '/network' ) ) ); ?>"
                   class="btn btn--outline-white btn--lg">
                    <?php echo esc_html( get_theme_mod( 'fw_hero_cta2_label', __( 'Find Your Network', 'faithfulwitness' ) ) ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     2. WE CHOOSE — Values Strip
     ============================================================ -->
<section class="values-strip" id="values" aria-label="<?php esc_attr_e( 'Our values', 'faithfulwitness' ); ?>">
    <div class="container">
        <p class="values-strip__heading"><?php esc_html_e( 'We Choose', 'faithfulwitness' ); ?></p>
    </div>
    <div class="values-grid">
        <div class="value-card">
            <span class="value-card__word"><?php esc_html_e( 'Hope', 'faithfulwitness' ); ?></span>
            <span class="value-card__over"><?php esc_html_e( 'over despair', 'faithfulwitness' ); ?></span>
        </div>
        <div class="value-card">
            <span class="value-card__word"><?php esc_html_e( 'Courage', 'faithfulwitness' ); ?></span>
            <span class="value-card__over"><?php esc_html_e( 'over silence', 'faithfulwitness' ); ?></span>
        </div>
        <div class="value-card">
            <span class="value-card__word"><?php esc_html_e( 'Nonviolence', 'faithfulwitness' ); ?></span>
            <span class="value-card__over"><?php esc_html_e( 'over fear', 'faithfulwitness' ); ?></span>
        </div>
        <div class="value-card">
            <span class="value-card__word"><?php esc_html_e( 'Love', 'faithfulwitness' ); ?></span>
            <span class="value-card__over"><?php esc_html_e( 'over division', 'faithfulwitness' ); ?></span>
        </div>
    </div>
</section>

<!-- ============================================================
     3. THREE COMMITMENTS
     ============================================================ -->
<section class="commitments-section section" id="commitments">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="eyebrow"><?php esc_html_e( 'How We Show Up', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Three Commitments', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'Our organizing work is rooted in three interlocking practices — not just strategies, but postures of faith.', 'faithfulwitness' ); ?></p>
        </div>

        <div class="commitment-cards">

            <!-- Commitment 1: Formation -->
            <article class="commitment-card">
                <div class="commitment-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="commitment-card__number"><?php esc_html_e( '01', 'faithfulwitness' ); ?></span>
                <h3 class="commitment-card__title"><?php esc_html_e( 'Local Grassroots Movements & Formation', 'faithfulwitness' ); ?></h3>
                <p class="commitment-card__desc"><?php esc_html_e( 'Cultivating faithful leadership in local communities — rooting people in Gospel values before, during, and after action.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/spiritual-formation' ) ); ?>" class="commitment-card__link">
                    <?php esc_html_e( 'Learn more', 'faithfulwitness' ); ?> →
                </a>
            </article>

            <!-- Commitment 2: Accompaniment -->
            <article class="commitment-card">
                <div class="commitment-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </div>
                <span class="commitment-card__number"><?php esc_html_e( '02', 'faithfulwitness' ); ?></span>
                <h3 class="commitment-card__title"><?php esc_html_e( 'Presence & Accompaniment', 'faithfulwitness' ); ?></h3>
                <p class="commitment-card__desc"><?php esc_html_e( 'Walking with those navigating fear and uncertainty — court accompaniment, Know Your Rights trainings, food and transportation assistance, and pastoral care.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/know-your-rights' ) ); ?>" class="commitment-card__link">
                    <?php esc_html_e( 'Learn more', 'faithfulwitness' ); ?> →
                </a>
            </article>

            <!-- Commitment 3: Public Witness -->
            <article class="commitment-card">
                <div class="commitment-card__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" width="28" height="28">
                        <polyline points="22 8 22 16"/>
                        <path d="M2 12v-2a2 2 0 0 1 2-2h10l6-4v16l-6-4H4a2 2 0 0 1-2-2v-2z"/>
                    </svg>
                </div>
                <span class="commitment-card__number"><?php esc_html_e( '03', 'faithfulwitness' ); ?></span>
                <h3 class="commitment-card__title"><?php esc_html_e( 'Public Witness in the Public Square', 'faithfulwitness' ); ?></h3>
                <p class="commitment-card__desc"><?php esc_html_e( 'Speaking with one moral voice on policy that upholds human dignity, due process, humanitarian protections, and nonviolence.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/take-action' ) ); ?>" class="commitment-card__link">
                    <?php esc_html_e( 'Learn more', 'faithfulwitness' ); ?> →
                </a>
            </article>

        </div>
    </div>
</section>

<!-- ============================================================
     4. WHO IS THIS FOR — Audience Entry Points
     ============================================================ -->
<section class="audience-section section section--alt" id="who-is-this-for">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="eyebrow"><?php esc_html_e( 'Who Is This For', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Wherever You Are, You Belong Here', 'faithfulwitness' ); ?></h2>
        </div>

        <div class="audience-cards">

            <div class="audience-card">
                <span class="audience-card__label"><?php esc_html_e( 'Starting Point', 'faithfulwitness' ); ?></span>
                <h3 class="audience-card__title"><?php esc_html_e( 'For those who are uncertain', 'faithfulwitness' ); ?></h3>
                <p class="audience-card__desc"><?php esc_html_e( 'You sense something is wrong but aren\'t sure what faithful engagement looks like. You want to understand the issues through a Gospel lens before acting. This is a safe place to learn, ask hard questions, and be formed.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/resources' ) ); ?>" class="btn btn--outline" style="margin-top:auto;">
                    <?php esc_html_e( 'Start by learning', 'faithfulwitness' ); ?>
                </a>
            </div>

            <div class="audience-card" style="border-color: var(--color-primary);">
                <span class="audience-card__label"><?php esc_html_e( 'Ready to Organize', 'faithfulwitness' ); ?></span>
                <h3 class="audience-card__title"><?php esc_html_e( 'For those ready to engage', 'faithfulwitness' ); ?></h3>
                <p class="audience-card__desc"><?php esc_html_e( 'You\'re a church leader, pastor, or congregation ready to move from concern to action. You want practical tools, community support, and a network of faithful witnesses who will walk alongside you.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/network' ) ); ?>" class="btn btn--primary" style="margin-top:auto;">
                    <?php esc_html_e( 'Find your local group', 'faithfulwitness' ); ?>
                </a>
            </div>

            <div class="audience-card">
                <span class="audience-card__label"><?php esc_html_e( 'Direct Support', 'faithfulwitness' ); ?></span>
                <h3 class="audience-card__title"><?php esc_html_e( 'For those directly affected', 'faithfulwitness' ); ?></h3>
                <p class="audience-card__desc"><?php esc_html_e( 'You or someone you love is navigating the immigration system right now. You need practical help, legal information, and a community that will stand with you without judgment or fear.', 'faithfulwitness' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/know-your-rights' ) ); ?>" class="btn btn--outline" style="margin-top:auto;">
                    <?php esc_html_e( 'Connect with support', 'faithfulwitness' ); ?>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================
     5. NETWORK MAP TEASER
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
     6. CAMPAIGN HIGHLIGHT
     ============================================================ -->
<?php
$featured_campaign = get_posts( [
    'post_type'      => 'fw_initiative',
    'post_status'    => 'publish',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );
$campaign = ! empty( $featured_campaign ) ? $featured_campaign[0] : null;
?>
<section class="campaign-highlight" id="campaign">
    <div class="container">
        <div class="campaign-highlight__inner">
            <div class="campaign-highlight__text">
                <span class="campaign-highlight__label"><?php esc_html_e( 'Current Campaign', 'faithfulwitness' ); ?></span>
                <h2 class="campaign-highlight__title">
                    <?php if ( $campaign ) :
                        echo esc_html( get_the_title( $campaign ) );
                    else :
                        echo esc_html( get_theme_mod( 'fw_campaign_title', __( 'Stand With Immigrant Families', 'faithfulwitness' ) ) );
                    endif; ?>
                </h2>
                <p class="campaign-highlight__desc">
                    <?php if ( $campaign ) :
                        echo esc_html( wp_trim_words( get_the_excerpt( $campaign ), 30, '…' ) );
                    else :
                        echo esc_html( get_theme_mod( 'fw_campaign_desc', __( 'The moment calls for faithful witnesses to speak and act with courage. Join churches across the country in this critical campaign.', 'faithfulwitness' ) ) );
                    endif; ?>
                </p>
            </div>
            <div class="campaign-highlight__cta">
                <a href="<?php echo esc_url( $campaign ? get_permalink( $campaign ) : home_url( '/take-action' ) ); ?>"
                   class="btn btn--accent btn--lg">
                    <?php esc_html_e( 'Take Action', 'faithfulwitness' ); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     6B. UPCOMING EVENTS WIDGET (mini)
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
     7. SCRIPTURE ANCHOR — Acts 4:20
     ============================================================ -->
<section class="scripture-section" id="scripture">
    <div class="container">
        <div class="scripture-quote">
            <p class="scripture-quote__text">
                <?php echo esc_html( get_theme_mod( 'fw_scripture_text', __( 'As for us, we cannot help speaking about what we have seen and heard.', 'faithfulwitness' ) ) ); ?>
            </p>
            <span class="scripture-quote__ref">
                <?php echo esc_html( get_theme_mod( 'fw_scripture_ref', __( 'Acts 4:20', 'faithfulwitness' ) ) ); ?>
            </span>
        </div>
    </div>
</section>

<!-- ============================================================
     8. PARTNER LOGOS
     ============================================================ -->
<section class="partners-section" id="partners">
    <div class="container">
        <p class="partners-label"><?php esc_html_e( 'In partnership with:', 'faithfulwitness' ); ?></p>
        <div class="partner-logos">
            <?php
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
