<?php get_header(); ?>

<section class="section">
    <div class="container">
        <?php fw_breadcrumbs(); ?>

        <div class="section-header">
            <?php
            if ( is_home() ) {
                echo '<h1>' . esc_html__( 'Latest Stories', 'faithfulwitness' ) . '</h1>';
            } elseif ( is_search() ) {
                echo '<h1>' . sprintf( esc_html__( 'Search Results for: %s', 'faithfulwitness' ), '<em>' . esc_html( get_search_query() ) . '</em>' ) . '</h1>';
            } elseif ( is_archive() ) {
                the_archive_title( '<h1>', '</h1>' );
                the_archive_description( '<p class="archive-description">', '</p>' );
            }
            ?>
        </div>

        <?php if ( have_posts() ) : ?>

        <div class="grid grid--3">
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'template-parts/content', 'post' ); ?>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_pagination( [
            'mid_size'  => 2,
            'prev_text' => '← ' . __( 'Previous', 'faithfulwitness' ),
            'next_text' => __( 'Next', 'faithfulwitness' ) . ' →',
            'class'     => 'pagination',
        ] );
        ?>

        <?php else : ?>
        <p><?php esc_html_e( 'No posts found.', 'faithfulwitness' ); ?></p>
        <?php endif; ?>

    </div>
</section>

<?php get_footer();
