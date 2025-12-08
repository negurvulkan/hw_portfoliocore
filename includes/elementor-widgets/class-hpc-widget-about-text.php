<?php
/**
 * About Text widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_About_Text' ) ) {
    /**
     * About text widget class.
     */
    class HPC_Widget_About_Text extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_about_text';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'About – Text', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-editor-paragraph';
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
                'heading',
                array(
                    'label'       => __( 'Kleine Überschrift', 'hanjo-portfolio-core' ),
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
                'content',
                array(
                    'label' => __( 'Text', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::WYSIWYG,
                )
            );

            $this->end_controls_section();
        }

        /**
         * Render widget output.
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            ?>
            <div class="hpc-about-text">
                <?php if ( ! empty( $settings['heading'] ) ) : ?>
                    <div class="hpc-about-heading"><?php echo esc_html( $settings['heading'] ); ?></div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h3 class="hpc-about-title"><?php echo esc_html( $settings['title'] ); ?></h3>
                <?php endif; ?>

                <?php if ( ! empty( $settings['content'] ) ) : ?>
                    <div class="hpc-about-body"><?php echo wp_kses_post( $settings['content'] ); ?></div>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}
