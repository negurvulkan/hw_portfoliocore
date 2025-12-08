<?php
/**
 * Experience Timeline widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Experience_Timeline' ) ) {
    /**
     * Timeline widget.
     */
    class HPC_Widget_Experience_Timeline extends Widget_Base {
        public function get_name() {
            return 'hpc_experience_timeline';
        }

        public function get_title() {
            return __( 'Erfahrungs-Timeline', 'hanjo-portfolio-core' );
        }

        public function get_icon() {
            return 'eicon-time-line';
        }

        public function get_categories() {
            return array( 'hpc_cv_portfolio' );
        }

        protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => __( 'Timeline', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'limit',
                array(
                    'label'   => __( 'Anzahl Stationen', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::NUMBER,
                    'default' => 10,
                )
            );

            $this->add_control(
                'order_by',
                array(
                    'label'   => __( 'Sortieren nach', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'meta_value',
                    'options' => array(
                        'meta_value'   => __( 'Startdatum', 'hanjo-portfolio-core' ),
                        'meta_value_num' => __( 'Sortierindex', 'hanjo-portfolio-core' ),
                        'date'         => __( 'Beitragsdatum', 'hanjo-portfolio-core' ),
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

            $this->add_control(
                'filter_type',
                array(
                    'label'       => __( 'Typ-Filter (kommagetrennt)', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'placeholder' => __( 'Job, Ausbildung', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'status_filter',
                array(
                    'label'   => __( 'Status', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'all',
                    'options' => array(
                        'all'     => __( 'Alle', 'hanjo-portfolio-core' ),
                        'current' => __( 'Nur aktuell', 'hanjo-portfolio-core' ),
                        'past'    => __( 'Nur vergangen', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $this->end_controls_section();
        }

        protected function render() {
            $settings = $this->get_settings_for_display();
            $meta_key = 'experience_start_date';
            if ( 'meta_value_num' === $settings['order_by'] ) {
                $meta_key = 'experience_order';
            }

            $meta_query = array();
            if ( 'current' === $settings['status_filter'] ) {
                $meta_query[] = array(
                    'key'   => 'experience_is_current',
                    'value' => 1,
                );
            } elseif ( 'past' === $settings['status_filter'] ) {
                $meta_query[] = array(
                    'key'   => 'experience_is_current',
                    'value' => 0,
                );
            }

            $args = array(
                'post_type'      => 'experience',
                'posts_per_page' => absint( $settings['limit'] ),
                'orderby'        => $settings['order_by'],
                'order'          => $settings['order'],
                'meta_key'       => $meta_key,
            );

            if ( ! empty( $meta_query ) ) {
                $args['meta_query'] = $meta_query;
            }

            if ( ! empty( $settings['filter_type'] ) ) {
                $types            = array_map( 'trim', explode( ',', $settings['filter_type'] ) );
                $meta_query_types = array(
                    'relation' => 'OR',
                );
                foreach ( $types as $type ) {
                    $meta_query_types[] = array(
                        'key'     => 'experience_type',
                        'value'   => $type,
                        'compare' => 'LIKE',
                    );
                }
                $args['meta_query'] = isset( $args['meta_query'] ) ? array_merge( $args['meta_query'], array( $meta_query_types ) ) : array( $meta_query_types );
            }

            $query = new WP_Query( $args );
            if ( $query->have_posts() ) {
                echo '<div class="hpc-experience-timeline">';
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $this->render_item( get_the_ID() );
                }
                echo '</div>';
                wp_reset_postdata();
            } else {
                echo '<p>' . esc_html__( 'Keine Stationen gefunden.', 'hanjo-portfolio-core' ) . '</p>';
            }
        }

        private function render_item( $post_id ) {
            $org        = get_post_meta( $post_id, 'experience_org', true );
            $location   = get_post_meta( $post_id, 'experience_location', true );
            $start      = get_post_meta( $post_id, 'experience_start_date', true );
            $end        = get_post_meta( $post_id, 'experience_end_date', true );
            $is_current = get_post_meta( $post_id, 'experience_is_current', true );
            $desc       = get_post_meta( $post_id, 'experience_short_desc', true );
            $bullets    = get_post_meta( $post_id, 'experience_bullets', true );
            $type       = get_post_meta( $post_id, 'experience_type', true );

            $timeframe = $start;
            if ( $is_current ) {
                $timeframe .= ' – ' . __( 'seit', 'hanjo-portfolio-core' );
            } elseif ( $end ) {
                $timeframe .= ' – ' . $end;
            }

            echo '<article class="hpc-timeline-item">';
            echo '<div class="hpc-timeline-marker"></div>';
            echo '<div class="hpc-timeline-content">';
            if ( $type ) {
                echo '<p class="hpc-experience-type">' . esc_html( $type ) . '</p>';
            }
            echo '<h3 class="hpc-experience-title">' . esc_html( get_the_title( $post_id ) ) . '</h3>';
            echo '<p class="hpc-experience-meta">';
            echo esc_html( $org );
            if ( $timeframe ) {
                echo ' · ' . esc_html( $timeframe );
            }
            if ( $location ) {
                echo ' · ' . esc_html( $location );
            }
            echo '</p>';

            if ( $desc ) {
                echo '<p class="hpc-experience-desc">' . esc_html( $desc ) . '</p>';
            } else {
                echo '<p class="hpc-experience-desc">' . esc_html( wp_trim_words( get_the_content( null, false, $post_id ), 30 ) ) . '</p>';
            }

            if ( $bullets ) {
                $lines = array_filter( array_map( 'trim', explode( "\n", $bullets ) ) );
                if ( ! empty( $lines ) ) {
                    echo '<ul class="hpc-experience-bullets">';
                    foreach ( $lines as $line ) {
                        echo '<li>' . esc_html( $line ) . '</li>';
                    }
                    echo '</ul>';
                }
            }

            echo '</div>';
            echo '</article>';
        }
    }
}
