<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content"><?php esc_html_e( 'Skip to content', 'faithfulwitness' ); ?></a>

<div class="site-wrapper" id="page">

<?php
// Optional notice bar — configured via Customizer
$notice = get_theme_mod( 'fw_notice_bar_text', '' );
if ( $notice ) : ?>
<div class="notice-bar" role="banner">
    <?php echo wp_kses_post( $notice ); ?>
</div>
<?php endif; ?>

<header class="site-header" id="site-header" role="banner">
    <div class="container">
        <div class="site-header__inner">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home">
                <?php if ( has_custom_logo() ) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <?php bloginfo( 'name' ); ?>
                <?php endif; ?>
            </a>

            <!-- Primary Navigation -->
            <nav class="primary-nav" id="primary-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'faithfulwitness' ); ?>">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_class'     => 'primary-nav__menu',
                    'container'      => false,
                    'fallback_cb'    => 'fw_fallback_menu',
                    'walker'         => class_exists( 'FW_Nav_Walker' ) ? new FW_Nav_Walker() : null,
                ] );
                ?>
                <a href="<?php echo esc_url( get_theme_mod( 'fw_nav_cta_url', 'https://mailchi.mp/ccda/join-the-faithful-witness-campaign' ) ); ?>"
                   class="nav-cta"
                   target="_blank"
                   rel="noopener noreferrer">
                    <?php echo esc_html( get_theme_mod( 'fw_nav_cta_text', __( 'Join the Campaign', 'faithfulwitness' ) ) ); ?>
                </a>
            </nav>

            <!-- Google Translate widget -->
            <div id="google_translate_element" class="google-translate-widget" aria-label="<?php esc_attr_e( 'Language selector', 'faithfulwitness' ); ?>"></div>

            <!-- Mobile toggle -->
            <button class="nav-toggle" id="nav-toggle"
                    aria-controls="primary-nav"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle menu', 'faithfulwitness' ); ?>">
                <span class="nav-toggle-icon" aria-hidden="true">
                    <span></span><span></span><span></span>
                </span>
            </button>

        </div>
    </div>
</header>

<main class="site-content" id="main-content">

<?php
// Google Translate — initialize widget (free, no API key required)
add_action( 'wp_footer', function () {
    ?>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                { pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE },
                'google_translate_element'
            );
        }
    </script>
    <script type="text/javascript"
        src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
    </script>
    <?php
}, 20 );
?>

<?php
/**
 * Fallback menu when no menu is assigned to 'primary'.
 * Shows a placeholder with a link to the menus screen.
 */
function fw_fallback_menu() {
    // Build a default nav from the registered menu structure
    $items = [
        __( 'Our Work',   'faithfulwitness' ) => '#',
        __( 'The Network','faithfulwitness' ) => '#',
        __( 'Resources',  'faithfulwitness' ) => '#',
        __( 'Stories',    'faithfulwitness' ) => '#',
        __( 'Take Action','faithfulwitness' ) => '#',
    ];
    echo '<ul class="primary-nav__menu">';
    foreach ( $items as $label => $url ) {
        echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}
