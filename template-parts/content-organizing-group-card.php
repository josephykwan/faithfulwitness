<?php
/**
 * Template part: organizing group card
 */
$og = $args['post'] ?? $GLOBALS['post'];
if ( ! $og instanceof WP_Post ) return;

$city    = get_post_meta( $og->ID, 'fw_city', true );
$state   = get_post_meta( $og->ID, 'fw_state_abbr', true );
$email   = get_post_meta( $og->ID, 'fw_contact_email', true );
$phone   = get_post_meta( $og->ID, 'fw_contact_phone', true );
$website = get_post_meta( $og->ID, 'fw_website_url', true );
$ig      = get_post_meta( $og->ID, 'fw_instagram_url', true );
$fb      = get_post_meta( $og->ID, 'fw_facebook_url', true );
$tw      = get_post_meta( $og->ID, 'fw_twitter_url', true );
?>
<article id="group-<?php echo esc_attr( $og->ID ); ?>" class="og-card card">

    <?php if ( has_post_thumbnail( $og ) ) : ?>
    <div class="card__image">
        <a href="<?php echo esc_url( get_permalink( $og ) ); ?>" tabindex="-1">
            <?php echo get_the_post_thumbnail( $og, 'fw-card', [ 'loading' => 'lazy' ] ); ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="card__body">
        <div class="card__meta">
            <?php if ( $city || $state ) : ?>
            <span class="tag">
                <?php echo esc_html( implode( ', ', array_filter( [ $city, $state ] ) ) ); ?>
            </span>
            <?php endif; ?>
        </div>

        <h3 class="card__title">
            <a href="<?php echo esc_url( get_permalink( $og ) ); ?>"><?php echo esc_html( get_the_title( $og ) ); ?></a>
        </h3>

        <p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $og ), 20, '…' ) ); ?></p>

        <div class="og-card__contacts">
            <?php if ( $email ) : ?>
            <a href="mailto:<?php echo esc_attr( $email ); ?>" class="og-card__contact-link">
                <span aria-hidden="true">✉</span> <?php echo esc_html( $email ); ?>
            </a>
            <?php endif; ?>
            <?php if ( $phone ) : ?>
            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="og-card__contact-link">
                <span aria-hidden="true">📞</span> <?php echo esc_html( $phone ); ?>
            </a>
            <?php endif; ?>
        </div>

        <div class="card__footer">
            <a href="<?php echo esc_url( get_permalink( $og ) ); ?>" class="btn btn--sm btn--outline">
                <?php esc_html_e( 'Learn More', 'faithfulwitness' ); ?>
            </a>
            <div class="og-card__social">
                <?php if ( $ig ) : ?>
                    <a href="<?php echo esc_url( $ig ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram" style="background:rgba(27,79,114,0.1);color:var(--color-primary);">IG</a>
                <?php endif; ?>
                <?php if ( $fb ) : ?>
                    <a href="<?php echo esc_url( $fb ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook" style="background:rgba(27,79,114,0.1);color:var(--color-primary);">FB</a>
                <?php endif; ?>
                <?php if ( $tw ) : ?>
                    <a href="<?php echo esc_url( $tw ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Twitter / X" style="background:rgba(27,79,114,0.1);color:var(--color-primary);">X</a>
                <?php endif; ?>
                <?php if ( $website ) : ?>
                    <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Website" style="background:rgba(27,79,114,0.1);color:var(--color-primary);">↗</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</article>
