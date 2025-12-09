<?php
/**
 * Workflow widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Workflow' ) ) {
    /**
     * Workflow widget class.
     */
    class HPC_Widget_Workflow extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_workflow';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Workflow / Process', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-flow';
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
                'steps_section',
                array(
                    'label' => __( 'Schritte', 'hanjo-portfolio-core' ),
                )
            );

            $repeater = new Repeater();
            $repeater->add_control(
                'title',
                array(
                    'label'       => __( 'Schritt Titel', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $repeater->add_control(
                'description',
                array(
                    'label' => __( 'Beschreibung', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXTAREA,
                    'rows'  => 3,
                    'dynamic' => array( 'active' => true ),
                )
            );
            $repeater->add_control(
                'icon',
                array(
                    'label'       => __( 'Icon / Emoji', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'placeholder' => '💡',
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->add_control(
                'steps',
                array(
                    'label'       => __( 'Workflow Schritte', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $repeater->get_controls(),
                    'title_field' => '{{{ title }}}',
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
            <div class="hpc-workflow">
                <?php if ( ! empty( $settings['steps'] ) ) : ?>
                    <?php foreach ( $settings['steps'] as $step ) :
                        if ( empty( $step['title'] ) && empty( $step['description'] ) && empty( $step['icon'] ) ) {
                            continue;
                        }
                        ?>
                        <div class="hpc-workflow-step">
                            <?php if ( ! empty( $step['icon'] ) ) : ?>
                                <div class="hpc-workflow-step-icon"><?php echo esc_html( $step['icon'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $step['title'] ) ) : ?>
                                <div class="hpc-workflow-step-title"><?php echo esc_html( $step['title'] ); ?></div>
                            <?php endif; ?>
                            <?php if ( ! empty( $step['description'] ) ) : ?>
                                <div class="hpc-workflow-step-text"><?php echo wp_kses_post( $step['description'] ); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}
