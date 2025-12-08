<?php
/**
 * Theme footer.
 *
 * @package Hanjo_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) : ?>
<footer class="site-footer">
  <div class="container site-footer-inner">
    <div>© <?php echo esc_html( date_i18n( 'Y' ) ); ?> · <?php bloginfo( 'name' ); ?></div>
    <div class="footer-links">
      <?php
      wp_nav_menu(
          array(
              'theme_location' => 'primary',
              'container'      => false,
              'depth'          => 1,
              'items_wrap'     => '%3$s',
              'fallback_cb'    => function () {
                  echo '<a href="#hero">' . esc_html__( 'Impressum', 'hanjo-portfolio-theme' ) . '</a>';
                  echo '<a href="#contact">' . esc_html__( 'Datenschutz', 'hanjo-portfolio-theme' ) . '</a>';
              },
          )
      );
      ?>
    </div>
  </div>
</footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
