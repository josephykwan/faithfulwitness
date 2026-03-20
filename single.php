<?php
/**
 * Single blog post
 */
get_header();
while ( have_posts() ) : the_post();
    $init_tags = fw_get_initiative_tags( get_the_ID() );
?>

<div class="hero hero--short single-post">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php fw_breadcrumbs(); ?>
            <?php if ( $init_tags ) : ?>
            <div style="margin-bottom:var(--space-3);"><?php echo $init_tags; // phpcs:ignore ?></div>
            <?php endif; ?>
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="content-with-sidebar">

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-article' ); ?>>
                <div class="post-meta">
                    <span class="author"><?php the_author(); ?></span>
                    <span><?php echo esc_html( get_the_date() ); ?></span>
                    <?php if ( has_tag() ) : ?>
                    <span><?php the_tags( '', ' · ' ); ?></span>
                    <?php endif; ?>
                </div>
                <div class="entry-content">
                    <?php the_content(); ?>
                    <?php
                    wp_link_pages( [
                        'before' => '<nav class="page-links"><strong>' . __( 'Pages:', 'faithfulwitness' ) . '</strong>',
                        'after'  => '</nav>',
                    ] );
                    ?>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:var(--space-8);padding-top:var(--space-6);border-top:1px solid var(--color-border);">
                    <?php echo $init_tags; // phpcs:ignore ?>
                </div>
            </article>

            <aside class="sidebar">
                <?php if ( is_active_sidebar( 'blog-sidebar' ) ) :
                    dynamic_sidebar( 'blog-sidebar' );
                endif; ?>

                <?php if ( $init_tags ) : ?>
                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Related Initiatives', 'faithfulwitness' ); ?></div>
                    <div style="display:flex;flex-wrap:wrap;gap:.5rem;"><?php echo $init_tags; // phpcs:ignore ?></div>
                </div>
                <?php endif; ?>

                <div class="sidebar-widget">
                    <div class="sidebar-widget__title"><?php esc_html_e( 'Recent Stories', 'faithfulwitness' ); ?></div>
                    <?php
                    $recent = get_posts( [ 'posts_per_page' => 4, 'post__not_in' => [ get_the_ID() ] ] );
                    foreach ( $recent as $rp ) : ?>
                    <div style="margin-bottom:var(--space-4);padding-bottom:var(--space-4);border-bottom:1px solid var(--color-border);">
                        <a href="<?php echo esc_url( get_permalink( $rp ) ); ?>" style="font-weight:600;font-size:var(--text-sm);text-decoration:none;color:var(--color-text);">
                            <?php echo esc_html( get_the_title( $rp ) ); ?>
                        </a>
                        <div style="font-size:var(--text-xs);color:var(--color-text-muted);margin-top:var(--space-1);">
                            <?php echo esc_html( get_the_date( '', $rp ) ); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </aside>

        </div>
    </div>
</section>

<!-- Related posts by initiative category -->
<?php
$related_posts = [];
$term_ids = wp_get_post_terms( get_the_ID(), 'fw_initiative_category', [ 'fields' => 'ids' ] );
if ( ! empty( $term_ids ) && ! is_wp_error( $term_ids ) ) {
    $related_posts = get_posts( [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [ get_the_ID() ],
        'tax_query'      => [
            [ 'taxonomy' => 'fw_initiative_category', 'field' => 'term_id', 'terms' => $term_ids ],
        ],
    ] );
}
if ( ! empty( $related_posts ) ) : ?>
<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <span class="eyebrow"><?php esc_html_e( 'Continue Reading', 'faithfulwitness' ); ?></span>
            <h2><?php esc_html_e( 'Related Stories', 'faithfulwitness' ); ?></h2>
        </div>
        <div class="grid grid--3">
            <?php foreach ( $related_posts as $rp ) :
                setup_postdata( $rp );
                get_template_part( 'template-parts/content', 'post' );
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer();
