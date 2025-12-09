<?php
/**
 * Logo / Tool Grid widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Logo_Grid' ) ) {
    /**
     * Logo grid widget class.
     */
    class HPC_Widget_Logo_Grid extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_logo_grid';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Logo / Tool Grid', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-gallery-grid';
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
                'logos_section',
                array(
                    'label' => __( 'Logos', 'hanjo-portfolio-core' ),
                )
            );

            $repeater = new Repeater();
            $repeater->add_control(
                'image',
                array(
                    'label'   => __( 'Bild', 'hanjo-portfolio-core' ),
                    'type'    => Controls_Manager::MEDIA,
                    'default' => array(
                        'url' => Utils::get_placeholder_image_src(),
                    ),
                )
            );
            $repeater->add_control(
                'label',
                array(
                    'label'       => __( 'Label', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $repeater->add_control(
                'url',
                array(
                    'label'         => __( 'URL', 'hanjo-portfolio-core' ),
                    'type'          => Controls_Manager::URL,
                    'show_external' => true,
                    'dynamic'       => array( 'active' => true ),
                )
            );

            $this->add_control(
                'logos',
                array(
                    'label'       => __( 'Logos', 'hanjo-portfolio-core' ),
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
            <div class="hpc-logo-grid">
                <?php if ( ! empty( $settings['logos'] ) ) : ?>
                    <?php foreach ( $settings['logos'] as $logo ) :
                        $image_url = isset( $logo['image']['url'] ) ? $logo['image']['url'] : '';
                        if ( empty( $image_url ) && empty( $logo['label'] ) ) {
                            continue;
                        }
                        $link_open  = '';
                        $link_close = '';
                        if ( ! empty( $logo['url']['url'] ) ) {
                            $target     = ! empty( $logo['url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                            $link_open  = '<a class="hpc-logo-link" href="' . esc_url( $logo['url']['url'] ) . '"' . $target . '>';
                            $link_close = '</a>';
                        }
                        ?>
                        <div class="hpc-logo-item">
                            <?php echo wp_kses_post( $link_open ); ?>
                            <?php if ( ! empty( $image_url ) ) : ?>
                                <img class="hpc-logo-img" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $logo['label'] ); ?>">
                            <?php endif; ?>
                            <?php if ( ! empty( $logo['label'] ) ) : ?>
                                <div class="hpc-logo-label"><?php echo esc_html( $logo['label'] ); ?></div>
                            <?php endif; ?>
                            <?php echo wp_kses_post( $link_close ); ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}
