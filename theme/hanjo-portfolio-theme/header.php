<?php
/**
 * Theme header.
 *
 * @package Hanjo_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?> >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
  <div class="container site-header-inner">
    <div class="brand">
      <div class="brand-mark"></div>
      <div class="brand-text">
        <div class="brand-name"><?php bloginfo( 'name' ); ?></div>
        <div class="brand-tagline"><?php bloginfo( 'description' ); ?></div>
      </div>
    </div>

    <?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) : ?>
      <nav class="nav" id="site-nav">
        <?php
        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => function () {
                    echo '<a href="#hero">' . esc_html__( 'Start', 'hanjo-portfolio-theme' ) . '</a>';
                    echo '<a href="#about">' . esc_html__( 'Über mich', 'hanjo-portfolio-theme' ) . '</a>';
                    echo '<a href="#cv">' . esc_html__( 'Lebenslauf', 'hanjo-portfolio-theme' ) . '</a>';
                    echo '<a href="#portfolio">' . esc_html__( 'Portfolio', 'hanjo-portfolio-theme' ) . '</a>';
                    echo '<a href="#contact">' . esc_html__( 'Kontakt', 'hanjo-portfolio-theme' ) . '</a>';
                },
                'items_wrap'     => '%3$s',
            )
        );
        ?>
        <a class="nav-cta" href="<?php echo esc_url( get_theme_mod( 'hpt_cv_url', '#' ) ); ?>" target="_blank" rel="noopener">
          <?php esc_html_e( 'PDF-CV', 'hanjo-portfolio-theme' ); ?> <span>↗</span>
        </a>
      </nav>
    <?php endif; ?>

    <button class="nav-toggle" id="nav-toggle" aria-label="<?php esc_attr_e( 'Navigation umschalten', 'hanjo-portfolio-theme' ); ?>">
      <div class="nav-toggle-lines">
        <span></span>
      </div>
    </button>
  </div>
</header>
