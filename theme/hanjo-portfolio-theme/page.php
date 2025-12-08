<?php
/**
 * Default page template with Elementor compatibility.
 *
 * @package Hanjo_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'single' ) ) {
    get_footer();
    return;
}
?>
<main class="container" style="padding: 4rem 1.5rem;">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="section-header" style="margin-bottom:1.5rem;">
          <div>
            <div class="section-kicker"><?php echo esc_html( get_the_date() ); ?></div>
            <h1 class="section-title"><?php the_title(); ?></h1>
          </div>
        </header>
        <div class="about-text">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php endif; ?>
</main>
<?php
get_footer();
