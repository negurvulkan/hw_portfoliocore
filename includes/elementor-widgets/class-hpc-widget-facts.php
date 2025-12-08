<?php
/**
 * Facts widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Facts' ) ) {
    /**
     * Facts widget class.
     */
    class HPC_Widget_Facts extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_facts';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Facts / Metrics', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-counter-circle';
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
                'facts_section',
                array(
                    'label' => __( 'Fakten', 'hanjo-portfolio-core' ),
                )
            );

            $repeater = new Repeater();
            $repeater->add_control(
                'value',
                array(
                    'label'       => __( 'Wert / Zahl', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );
            $repeater->add_control(
                'label',
                array(
                    'label'       => __( 'Label', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'facts',
                array(
                    'label'       => __( 'Fakten', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{{ value }}}',
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
            <div class="hpc-facts">
                <?php if ( ! empty( $settings['facts'] ) ) : ?>
                    <?php foreach ( $settings['facts'] as $fact ) :
                        if ( empty( $fact['value'] ) && empty( $fact['label'] ) ) {
                            continue;
                        }
                        ?>
                        <div class="hpc-fact">
                            <?php if ( ! empty( $fact['value'] ) ) : ?>
                                <div class="hpc-fact-value"><?php echo esc_html( $fact['value'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $fact['label'] ) ) : ?>
                                <div class="hpc-fact-label"><?php echo esc_html( $fact['label'] ); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}
