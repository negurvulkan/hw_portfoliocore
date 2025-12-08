<?php
/**
 * About Details widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_About_Details' ) ) {
    /**
     * About details widget class.
     */
    class HPC_Widget_About_Details extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_about_details';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'About – Details', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-bullet-list';
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
                    'label' => __( 'Details', 'hanjo-portfolio-core' ),
                )
            );

            $repeater = new Repeater();
            $repeater->add_control(
                'label',
                array(
                    'label'       => __( 'Label', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );
            $repeater->add_control(
                'text',
                array(
                    'label'       => __( 'Text', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'items',
                array(
                    'label'       => __( 'Einträge', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{{ label }}}',
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
            <aside class="hpc-about-panel">
                <ul class="hpc-about-list">
                    <?php if ( ! empty( $settings['items'] ) ) : ?>
                        <?php foreach ( $settings['items'] as $item ) :
                            if ( empty( $item['label'] ) && empty( $item['text'] ) ) {
                                continue;
                            }
                            ?>
                            <li class="hpc-about-item">
                                <?php if ( ! empty( $item['label'] ) ) : ?>
                                    <span class="hpc-about-label"><?php echo esc_html( $item['label'] ); ?></span>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['text'] ) ) : ?>
                                    <span class="hpc-about-value"><?php echo esc_html( $item['text'] ); ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </aside>
            <?php
        }
    }
}
