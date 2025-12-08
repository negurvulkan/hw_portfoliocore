<?php
/**
 * CTA Banner widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_CTA_Banner' ) ) {
    /**
     * CTA Banner widget class.
     */
    class HPC_Widget_CTA_Banner extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_cta_banner';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'CTA Banner', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-call-to-action';
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
                'title',
                array(
                    'label'       => __( 'Titel', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );

            $this->add_control(
                'description',
                array(
                    'label' => __( 'Beschreibung', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXTAREA,
                    'rows'  => 4,
                )
            );

            $buttons_repeater = new Repeater();
            $buttons_repeater->add_control(
                'btn_text',
                array(
                    'label'       => __( 'Button Text', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                )
            );
            $buttons_repeater->add_control(
                'btn_url',
                array(
                    'label'         => __( 'Button URL', 'hanjo-portfolio-core' ),
                    'type'          => Controls_Manager::URL,
                    'show_external' => true,
                )
            );
            $buttons_repeater->add_control(
                'btn_style',
                array(
                    'label'   => __( 'Stil', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'primary',
                    'options' => array(
                        'primary' => __( 'Primary', 'hanjo-portfolio-core' ),
                        'ghost'   => __( 'Ghost', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $this->add_control(
                'buttons',
                array(
                    'label'       => __( 'Buttons', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $buttons_repeater->get_controls(),
                    'title_field' => '{{{ btn_text }}}',
                )
            );

            $this->add_control(
                'background_style',
                array(
                    'label'   => __( 'Hintergrund', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::SELECT,
                    'default' => 'dark',
                    'options' => array(
                        'dark'   => __( 'Dunkel', 'hanjo-portfolio-core' ),
                        'accent' => __( 'Akzent', 'hanjo-portfolio-core' ),
                        'soft'   => __( 'Soft', 'hanjo-portfolio-core' ),
                    ),
                )
            );

            $this->end_controls_section();
        }

        /**
         * Render widget output.
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            $style    = ! empty( $settings['background_style'] ) ? ' hpc-cta--' . sanitize_html_class( $settings['background_style'] ) : '';
            ?>
            <section class="hpc-cta<?php echo esc_attr( $style ); ?>">
                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h2 class="hpc-cta-title"><?php echo esc_html( $settings['title'] ); ?></h2>
                <?php endif; ?>

                <?php if ( ! empty( $settings['description'] ) ) : ?>
                    <div class="hpc-cta-text"><?php echo wp_kses_post( $settings['description'] ); ?></div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['buttons'] ) ) : ?>
                    <div class="hpc-cta-actions">
                        <?php foreach ( $settings['buttons'] as $button ) :
                            if ( empty( $button['btn_text'] ) ) {
                                continue;
                            }
                            $url    = isset( $button['btn_url']['url'] ) ? $button['btn_url']['url'] : '';
                            $target = ! empty( $button['btn_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                            $style_class = ( isset( $button['btn_style'] ) && 'ghost' === $button['btn_style'] ) ? ' hpc-btn-ghost' : ' hpc-btn-primary';
                            ?>
                            <a class="hpc-btn<?php echo esc_attr( $style_class ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo wp_kses_post( $target ); ?>><?php echo esc_html( $button['btn_text'] ); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            <?php
        }
    }
}
