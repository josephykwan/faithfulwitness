<?php
/**
 * Template part: blog post card
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
    <div class="card__image">
        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail( 'fw-card', [ 'loading' => 'lazy' ] ); ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="card__body">
        <div class="card__meta">
            <?php
            $cats = get_the_terms( get_the_ID(), 'fw_initiative_category' );
            if ( $cats && ! is_wp_error( $cats ) ) :
                foreach ( $cats as $cat ) :
            ?>
                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="tag tag--primary">
                    <?php echo esc_html( $cat->name ); ?>
                </a>
            <?php
                endforeach;
            endif;
            ?>
        </div>

        <h3 class="card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p class="card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 25, '…' ) ); ?></p>

        <div class="card__footer">
            <span class="post-date" style="font-size: var(--text-sm); color: var(--color-text-muted);">
                <?php echo esc_html( get_the_date() ); ?>
            </span>
            <a href="<?php the_permalink(); ?>" class="btn btn--sm btn--outline">
                <?php esc_html_e( 'Read More', 'faithfulwitness' ); ?>
            </a>
        </div>
    </div>

</article>
