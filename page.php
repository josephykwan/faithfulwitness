<?php
/**
 * Default page template
 */
get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="hero hero--short">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="hero__bg" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'fw-hero' ) ); ?>');"></div>
    <div class="hero__overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="hero__content">
            <?php fw_breadcrumbs(); ?>
            <h1><?php the_title(); ?></h1>
        </div>
    </div>
</div>

<section class="section">
    <div class="container container--narrow">
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer();
