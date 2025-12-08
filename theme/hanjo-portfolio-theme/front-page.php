<?php
/**
 * Front page template mirroring the provided single-page layout.
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

$cv_url        = get_theme_mod( 'hpt_cv_url', '#' );
$hero_kicker   = get_theme_mod( 'hpt_hero_kicker', __( 'Portfolio & Lebenslauf', 'hanjo-portfolio-theme' ) );
$hero_title    = get_theme_mod( 'hpt_hero_title', __( 'Kreatives <span>Design</span> trifft<br>strukturierte <span>Technik</span>.', 'hanjo-portfolio-theme' ) );
$hero_subtitle = get_theme_mod( 'hpt_hero_subtitle', __( 'Ich verbinde Gestaltung, Webentwicklung und moderne KI-Tools, um Ideen schnell, klar und wirkungsvoll umzusetzen – von Social Ads bis zu kompletten Web-Erlebnissen.', 'hanjo-portfolio-theme' ) );

$hero_tags = array(
    __( 'Offen für neue Projekte & Festanstellungen', 'hanjo-portfolio-theme' ),
    __( 'Remote & vor Ort (NRW)', 'hanjo-portfolio-theme' ),
);

$skill_terms = get_terms(
    array(
        'taxonomy'   => 'project_skill',
        'hide_empty' => false,
        'number'     => 4,
    )
);

$projects_query = new WP_Query(
    array(
        'post_type'      => 'projekt',
        'posts_per_page' => 6,
        'orderby'        => array(
            'meta_value_num' => 'ASC',
            'date'           => 'DESC',
        ),
        'meta_key'       => 'projekt_sort_order',
    )
);

$experience_query = new WP_Query(
    array(
        'post_type'      => 'experience',
        'posts_per_page' => -1,
        'orderby'        => array(
            'meta_value_num' => 'ASC',
            'date'           => 'DESC',
        ),
        'meta_key'       => 'experience_order',
    )
);
?>

<main>
  <section class="hero" id="hero">
    <div class="container hero-inner">
      <div>
        <div class="hero-kicker"><?php echo esc_html( $hero_kicker ); ?></div>
        <h1 class="hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
        <p class="hero-subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>

        <div class="hero-actions">
          <a href="#portfolio" class="btn btn-primary">
            <span><?php esc_html_e( 'Portfolio ansehen', 'hanjo-portfolio-theme' ); ?></span> <span>→</span>
          </a>
          <a href="<?php echo esc_url( $cv_url ); ?>" class="btn btn-ghost" target="_blank" rel="noopener">
            <?php esc_html_e( 'Lebenslauf als PDF', 'hanjo-portfolio-theme' ); ?>
          </a>
        </div>

        <div class="hero-meta">
          <?php foreach ( $hero_tags as $tag ) : ?>
            <div class="hero-tag">
              <span class="hero-tag-dot"></span>
              <span><?php echo esc_html( $tag ); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <aside class="hero-panel" aria-label="Kurzprofil">
        <div class="hero-panel-label"><?php esc_html_e( 'Kurzprofil', 'hanjo-portfolio-theme' ); ?></div>
        <div class="hero-panel-main">
          <?php
          echo wp_kses_post(
              sprintf(
                  /* translators: %s: site name */
                  __( 'Ich arbeite an der Schnittstelle von <strong>Design, Webentwicklung und KI-gestützten Workflows</strong>. Mein Fokus: verständliche Strukturen, klare Visuals und saubere Umsetzung – immer im Sinne von %s.', 'hanjo-portfolio-theme' ),
                  esc_html( get_bloginfo( 'name' ) )
              )
          );
          ?>
        </div>
        <div class="hero-panel-grid">
          <?php if ( ! empty( $skill_terms ) && ! is_wp_error( $skill_terms ) ) : ?>
            <?php foreach ( $skill_terms as $term ) : ?>
              <div class="chip">
                <span class="chip-dot"></span>
                <span><?php echo esc_html( $term->name ); ?></span>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="chip"><span class="chip-dot"></span><span><?php esc_html_e( 'Performance-Creatives', 'hanjo-portfolio-theme' ); ?></span></div>
            <div class="chip"><span class="chip-dot"></span><span><?php esc_html_e( 'Webdesign & Frontend', 'hanjo-portfolio-theme' ); ?></span></div>
            <div class="chip"><span class="chip-dot"></span><span><?php esc_html_e( 'KI-gestützte Bild- & Textproduktion', 'hanjo-portfolio-theme' ); ?></span></div>
            <div class="chip"><span class="chip-dot"></span><span><?php esc_html_e( 'Konzept & Struktur', 'hanjo-portfolio-theme' ); ?></span></div>
          <?php endif; ?>
        </div>
        <div class="hero-panel-footer">
          <?php esc_html_e( 'Auf dieser Seite finden Sie meinen Lebenslauf, ausgewählte Projekte und einen Einblick, wie ich arbeite.', 'hanjo-portfolio-theme' ); ?>
        </div>
      </aside>
    </div>
  </section>

  <section id="about">
    <div class="container">
      <header class="section-header">
        <div>
          <div class="section-kicker"><?php esc_html_e( 'Über mich', 'hanjo-portfolio-theme' ); ?></div>
          <h2 class="section-title"><?php esc_html_e( 'Wer ich bin & wie ich arbeite', 'hanjo-portfolio-theme' ); ?></h2>
        </div>
        <p class="section-subtitle"><?php esc_html_e( 'Kurz gesagt: Ich mag klare Strukturen, ehrliche Kommunikation und Lösungen, die wirklich genutzt werden – nicht nur gut aussehen.', 'hanjo-portfolio-theme' ); ?></p>
      </header>

      <div class="about-grid">
        <div class="about-text">
          <p><?php esc_html_e( 'Ich arbeite seit einigen Jahren an Projekten, die Design, Technik und digitale Prozesse verbinden – von Social-Media-Assets über Landingpages bis zu kleineren Webtools.', 'hanjo-portfolio-theme' ); ?></p>
          <p><?php esc_html_e( 'Neben einem Auge für Gestaltung habe ich Spaß an sauberer Struktur: Informationen so aufzubereiten, dass sie schnell erfasst werden können – egal ob in einem Creative, einer Website oder einem internen Tool.', 'hanjo-portfolio-theme' ); ?></p>
          <p><?php esc_html_e( 'Mit KI-Tools gehe ich pragmatisch um: Sie sind für mich Werkzeuge, keine Magie. Ich nutze sie, um Ideen schneller zu variieren, Mockups zu entwickeln oder Content-Varianten zu erstellen – aber Konzept und Qualität bleiben in meiner Hand.', 'hanjo-portfolio-theme' ); ?></p>
        </div>

        <aside class="about-panel">
          <ul class="about-list">
            <li>
              <span class="about-list-label"><?php esc_html_e( 'Fokus', 'hanjo-portfolio-theme' ); ?></span>
              <span><?php esc_html_e( 'Design & Web – mit einem Bein im Code', 'hanjo-portfolio-theme' ); ?></span>
            </li>
            <li>
              <span class="about-list-label"><?php esc_html_e( 'Stärken', 'hanjo-portfolio-theme' ); ?></span>
              <span><?php esc_html_e( 'Struktur, klare Kommunikation, technisch-kreative Brücke', 'hanjo-portfolio-theme' ); ?></span>
            </li>
            <li>
              <span class="about-list-label"><?php esc_html_e( 'Tools', 'hanjo-portfolio-theme' ); ?></span>
              <span><?php esc_html_e( 'Adobe / Figma, KI-Bildgeneratoren, Web-Stack (HTML/CSS/JS)', 'hanjo-portfolio-theme' ); ?></span>
            </li>
            <li>
              <span class="about-list-label"><?php esc_html_e( 'Arbeitsstil', 'hanjo-portfolio-theme' ); ?></span>
              <span><?php esc_html_e( 'ehrlich, zuverlässig, iterativ – lieber solide als „schnell schön“', 'hanjo-portfolio-theme' ); ?></span>
            </li>
          </ul>
        </aside>
      </div>
    </div>
  </section>

  <section id="cv">
    <div class="container">
      <header class="section-header">
        <div>
          <div class="section-kicker"><?php esc_html_e( 'Lebenslauf', 'hanjo-portfolio-theme' ); ?></div>
          <h2 class="section-title"><?php esc_html_e( 'Erfahrung & Schwerpunkte', 'hanjo-portfolio-theme' ); ?></h2>
        </div>
        <p class="section-subtitle"><?php esc_html_e( 'Hier eine kompakte Übersicht. Eine ausführliche Version finden Sie im PDF-Lebenslauf.', 'hanjo-portfolio-theme' ); ?></p>
      </header>

      <div class="cv-grid">
        <aside class="card">
          <h3 class="card-title"><?php esc_html_e( 'Kontakt & Skills', 'hanjo-portfolio-theme' ); ?></h3>

          <div class="cv-contact">
            <div><?php esc_html_e( 'E-Mail:', 'hanjo-portfolio-theme' ); ?> <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"><?php echo esc_html( get_option( 'admin_email' ) ); ?></a></div>
            <div><?php esc_html_e( 'Ort: NRW, Deutschland', 'hanjo-portfolio-theme' ); ?></div>
            <div><?php esc_html_e( 'Website:', 'hanjo-portfolio-theme' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( home_url( '/' ) ); ?></a></div>
          </div>

          <div style="margin-bottom: 1rem;">
            <div class="cv-section-title"><?php esc_html_e( 'Skills', 'hanjo-portfolio-theme' ); ?></div>
            <div class="pill-row">
              <span class="pill"><?php esc_html_e( 'Layout & Typografie', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'Social & Ad Creatives', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'Landingpages', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'UX-orientiertes Denken', 'hanjo-portfolio-theme' ); ?></span>
            </div>
          </div>

          <div style="margin-bottom: 1rem;">
            <div class="cv-section-title"><?php esc_html_e( 'Tools', 'hanjo-portfolio-theme' ); ?></div>
            <div class="pill-row">
              <span class="pill"><?php esc_html_e( 'Adobe / Figma', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'Canva', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'HTML/CSS', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'Grundlagen JS', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'KI-Tools (Bild/Text)', 'hanjo-portfolio-theme' ); ?></span>
            </div>
          </div>

          <div>
            <div class="cv-section-title"><?php esc_html_e( 'Arbeitsweise', 'hanjo-portfolio-theme' ); ?></div>
            <p style="font-size:0.86rem; color:var(--color-text-muted); margin:0;">
              <?php esc_html_e( 'Ich arbeite gerne eng mit Auftraggebern zusammen, frage nach, wenn etwas unklar ist und setze auf Iterationen, statt mit einer „perfekten“ Lösung aus dem Off zu kommen.', 'hanjo-portfolio-theme' ); ?>
            </p>
          </div>
        </aside>

        <div class="card">
          <div style="margin-bottom: 1.4rem;">
            <div class="cv-section-title"><?php esc_html_e( 'Berufserfahrung', 'hanjo-portfolio-theme' ); ?></div>
            <ul class="timeline">
              <?php if ( $experience_query->have_posts() ) : ?>
                <?php while ( $experience_query->have_posts() ) : $experience_query->the_post(); ?>
                  <?php
                  $org        = hpt_get_experience_meta( get_the_ID(), 'experience_org' );
                  $location   = hpt_get_experience_meta( get_the_ID(), 'experience_location' );
                  $start      = hpt_get_experience_meta( get_the_ID(), 'experience_start_date' );
                  $end        = hpt_get_experience_meta( get_the_ID(), 'experience_end_date' );
                  $is_current = hpt_get_experience_meta( get_the_ID(), 'experience_is_current' );
                  $bullets    = hpt_parse_list_field( hpt_get_experience_meta( get_the_ID(), 'experience_bullets' ) );
                  $short_desc = hpt_get_experience_meta( get_the_ID(), 'experience_short_desc' );
                  ?>
                  <li class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-role"><?php the_title(); ?></div>
                    <div class="timeline-meta">
                      <?php echo esc_html( $org ); ?>
                      <?php if ( $location ) : ?>· <?php echo esc_html( $location ); ?><?php endif; ?> ·
                      <?php echo esc_html( $start ); ?> – <?php echo $is_current ? esc_html__( 'heute', 'hanjo-portfolio-theme' ) : esc_html( $end ); ?>
                    </div>
                    <div class="timeline-desc">
                      <?php if ( $short_desc ) : ?>
                        <p><?php echo esc_html( $short_desc ); ?></p>
                      <?php endif; ?>
                      <?php if ( ! empty( $bullets ) ) : ?>
                        <ul>
                          <?php foreach ( $bullets as $bullet ) : ?>
                            <li><?php echo esc_html( $bullet ); ?></li>
                          <?php endforeach; ?>
                        </ul>
                      <?php endif; ?>
                    </div>
                  </li>
                <?php endwhile; ?>
              <?php else : ?>
                <li class="timeline-item">
                  <div class="timeline-dot"></div>
                  <div class="timeline-role"><?php esc_html_e( 'Kreativallrounder / Mediengestaltung (Beispielrolle)', 'hanjo-portfolio-theme' ); ?></div>
                  <div class="timeline-meta"><?php esc_html_e( 'Firma XY · 2022 – heute · Remote / vor Ort', 'hanjo-portfolio-theme' ); ?></div>
                  <div class="timeline-desc">
                    <ul>
                      <li><?php esc_html_e( 'Entwicklung von Social-Media- und Performance-Creatives für verschiedene Branchen.', 'hanjo-portfolio-theme' ); ?></li>
                      <li><?php esc_html_e( 'Gestaltung von Landingpages & Ad-Assets in enger Abstimmung mit dem Kampagnenteam.', 'hanjo-portfolio-theme' ); ?></li>
                      <li><?php esc_html_e( 'Einsatz von KI-Tools für Bildideen, Variationen und schnelle Mockups.', 'hanjo-portfolio-theme' ); ?></li>
                    </ul>
                  </div>
                </li>
              <?php endif; ?>
            </ul>
          </div>

          <div>
            <div class="cv-section-title"><?php esc_html_e( 'Ausbildung & Weiterbildung', 'hanjo-portfolio-theme' ); ?></div>
            <ul class="timeline">
              <li class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-role"><?php esc_html_e( 'Relevante Ausbildung / Studium (Platzhalter)', 'hanjo-portfolio-theme' ); ?></div>
                <div class="timeline-meta"><?php esc_html_e( 'Institution · Zeitraum', 'hanjo-portfolio-theme' ); ?></div>
                <div class="timeline-desc">
                  <p><?php esc_html_e( 'Kurze Beschreibung, was du dort gemacht bzw. gelernt hast, was für die Stelle relevant ist.', 'hanjo-portfolio-theme' ); ?></p>
                </div>
              </li>
              <li class="timeline-item">
                <div class="timeline-dot"></div>
                <div class="timeline-role"><?php esc_html_e( 'Fortbildungen & Kurse', 'hanjo-portfolio-theme' ); ?></div>
                <div class="timeline-meta"><?php esc_html_e( 'Online / Vor Ort · Auswahl', 'hanjo-portfolio-theme' ); ?></div>
                <div class="timeline-desc">
                  <ul>
                    <li><?php esc_html_e( 'Aktuelle Themen (z. B. KI-gestütztes Design, Performance Marketing, UX Basics).', 'hanjo-portfolio-theme' ); ?></li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </section>

  <section id="portfolio">
    <div class="container">
      <header class="section-header">
        <div>
          <div class="section-kicker"><?php esc_html_e( 'Portfolio', 'hanjo-portfolio-theme' ); ?></div>
          <h2 class="section-title"><?php esc_html_e( 'Ausgewählte Projekte', 'hanjo-portfolio-theme' ); ?></h2>
        </div>
        <p class="section-subtitle"><?php esc_html_e( 'Eine kleine Auswahl – weitere Beispiele und Details kann ich bei Interesse gerne zeigen.', 'hanjo-portfolio-theme' ); ?></p>
      </header>

      <div class="portfolio-grid">
        <?php if ( $projects_query->have_posts() ) : ?>
          <?php while ( $projects_query->have_posts() ) : $projects_query->the_post(); ?>
            <?php
            $project_type   = hpt_get_project_meta( get_the_ID(), 'projekt_type', __( 'Projekt', 'hanjo-portfolio-theme' ) );
            $project_roles  = hpt_parse_list_field( hpt_get_project_meta( get_the_ID(), 'projekt_roles' ) );
            $project_link   = hpt_get_project_meta( get_the_ID(), 'projekt_link_main', get_permalink() );
            $project_client = hpt_get_project_meta( get_the_ID(), 'projekt_client' );
            $project_year   = hpt_get_project_meta( get_the_ID(), 'projekt_year' );
            $skills         = get_the_terms( get_the_ID(), 'project_skill' );
            $skills         = ( $skills && ! is_wp_error( $skills ) ) ? $skills : array();
            ?>
            <article class="project-card">
              <div class="project-tagline"><?php echo esc_html( $project_type ); ?></div>
              <h3 class="project-title"><?php the_title(); ?></h3>
              <p class="project-desc"><?php echo wp_kses_post( get_the_excerpt() ); ?></p>
              <?php if ( ! empty( $skills ) ) : ?>
                <div class="pill-row" style="margin-top:0.15rem;">
                  <?php foreach ( array_slice( $skills, 0, 4 ) as $skill ) : ?>
                    <span class="pill"><?php echo esc_html( $skill->name ); ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <div class="project-meta">
                <span>
                  <?php
                  $meta_pieces = array_filter( array( $project_client, $project_year ) );
                  echo esc_html( implode( ' · ', $meta_pieces ) );
                  ?>
                </span>
                <a href="<?php echo esc_url( $project_link ); ?>" class="project-link" target="_blank" rel="noopener">
                  <?php esc_html_e( 'Details', 'hanjo-portfolio-theme' ); ?>
                </a>
              </div>
            </article>
          <?php endwhile; ?>
        <?php else : ?>
          <article class="project-card">
            <div class="project-tagline"><?php esc_html_e( 'Performance Creative', 'hanjo-portfolio-theme' ); ?></div>
            <h3 class="project-title"><?php esc_html_e( 'Social-Ads Kampagne für Produkt XY', 'hanjo-portfolio-theme' ); ?></h3>
            <p class="project-desc"><?php esc_html_e( 'Entwicklung einer Reihe von statischen und animierten Creatives, optimiert für Meta & TikTok – inklusive Varianten für A/B-Tests.', 'hanjo-portfolio-theme' ); ?></p>
            <div class="pill-row" style="margin-top:0.15rem;">
              <span class="pill"><?php esc_html_e( 'Meta / TikTok Ads', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'Layout & Text', 'hanjo-portfolio-theme' ); ?></span>
              <span class="pill"><?php esc_html_e( 'KI-Ideenfindung', 'hanjo-portfolio-theme' ); ?></span>
            </div>
            <div class="project-meta">
              <span><?php esc_html_e( 'Konzept · Design · Ad-Assets', 'hanjo-portfolio-theme' ); ?></span>
              <a href="#" class="project-link"><?php esc_html_e( 'Details (Platzhalter)', 'hanjo-portfolio-theme' ); ?></a>
            </div>
          </article>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section id="contact">
    <div class="container">
      <header class="section-header">
        <div>
          <div class="section-kicker"><?php esc_html_e( 'Kontakt', 'hanjo-portfolio-theme' ); ?></div>
          <h2 class="section-title"><?php esc_html_e( 'Lust auf ein Gespräch?', 'hanjo-portfolio-theme' ); ?></h2>
        </div>
        <p class="section-subtitle"><?php esc_html_e( 'Am besten bin ich per E-Mail erreichbar. Gerne können wir auch einen kurzen Call vereinbaren.', 'hanjo-portfolio-theme' ); ?></p>
      </header>

      <div class="contact-layout">
        <div class="contact-text">
          <p><?php esc_html_e( 'Wenn Ihnen mein Profil und meine Arbeitsweise zusagen, freue ich mich über eine kurze Nachricht – mit ein paar Stichpunkten zur Rolle, zum Team und zu den Aufgaben.', 'hanjo-portfolio-theme' ); ?></p>
          <p><?php esc_html_e( 'Auf Wunsch kann ich weitere Arbeitsproben, detailliertere Case Studies oder einen tieferen Einblick in konkrete Projekte liefern.', 'hanjo-portfolio-theme' ); ?></p>
        </div>

        <aside class="card">
          <h3 class="card-title"><?php esc_html_e( 'Kontakt & Links', 'hanjo-portfolio-theme' ); ?></h3>
          <ul class="contact-list">
            <li><?php esc_html_e( 'E-Mail:', 'hanjo-portfolio-theme' ); ?> <a href="mailto:<?php echo esc_attr( get_option( 'admin_email' ) ); ?>"><?php echo esc_html( get_option( 'admin_email' ) ); ?></a></li>
            <li><?php esc_html_e( 'Website:', 'hanjo-portfolio-theme' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( home_url( '/' ) ); ?></a></li>
            <li><?php esc_html_e( 'LinkedIn:', 'hanjo-portfolio-theme' ); ?> <a href="#"><?php esc_html_e( 'Profil-Link (Platzhalter)', 'hanjo-portfolio-theme' ); ?></a></li>
            <li><?php esc_html_e( 'Optional:', 'hanjo-portfolio-theme' ); ?> <a href="#"><?php esc_html_e( 'GitHub / Behance / Dribbble', 'hanjo-portfolio-theme' ); ?></a></li>
          </ul>
        </aside>
      </div>
    </div>
  </section>
</main>

<?php
wp_reset_postdata();
get_footer();
