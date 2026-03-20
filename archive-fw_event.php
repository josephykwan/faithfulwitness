<?php
/**
 * Archive: Events — redirects to template-events.php page if one exists,
 * otherwise renders a simple list.
 */
get_header(); ?>

<div class="hero hero--short">
    <div class="container">
        <div class="hero__content">
            <span class="hero__eyebrow"><?php esc_html_e( 'Join the Movement', 'faithfulwitness' ); ?></span>
            <h1><?php esc_html_e( 'Events', 'faithfulwitness' ); ?></h1>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
        <div class="grid grid--3">
            <?php while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content', 'event-card', [ 'post' => $post ] );
            endwhile; ?>
        </div>
        <?php the_posts_pagination( [ 'class' => 'pagination' ] ); ?>
        <?php else : ?>
        <p><?php esc_html_e( 'No upcoming events found.', 'faithfulwitness' ); ?></p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer();
