<?php
/**
 * Skill Chips widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Skill_Chips' ) ) {
    /**
     * Skill chips widget class.
     */
    class HPC_Widget_Skill_Chips extends Widget_Base {
        public function get_name() {
            return 'hpc_skill_chips';
        }

        public function get_title() {
            return __( 'Skill-Badges', 'hanjo-portfolio-core' );
        }

        public function get_icon() {
            return 'eicon-skill-bar';
        }

        public function get_categories() {
            return array( 'hpc_cv_portfolio' );
        }

        protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => __( 'Inhalte', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'source',
                array(
                    'label'   => __( 'Quelle', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'repeater',
                    'options' => array(
                        'repeater' => __( 'Manuelle Liste', 'hanjo-portfolio-core' ),
                        'terms'    => __( 'Taxonomie project_skill', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $repeater = new Repeater();
            $repeater->add_control(
                'label',
                array(
                    'label'   => __( 'Label', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => __( 'Skill', 'hanjo-portfolio-core' ),
                    'dynamic' => array( 'active' => true ),
                )
            );
            $repeater->add_control(
                'group',
                array(
                    'label' => __( 'Gruppe', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXT,
                    'dynamic' => array( 'active' => true ),
                )
            );

            $this->add_control(
                'chips',
                array(
                    'label'       => __( 'Chips', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{{ label }}}',
                    'condition'   => array( 'source' => 'repeater' ),
                )
            );

            $this->add_control(
                'terms_filter',
                array(
                    'label'       => __( 'Skills auswählen', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::SELECT2,
                    'options'     => $this->get_terms_options(),
                    'multiple'    => true,
                    'label_block' => true,
                    'condition'   => array( 'source' => 'terms' ),
                )
            );

            $this->end_controls_section();
        }

        private function get_terms_options() {
            $options = array();
            $terms   = get_terms(
                array(
                    'taxonomy'   => 'project_skill',
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

        protected function render() {
            $settings = $this->get_settings_for_display();
            echo '<div class="hpc-skill-chips">';
            if ( 'terms' === $settings['source'] ) {
                $this->render_terms( $settings );
            } else {
                $this->render_repeater( $settings );
            }
            echo '</div>';
        }

        private function render_terms( $settings ) {
            $args = array(
                'taxonomy'   => 'project_skill',
                'hide_empty' => false,
            );
            if ( ! empty( $settings['terms_filter'] ) ) {
                $args['include'] = array_map( 'intval', $settings['terms_filter'] );
            }

            $terms = get_terms( $args );
            if ( is_wp_error( $terms ) || empty( $terms ) ) {
                echo '<p>' . esc_html__( 'Keine Skills gefunden.', 'hanjo-portfolio-core' ) . '</p>';
                return;
            }

            foreach ( $terms as $term ) {
                echo '<span class="hpc-skill-chip">' . esc_html( $term->name );
                if ( ! empty( $term->description ) ) {
                    echo '<small class="hpc-skill-group">' . esc_html( $term->description ) . '</small>';
                }
                echo '</span> ';
            }
        }

        private function render_repeater( $settings ) {
            if ( empty( $settings['chips'] ) ) {
                echo '<p>' . esc_html__( 'Keine Skills definiert.', 'hanjo-portfolio-core' ) . '</p>';
                return;
            }
            foreach ( $settings['chips'] as $chip ) {
                echo '<span class="hpc-skill-chip">' . esc_html( $chip['label'] );
                if ( ! empty( $chip['group'] ) ) {
                    echo '<small class="hpc-skill-group">' . esc_html( $chip['group'] ) . '</small>';
                }
                echo '</span> ';
            }
        }
    }
}
