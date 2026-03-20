</main><!-- #main-content -->

<footer class="site-footer" id="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand column -->
            <div class="footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <?php bloginfo( 'name' ); ?>
                    <?php endif; ?>
                </a>
                <p><?php echo esc_html( get_theme_mod( 'fw_footer_tagline', get_bloginfo( 'description' ) ) ); ?></p>

                <div class="social-links" style="margin-top: 1.5rem;">
                    <?php
                    $social_links = [
                        'instagram' => get_theme_mod( 'fw_social_instagram', '' ),
                        'facebook'  => get_theme_mod( 'fw_social_facebook', '' ),
                        'twitter'   => get_theme_mod( 'fw_social_twitter', '' ),
                        'youtube'   => get_theme_mod( 'fw_social_youtube', '' ),
                    ];
                    $social_icons = [
                        'instagram' => 'IG',
                        'facebook'  => 'FB',
                        'twitter'   => 'X',
                        'youtube'   => 'YT',
                    ];
                    foreach ( $social_links as $platform => $url ) :
                        if ( ! $url ) continue; ?>
                        <a href="<?php echo esc_url( $url ); ?>" class="social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                            <?php echo esc_html( $social_icons[ $platform ] ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Footer nav column 1 -->
            <div class="footer-col">
                <?php if ( has_nav_menu( 'footer-1' ) ) : ?>
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col1_title', __( 'Our Work', 'faithfulwitness' ) ) ); ?></h4>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-1',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php endif; ?>
            </div>

            <!-- Footer nav column 2 -->
            <div class="footer-col">
                <?php if ( has_nav_menu( 'footer-2' ) ) : ?>
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col2_title', __( 'Get Involved', 'faithfulwitness' ) ) ); ?></h4>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-2',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php endif; ?>
            </div>

            <!-- Footer nav column 3 -->
            <div class="footer-col">
                <?php if ( has_nav_menu( 'footer-3' ) ) : ?>
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col3_title', __( 'Resources', 'faithfulwitness' ) ) ); ?></h4>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-3',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php endif; ?>
            </div>

        </div><!-- .footer-grid -->

        <div class="footer-bottom">
            <span>
                &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
                <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'faithfulwitness' ); ?>
            </span>
            <?php if ( has_nav_menu( 'footer-legal' ) ) :
                wp_nav_menu( [
                    'theme_location' => 'footer-legal',
                    'container'      => 'nav',
                    'container_class' => 'footer-legal-nav',
                    'depth'          => 1,
                ] );
            endif; ?>
        </div>

    </div><!-- .container -->
</footer>

</div><!-- .site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
