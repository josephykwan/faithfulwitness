<?php
/**
 * Archive: Initiatives
 */
get_header(); ?>

<div class="hero hero--short">
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php esc_html_e( 'Our Work', 'faithfulwitness' ); ?></span>
            <h1><?php esc_html_e( 'Initiatives', 'faithfulwitness' ); ?></h1>
            <p><?php esc_html_e( 'Campaigns and organizing efforts for immigrant justice.', 'faithfulwitness' ); ?></p>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <div class="grid grid--3">
            <?php while ( have_posts() ) : the_post();
                $cta_url   = get_post_meta( get_the_ID(), 'fw_initiative_cta_url', true );
                $cta_label = get_post_meta( get_the_ID(), 'fw_initiative_cta_label', true );
                $status    = get_post_meta( get_the_ID(), 'fw_initiative_status', true );
                $resources = fw_get_initiative_resources( get_the_ID(), 3 );
            ?>
            <article id="init-<?php the_ID(); ?>" <?php post_class( 'card initiative-card' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="card__image">
                    <a href="<?php the_permalink(); ?>" tabindex="-1">
                        <?php the_post_thumbnail( 'fw-card', [ 'loading' => 'lazy' ] ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="card__body">
                    <div class="card__meta">
                        <?php echo fw_get_initiative_tags( get_the_ID() ); // phpcs:ignore ?>
                        <?php if ( $status === 'active' ) : ?>
                        <span class="tag" style="background:#EAFAF1;color:#1E8449;"><?php esc_html_e( 'Active', 'faithfulwitness' ); ?></span>
                        <?php endif; ?>
                    </div>
                    <h2 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '…' ) ); ?></p>

                    <?php if ( ! empty( $resources ) ) : ?>
                    <div style="font-size:var(--text-sm);color:var(--color-text-muted);margin-bottom:var(--space-4);">
                        <?php printf( esc_html( _n( '%d resource', '%d resources', count( $resources ), 'faithfulwitness' ) ), count( $resources ) ); ?>
                    </div>
                    <?php endif; ?>

                    <div class="card__footer">
                        <a href="<?php the_permalink(); ?>" class="btn btn--sm btn--outline">
                            <?php esc_html_e( 'Learn More', 'faithfulwitness' ); ?>
                        </a>
                        <?php if ( $cta_url ) : ?>
                        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--sm btn--accent">
                            <?php echo esc_html( $cta_label ?: __( 'Take Action', 'faithfulwitness' ) ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endwhile; ?>
        </div>
        <?php the_posts_pagination( [ 'class' => 'pagination' ] ); ?>
        <?php else : ?>
        <p><?php esc_html_e( 'No initiatives found.', 'faithfulwitness' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();
