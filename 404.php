<?php get_header(); ?>

<section class="section">
    <div class="container">
        <div class="error-404-content">
            <h1>404</h1>
            <h2><?php esc_html_e( 'Page Not Found', 'faithfulwitness' ); ?></h2>
            <p><?php esc_html_e( 'The page you\'re looking for doesn\'t exist or has been moved.', 'faithfulwitness' ); ?></p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:2rem;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary"><?php esc_html_e( '← Back Home', 'faithfulwitness' ); ?></a>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'fw_initiative' ) ); ?>" class="btn btn--outline"><?php esc_html_e( 'Our Initiatives', 'faithfulwitness' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer();
