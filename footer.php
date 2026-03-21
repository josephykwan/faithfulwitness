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
                <p><?php echo esc_html( get_theme_mod( 'fw_footer_tagline', __( 'A Gospel-centered campaign forming the Church for courageous, nonviolent engagement around immigration.', 'faithfulwitness' ) ) ); ?></p>

                <div class="social-links" style="margin-top: var(--space-6);">
                    <?php
                    $social_links = [
                        'instagram' => [ 'url' => get_theme_mod( 'fw_social_instagram', '' ), 'label' => 'Instagram', 'abbr' => 'IG' ],
                        'facebook'  => [ 'url' => get_theme_mod( 'fw_social_facebook',  '' ), 'label' => 'Facebook',  'abbr' => 'FB' ],
                        'twitter'   => [ 'url' => get_theme_mod( 'fw_social_twitter',   '' ), 'label' => 'X/Twitter', 'abbr' => 'X'  ],
                        'youtube'   => [ 'url' => get_theme_mod( 'fw_social_youtube',   '' ), 'label' => 'YouTube',   'abbr' => 'YT' ],
                    ];
                    foreach ( $social_links as $data ) :
                        if ( ! $data['url'] ) continue; ?>
                        <a href="<?php echo esc_url( $data['url'] ); ?>"
                           class="social-link"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="<?php echo esc_attr( $data['label'] ); ?>">
                            <?php echo esc_html( $data['abbr'] ); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Mailchimp CTA in footer -->
                <div style="margin-top: var(--space-6);">
                    <a href="https://mailchi.mp/ccda/join-the-faithful-witness-campaign"
                       class="btn btn--accent btn--sm"
                       target="_blank"
                       rel="noopener noreferrer">
                        <?php esc_html_e( 'Join the Campaign', 'faithfulwitness' ); ?>
                    </a>
                </div>
            </div>

            <!-- Footer nav column 1: Our Work -->
            <div class="footer-col">
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col1_title', __( 'Our Work', 'faithfulwitness' ) ) ); ?></h4>
                <?php if ( has_nav_menu( 'footer-1' ) ) : ?>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-1',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php else : ?>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/initiatives' ) ); ?>"><?php esc_html_e( 'Initiatives', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/take-action' ) ); ?>"><?php esc_html_e( 'Take Action', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/stories' ) ); ?>"><?php esc_html_e( 'Stories', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/spiritual-formation' ) ); ?>"><?php esc_html_e( 'Spiritual Formation', 'faithfulwitness' ); ?></a></li>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Footer nav column 2: Get Involved -->
            <div class="footer-col">
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col2_title', __( 'Get Involved', 'faithfulwitness' ) ) ); ?></h4>
                <?php if ( has_nav_menu( 'footer-2' ) ) : ?>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-2',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php else : ?>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/network' ) ); ?>"><?php esc_html_e( 'Find a Local Group', 'faithfulwitness' ); ?></a></li>
                    <li><a href="https://mailchi.mp/ccda/join-the-faithful-witness-campaign" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Join the Campaign', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/events' ) ); ?>"><?php esc_html_e( 'Events', 'faithfulwitness' ); ?></a></li>
                </ul>
                <?php endif; ?>
            </div>

            <!-- Footer nav column 3: Resources -->
            <div class="footer-col">
                <h4><?php echo esc_html( get_theme_mod( 'fw_footer_col3_title', __( 'Resources', 'faithfulwitness' ) ) ); ?></h4>
                <?php if ( has_nav_menu( 'footer-3' ) ) : ?>
                <?php wp_nav_menu( [
                    'theme_location' => 'footer-3',
                    'container'      => false,
                    'depth'          => 1,
                ] ); ?>
                <?php else : ?>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/resources' ) ); ?>"><?php esc_html_e( 'Resource Library', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/know-your-rights' ) ); ?>"><?php esc_html_e( 'Know Your Rights', 'faithfulwitness' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/spiritual-formation' ) ); ?>"><?php esc_html_e( 'Spiritual Formation', 'faithfulwitness' ); ?></a></li>
                </ul>
                <?php endif; ?>
            </div>

        </div><!-- .footer-grid -->

        <!-- Partners strip -->
        <div style="border-top: 1px solid rgba(255,255,255,0.08); padding-top: var(--space-8); margin-bottom: var(--space-6);">
            <p style="font-size:var(--text-xs);font-weight:700;letter-spacing:0.1em;text-transform:uppercase;opacity:0.4;margin-bottom:var(--space-4);"><?php esc_html_e( 'In partnership with', 'faithfulwitness' ); ?></p>
            <div style="display:flex;flex-wrap:wrap;gap:var(--space-4);align-items:center;">
                <a href="https://nalec.org" target="_blank" rel="noopener noreferrer" style="opacity:0.55;font-size:var(--text-sm);font-weight:700;color:rgba(255,255,255,0.7);">NaLEC</a>
                <a href="https://ccda.org" target="_blank" rel="noopener noreferrer" style="opacity:0.55;font-size:var(--text-sm);font-weight:700;color:rgba(255,255,255,0.7);">CCDA</a>
                <a href="https://worldrelief.org" target="_blank" rel="noopener noreferrer" style="opacity:0.55;font-size:var(--text-sm);font-weight:700;color:rgba(255,255,255,0.7);">World Relief</a>
                <a href="https://undivided.us" target="_blank" rel="noopener noreferrer" style="opacity:0.55;font-size:var(--text-sm);font-weight:700;color:rgba(255,255,255,0.7);">Undivided</a>
            </div>
        </div>

        <div class="footer-bottom">
            <span>
                &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
                <?php bloginfo( 'name' ); ?>.
                <?php esc_html_e( 'All rights reserved.', 'faithfulwitness' ); ?>
            </span>
            <?php if ( has_nav_menu( 'footer-legal' ) ) : ?>
            <?php wp_nav_menu( [
                'theme_location'  => 'footer-legal',
                'container'       => 'nav',
                'container_class' => 'footer-legal-nav',
                'depth'           => 1,
            ] ); ?>
            <?php else : ?>
            <span style="opacity:0.6;font-size:var(--text-xs);">
                <a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>" style="color:inherit;"><?php esc_html_e( 'Privacy Policy', 'faithfulwitness' ); ?></a>
            </span>
            <?php endif; ?>
        </div>

    </div><!-- .container -->
</footer>

</div><!-- .site-wrapper -->

<?php wp_footer(); ?>
</body>
</html>
