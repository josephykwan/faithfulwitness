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

<div class="site-wrapper" id="page">

<?php
// Optional notice bar — set a custom field on the front-page or via Customizer
$notice = get_theme_mod( 'fw_notice_bar_text', '' );
if ( $notice ) :
?>
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
                ] );
                ?>
                <a href="<?php echo esc_url( get_theme_mod( 'fw_nav_cta_url', '#take-action' ) ); ?>" class="nav-cta">
                    <?php echo esc_html( get_theme_mod( 'fw_nav_cta_text', __( 'Take Action', 'faithfulwitness' ) ) ); ?>
                </a>
            </nav>

            <!-- Mobile toggle -->
            <button class="nav-toggle" id="nav-toggle" aria-controls="primary-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle menu', 'faithfulwitness' ); ?>">
                <span class="nav-toggle-icon" aria-hidden="true">
                    <span></span><span></span><span></span>
                </span>
            </button>

        </div>
    </div>
</header>

<main class="site-content" id="main-content">

<?php
function fw_fallback_menu() {
    echo '<ul><li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Set up navigation →', 'faithfulwitness' ) . '</a></li></ul>';
}
