<?php
/**
 * Project Grid widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Project_Grid' ) ) {
    /**
     * Project grid widget class.
     */
    class HPC_Widget_Project_Grid extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_project_grid';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Projekt-Grid', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-posts-grid';
        }

        /**
         * Get widget categories.
         */
        public function get_categories() {
            return array( 'hpc_cv_portfolio' );
        }

        /**
         * Register controls.
         */
        protected function register_controls() {
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => __( 'Inhalte', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'posts_per_page',
                array(
                    'label'   => __( 'Anzahl Projekte', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 6,
                )
            );

            $this->add_control(
                'orderby',
                array(
                    'label'   => __( 'Sortieren nach', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'date',
                    'options' => array(
                        'date'             => __( 'Datum', 'hanjo-portfolio-core' ),
                        'meta_value_num'   => __( 'Sortierindex (projekt_sort_order)', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $this->add_control(
                'order',
                array(
                    'label'   => __( 'Reihenfolge', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'DESC',
                    'options' => array(
                        'ASC'  => __( 'Aufsteigend', 'hanjo-portfolio-core' ),
                        'DESC' => __( 'Absteigend', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $categories = $this->get_terms_options( 'project_category' );
            $skills     = $this->get_terms_options( 'project_skill' );

            $this->add_control(
                'filter_categories',
                array(
                    'label'       => __( 'Projektkategorien', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $categories,
                    'multiple'    => true,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'filter_skills',
                array(
                    'label'       => __( 'Skills & Tools', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $skills,
                    'multiple'    => true,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'show_subtitle',
                array(
                    'label'   => __( 'Untertitel anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_roles',
                array(
                    'label'   => __( 'Rollen anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_tools',
                array(
                    'label'   => __( 'Tools anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_excerpt',
                array(
                    'label'   => __( 'Kurztext anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->add_control(
                'show_link',
                array(
                    'label'   => __( 'Link / Button anzeigen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SWITCHER,
                    'default' => 'yes',
                )
            );

            $this->end_controls_section();
        }

        /**
         * Get terms options.
         */
        private function get_terms_options( $taxonomy ) {
            $options = array();
            $terms   = get_terms(
                array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                )
            );
            if ( ! is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options[ $term->term_id ] = $term->name;
                }
            }

            return $options;
        }

        /**
         * Render widget output.
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            $args     = array(
                'post_type'      => 'projekt',
                'posts_per_page' => absint( $settings['posts_per_page'] ),
                'orderby'        => $settings['orderby'],
                'order'          => $settings['order'],
            );

            if ( 'meta_value_num' === $settings['orderby'] ) {
                $args['meta_key'] = 'projekt_sort_order';
            }

            $tax_query = array();
            if ( ! empty( $settings['filter_categories'] ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'project_category',
                    'field'    => 'term_id',
                    'terms'    => array_map( 'intval', $settings['filter_categories'] ),
                );
            }
            if ( ! empty( $settings['filter_skills'] ) ) {
                $tax_query[] = array(
                    'taxonomy' => 'project_skill',
                    'field'    => 'term_id',
                    'terms'    => array_map( 'intval', $settings['filter_skills'] ),
                );
            }
            if ( ! empty( $tax_query ) ) {
                $args['tax_query'] = $tax_query;
            }

            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {
                echo '<div class="hpc-project-grid">';
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $this->render_card( get_the_ID() );
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p>' . esc_html__( 'Keine Projekte gefunden.', 'hanjo-portfolio-core' ) . '</p>';
            }
        }

        /**
         * Render single card.
         */
        private function render_card( $post_id ) {
            $settings = $this->get_settings_for_display();
            $subtitle = get_post_meta( $post_id, 'projekt_subtitle', true );
            $roles    = get_post_meta( $post_id, 'projekt_roles', true );
            $tools    = get_post_meta( $post_id, 'projekt_tools', true );
            $type     = get_post_meta( $post_id, 'projekt_type', true );
            $main_url = get_post_meta( $post_id, 'projekt_link_main', true );

            $target_url = $main_url ? $main_url : get_permalink( $post_id );

            echo '<article class="hpc-project-card">';
            echo '<h3 class="hpc-project-title">' . esc_html( get_the_title( $post_id ) ) . '</h3>';
            if ( 'yes' === $settings['show_subtitle'] && $subtitle ) {
                echo '<p class="hpc-project-subtitle">' . esc_html( $subtitle ) . '</p>';
            }

            if ( $type ) {
                echo '<p class="hpc-project-type">' . esc_html( $type ) . '</p>';
            } else {
                $terms = get_the_term_list( $post_id, 'project_category', '', ', ' );
                if ( $terms ) {
                    echo '<p class="hpc-project-type">' . wp_kses_post( $terms ) . '</p>';
                }
            }

            if ( 'yes' === $settings['show_roles'] && $roles ) {
                echo '<p class="hpc-project-roles">' . esc_html( $roles ) . '</p>';
            }

            if ( 'yes' === $settings['show_excerpt'] ) {
                $excerpt = get_the_excerpt( $post_id );
                echo '<p class="hpc-project-excerpt">' . esc_html( $excerpt ) . '</p>';
            }

            if ( 'yes' === $settings['show_tools'] && $tools ) {
                echo '<p class="hpc-project-tools">' . esc_html( $tools ) . '</p>';
            }

            if ( 'yes' === $settings['show_link'] ) {
                echo '<a class="hpc-project-link" href="' . esc_url( $target_url ) . '">' . esc_html__( 'Details ansehen', 'hanjo-portfolio-core' ) . '</a>';
            }

            echo '</article>';
        }
    }
}
