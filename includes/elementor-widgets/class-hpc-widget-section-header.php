<?php
/**
 * Section Header widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Section_Header' ) ) {
    /**
     * Section header widget class.
     */
    class HPC_Widget_Section_Header extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_section_header';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Section Header', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-heading';
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
        protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
            $this->start_controls_section(
                'content_section',
                array(
                    'label' => __( 'Inhalte', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'kicker',
                array(
                    'label'       => __( 'Kicker', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'title',
                array(
                    'label'       => __( 'Titel', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'subtitle',
                array(
                    'label' => __( 'Untertitel', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXTAREA,
                    'rows'  => 3,
                )
            );

            $this->add_responsive_control(
                'alignment',
                array(
                    'label'   => __( 'Ausrichtung', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::CHOOSE,
                    'options' => array(
                        'left'   => array(
                            'title' => __( 'Links', 'hanjo-portfolio-core' ),
                            'icon'  => 'eicon-text-align-left',
                        ),
                        'center' => array(
                            'title' => __( 'Zentriert', 'hanjo-portfolio-core' ),
                            'icon'  => 'eicon-text-align-center',
                        ),
                    ),
                    'default' => 'left',
                    'toggle'  => true,
                )
            );

            $this->end_controls_section();
        }

        /**
         * Render widget output.
         */
        protected function render() {
            $settings   = $this->get_settings_for_display();
            $alignment  = ! empty( $settings['alignment'] ) ? $settings['alignment'] : 'left';
            $align_class = 'hpc-section-header--' . sanitize_html_class( $alignment );
            ?>
            <header class="hpc-section-header <?php echo esc_attr( $align_class ); ?>">
                <?php if ( ! empty( $settings['kicker'] ) ) : ?>
                    <div class="hpc-section-kicker"><?php echo esc_html( $settings['kicker'] ); ?></div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="hpc-section-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <div class="hpc-section-subtitle"><?php echo wp_kses_post( $settings['subtitle'] ); ?></div>
                <?php endif; ?>
            </header>
            <?php
        }
    }
}
