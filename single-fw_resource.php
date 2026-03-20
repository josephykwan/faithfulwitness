<?php
/**
 * Single: Resource
 */
get_header();
while ( have_posts() ) : the_post();
    $type_tag   = fw_get_resource_type_tag( get_the_ID() );
    $init_tags  = fw_get_initiative_tags( get_the_ID() );
    $res_url    = get_post_meta( get_the_ID(), 'fw_resource_url', true );
    $file_id    = get_post_meta( get_the_ID(), 'fw_resource_file_id', true );
    $file_url   = $file_id ? wp_get_attachment_url( (int) $file_id ) : '';
    $init_id    = get_post_meta( get_the_ID(), 'fw_related_initiative_id', true );

    // Related resources (same initiative)
    $related = $init_id ? fw_get_initiative_resources( (int) $init_id, 4 ) : [];
    $related = array_filter( $related, fn( $r ) => $r->ID !== get_the_ID() );
?>

<section class="section section--sm" style="background:var(--color-bg-alt);">
    <div class="container container--narrow">
        <?php fw_breadcrumbs(); ?>
    </div>
</section>

<section class="section">
    <div class="container container--narrow">

        <!-- Type + initiative tags -->
        <div class="card__meta" style="margin-bottom: var(--space-4);">
            <?php echo $type_tag; // phpcs:ignore ?>
            <?php echo $init_tags; // phpcs:ignore ?>
        </div>

        <h1><?php the_title(); ?></h1>

        <?php if ( $excerpt = get_the_excerpt() ) : ?>
        <p style="font-size:var(--text-xl);color:var(--color-text-light);margin-top:var(--space-4);margin-bottom:var(--space-6);"><?php echo esc_html( $excerpt ); ?></p>
        <?php endif; ?>

        <!-- Action buttons -->
        <div class="flex gap-4" style="flex-wrap:wrap;margin-bottom:var(--space-8);">
            <?php if ( $file_url ) : ?>
            <a href="<?php echo esc_url( $file_url ); ?>" class="btn btn--accent btn--lg" download>
                <?php esc_html_e( '↓ Download', 'faithfulwitness' ); ?>
            </a>
            <?php endif; ?>
            <?php if ( $res_url ) : ?>
            <a href="<?php echo esc_url( $res_url ); ?>" class="btn btn--primary btn--lg" target="_blank" rel="noopener noreferrer">
                <?php esc_html_e( 'Read Article', 'faithfulwitness' ); ?> ↗
            </a>
            <?php endif; ?>
            <?php if ( $init_id ) : ?>
            <a href="<?php echo esc_url( get_permalink( $init_id ) ); ?>" class="btn btn--outline">
                <?php esc_html_e( '← Back to Initiative', 'faithfulwitness' ); ?>
            </a>
            <?php endif; ?>
        </div>

        <?php if ( has_post_thumbnail() ) : ?>
        <div style="margin-bottom:var(--space-8);border-radius:var(--border-radius-lg);overflow:hidden;">
            <?php the_post_thumbnail( 'fw-hero', [ 'loading' => 'lazy' ] ); ?>
        </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php the_content(); ?>
        </div>

    </div>
</section>

<!-- Related resources -->
<?php if ( ! empty( $related ) ) : ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'More Resources', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'From This Initiative', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--auto">
            <?php foreach ( $related as $res ) : fw_render_resource_card( $res ); endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer();
