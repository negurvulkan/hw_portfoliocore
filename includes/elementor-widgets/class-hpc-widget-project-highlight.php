<?php
/**
 * Project Highlight widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Project_Highlight' ) ) {
    /**
     * Highlight widget class.
     */
    class HPC_Widget_Project_Highlight extends Widget_Base {
        public function get_name() {
            return 'hpc_project_highlight';
        }

        public function get_title() {
            return __( 'Projekt-Highlight', 'hanjo-portfolio-core' );
        }

        public function get_icon() {
            return 'eicon-featured-image';
        }

        public function get_categories() {
            return array( 'hpc_cv_portfolio' );
        }

        protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => __( 'Highlight', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'project_id',
                array(
                    'label'       => __( 'Projekt auswählen', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $this->get_project_options(),
                    'multiple'    => false,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'show_image',
                array(
                    'label'   => __( 'Bild anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_goal',
                array(
                    'label'   => __( 'Ziel anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_result',
                array(
                    'label'   => __( 'Ergebnis anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'link_to_main',
                array(
                    'label'       => __( 'Auf Hauptlink verweisen', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::SWITCHER,
                    'description' => __( 'Wenn aktiv, wird auf projekt_link_main verlinkt, sonst auf den Einzelbeitrag.', 'hanjo-portfolio-core' ),
                    'default'     => 'yes',
                )
            );

            $this->end_controls_section();
        }

        private function get_project_options() {
            $options = array();
            $posts   = get_posts(
                array(
                    'post_type'      => 'projekt',
                    'posts_per_page' => 100,
                )
            );
            foreach ( $posts as $post ) {
                $options[ $post->ID ] = $post->post_title;
            }

            return $options;
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $post_id  = ! empty( $settings['project_id'] ) ? absint( $settings['project_id'] ) : $this->get_featured_project();
            if ( ! $post_id ) {
                echo '<p>' . esc_html__( 'Kein Projekt gefunden.', 'hanjo-portfolio-core' ) . '</p>';
                return;
            }

            $subtitle = get_post_meta( $post_id, 'projekt_subtitle', true );
            $type     = get_post_meta( $post_id, 'projekt_type', true );
            $goal     = get_post_meta( $post_id, 'projekt_goal', true );
            $what     = get_post_meta( $post_id, 'projekt_what_i_did', true );
            $result   = get_post_meta( $post_id, 'projekt_result', true );
            $roles    = get_post_meta( $post_id, 'projekt_roles', true );
            $tools    = get_post_meta( $post_id, 'projekt_tools', true );
            $link     = get_post_meta( $post_id, 'projekt_link_main', true );

            $target_url = ( 'yes' === $settings['link_to_main'] && $link ) ? $link : get_permalink( $post_id );

            echo '<section class="hpc-project-highlight">';
            echo '<header class="hpc-highlight-header">';
            echo '<h2 class="hpc-highlight-title">' . esc_html( get_the_title( $post_id ) ) . '</h2>';
            if ( $subtitle ) {
                echo '<p class="hpc-highlight-subtitle">' . esc_html( $subtitle ) . '</p>';
            }
            echo '</header>';

            if ( $type ) {
                echo '<p class="hpc-highlight-type">' . esc_html( $type ) . '</p>';
            } else {
                $terms = get_the_term_list( $post_id, 'project_category', '', ', ' );
                if ( $terms ) {
                    echo '<p class="hpc-highlight-type">' . wp_kses_post( $terms ) . '</p>';
                }
            }

            echo '<div class="hpc-highlight-content">';
            if ( 'yes' === $settings['show_image'] ) {
                $image_html = $this->get_project_image( $post_id );
                if ( $image_html ) {
                    echo '<div class="hpc-highlight-image">' . $image_html . '</div>';
                }
            }

            echo '<div class="hpc-highlight-text">';
            if ( 'yes' === $settings['show_goal'] && $goal ) {
                echo '<h4>' . esc_html__( 'Ziel', 'hanjo-portfolio-core' ) . '</h4>';
                echo '<p>' . esc_html( $goal ) . '</p>';
            }

            if ( $what ) {
                echo '<h4>' . esc_html__( 'Meine Rolle', 'hanjo-portfolio-core' ) . '</h4>';
                echo '<p>' . esc_html( $what ) . '</p>';
            }

            if ( 'yes' === $settings['show_result'] && $result ) {
                echo '<h4>' . esc_html__( 'Ergebnis', 'hanjo-portfolio-core' ) . '</h4>';
                echo '<p>' . esc_html( $result ) . '</p>';
            }

            if ( $roles ) {
                echo '<p class="hpc-highlight-roles">' . esc_html( $roles ) . '</p>';
            }

            if ( $tools ) {
                echo '<p class="hpc-highlight-tools">' . esc_html( $tools ) . '</p>';
            }

            echo '<p><a class="hpc-highlight-link" href="' . esc_url( $target_url ) . '">' . esc_html__( 'Details ansehen', 'hanjo-portfolio-core' ) . '</a></p>';
            echo '</div>'; // text.
            echo '</div>'; // content.
            echo '</section>';
        }

        private function get_featured_project() {
            $featured = get_posts(
                array(
                    'post_type'      => 'projekt',
                    'posts_per_page' => 1,
                    'meta_key'       => 'projekt_is_featured',
                    'meta_value'     => 1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                )
            );
            if ( ! empty( $featured ) ) {
                return $featured[0]->ID;
            }

            $latest = get_posts(
                array(
                    'post_type'      => 'projekt',
                    'posts_per_page' => 1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                )
            );
            if ( ! empty( $latest ) ) {
                return $latest[0]->ID;
            }

            return 0;
        }

        private function get_project_image( $post_id ) {
            if ( has_post_thumbnail( $post_id ) ) {
                return get_the_post_thumbnail( $post_id, 'large', array( 'class' => 'hpc-highlight-thumb' ) );
            }

            $gallery = get_post_meta( $post_id, 'projekt_gallery', true );
            if ( ! empty( $gallery ) && is_array( $gallery ) ) {
                $first_id = absint( $gallery[0] );
                return wp_get_attachment_image( $first_id, 'large', false, array( 'class' => 'hpc-highlight-thumb' ) );
            }

            return '';
        }
    }
}
