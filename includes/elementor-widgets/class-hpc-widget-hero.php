<?php
/**
 * Hero widget for portfolio.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_Hero' ) ) {
    /**
     * Hero widget class.
     */
    class HPC_Widget_Hero extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_hero';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'Hero – Portfolio', 'hanjo-portfolio-core' );
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
                'kicker',
                array(
                    'label'       => __( 'Kicker', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->add_control(
                'title',
                array(
                    'label'       => __( 'Titel', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->add_control(
                'subtitle',
                array(
                    'label' => __( 'Untertitel', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXTAREA,
                    'rows'  => 4,
                    'dynamic' => array( 'active' => true ),
                )
            );

            $buttons_repeater = new Repeater();
            $buttons_repeater->add_control(
                'btn_text',
                array(
                    'label'       => __( 'Button Text', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $buttons_repeater->add_control(
                'btn_url',
                array(
                    'label'         => __( 'Button URL', 'hanjo-portfolio-core' ),
                    'type'          => Controls_Manager::URL,
                    'show_external' => true,
                    'placeholder'   => __( 'https://dein-link.de', 'hanjo-portfolio-core' ),
                    'dynamic'       => array( 'active' => true ),
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

            $tags_repeater = new Repeater();
            $tags_repeater->add_control(
                'tag_label',
                array(
                    'label'       => __( 'Tag', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->add_control(
                'tags',
                array(
                    'label'       => __( 'Tags / Fokus', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $tags_repeater->get_controls(),
                    'title_field' => '{{{ tag_label }}}',
                )
            );

            $this->add_control(
                'variant',
                array(
                    'label'        => __( 'Dunkle Variante', 'hanjo-portfolio-core' ),
                    'type'         => Controls_Manager::SWITCHER,
                    'label_on'     => __( 'Dunkel', 'hanjo-portfolio-core' ),
                    'label_off'    => __( 'Hell', 'hanjo-portfolio-core' ),
                    'return_value' => 'dark',
                    'default'      => 'dark',
                )
            );

            $this->end_controls_section();
        }

        /**
         * Render widget output.
         */
        protected function render() {
            $settings = $this->get_settings_for_display();
            $variant  = ! empty( $settings['variant'] ) ? ' hpc-hero--' . sanitize_html_class( $settings['variant'] ) : '';
            ?>
            <section class="hpc-hero<?php echo esc_attr( $variant ); ?>">
                <?php if ( ! empty( $settings['kicker'] ) ) : ?>
                    <div class="hpc-hero-kicker"><?php echo esc_html( $settings['kicker'] ); ?></div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['title'] ) ) : ?>
                    <h1 class="hpc-hero-title"><?php echo wp_kses_post( $settings['title'] ); ?></h1>
                <?php endif; ?>

                <?php if ( ! empty( $settings['subtitle'] ) ) : ?>
                    <div class="hpc-hero-subtitle"><?php echo wp_kses_post( $settings['subtitle'] ); ?></div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['buttons'] ) ) : ?>
                    <div class="hpc-hero-actions">
                        <?php foreach ( $settings['buttons'] as $button ) :
                            $url     = isset( $button['btn_url']['url'] ) ? $button['btn_url']['url'] : '';
                            $target  = ! empty( $button['btn_url']['is_external'] ) ? ' target="_blank" rel="noopener noreferrer"' : '';
                            $style   = ( isset( $button['btn_style'] ) && 'ghost' === $button['btn_style'] ) ? ' hpc-btn-ghost' : ' hpc-btn-primary';
                            if ( empty( $button['btn_text'] ) ) {
                                continue;
                            }
                            ?>
                            <a class="hpc-btn<?php echo esc_attr( $style ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo wp_kses_post( $target ); ?>><?php echo esc_html( $button['btn_text'] ); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['tags'] ) ) : ?>
                    <div class="hpc-hero-tags">
                        <?php foreach ( $settings['tags'] as $tag ) :
                            if ( empty( $tag['tag_label'] ) ) {
                                continue;
                            }
                            ?>
                            <span class="hpc-hero-tag"><?php echo esc_html( $tag['tag_label'] ); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
            <?php
        }
    }
}
