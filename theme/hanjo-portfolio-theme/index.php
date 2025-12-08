<?php
/**
 * Fallback index template.
 *
 * @package Hanjo_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $wp_query;

if ( is_front_page() ) {
    include get_template_directory() . '/front-page.php';
    return;
}

get_header();
?>
<main class="container" style="padding: 4rem 1.5rem;">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'card' ); ?> style="margin-bottom:2rem;">
        <header class="section-header" style="margin-bottom:1rem;">
          <div>
            <div class="section-kicker"><?php echo esc_html( get_the_date() ); ?></div>
            <h2 class="section-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
          </div>
        </header>
        <div class="about-text">
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; ?>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <p><?php esc_html_e( 'Keine Beiträge gefunden.', 'hanjo-portfolio-theme' ); ?></p>
  <?php endif; ?>
</main>
<?php
get_footer();
