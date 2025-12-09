<?php
/**
 * CV Sidebar widget.
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Widget_CV_Sidebar' ) ) {
    /**
     * CV sidebar widget class.
     */
    class HPC_Widget_CV_Sidebar extends Widget_Base {
        /**
         * Get widget name.
         */
        public function get_name() {
            return 'hpc_cv_sidebar';
        }

        /**
         * Get widget title.
         */
        public function get_title() {
            return __( 'CV – Sidebar', 'hanjo-portfolio-core' );
        }

        /**
         * Get widget icon.
         */
        public function get_icon() {
            return 'eicon-sidebar';
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
                'contact_section',
                array(
                    'label' => __( 'Kontakt', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'name',
                array(
                    'label'       => __( 'Name', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $this->add_control(
                'location',
                array(
                    'label'       => __( 'Ort', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $this->add_control(
                'email',
                array(
                    'label'       => __( 'E-Mail', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $this->add_control(
                'website',
                array(
                    'label'       => __( 'Website URL', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );
            $this->add_control(
                'linkedin',
                array(
                    'label'       => __( 'LinkedIn URL', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->end_controls_section();

            $skills_repeater = new Repeater();
            $skills_repeater->add_control(
                'label',
                array(
                    'label'       => __( 'Skill', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->start_controls_section(
                'skills_section',
                array(
                    'label' => __( 'Skills', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'skills',
                array(
                    'label'       => __( 'Skill Pills', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $skills_repeater->get_controls(),
                    'title_field' => '{{{ label }}}',
                )
            );

            $this->end_controls_section();

            $tools_repeater = new Repeater();
            $tools_repeater->add_control(
                'label',
                array(
                    'label'       => __( 'Tool', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::TEXT,
                    'label_block' => true,
                    'dynamic'     => array( 'active' => true ),
                )
            );

            $this->start_controls_section(
                'tools_section',
                array(
                    'label' => __( 'Tools', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'tools',
                array(
                    'label'       => __( 'Tool Pills', 'hanjo-portfolio-core' ),
                    'type'        => Controls_Manager::REPEATER,
                    'fields'      => $tools_repeater->get_controls(),
                    'title_field' => '{{{ label }}}',
                )
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'workstyle_section',
                array(
                    'label' => __( 'Arbeitsweise', 'hanjo-portfolio-core' ),
                )
            );

            $this->add_control(
                'workstyle',
                array(
                    'label' => __( 'Kurztext', 'hanjo-portfolio-core' ),
                    'type'  => Controls_Manager::TEXTAREA,
                    'rows'  => 4,
                    'dynamic' => array( 'active' => true ),
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
            <aside class="hpc-cv-sidebar card">
                <div class="hpc-cv-contact">
                    <?php if ( ! empty( $settings['name'] ) ) : ?>
                        <div class="hpc-cv-name"><?php echo esc_html( $settings['name'] ); ?></div>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['location'] ) ) : ?>
                        <div class="hpc-cv-location"><?php echo esc_html( $settings['location'] ); ?></div>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['email'] ) ) : ?>
                        <div class="hpc-cv-email"><a href="mailto:<?php echo esc_attr( $settings['email'] ); ?>"><?php echo esc_html( $settings['email'] ); ?></a></div>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['website'] ) ) : ?>
                        <div class="hpc-cv-website"><a href="<?php echo esc_url( $settings['website'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $settings['website'] ); ?></a></div>
                    <?php endif; ?>
                    <?php if ( ! empty( $settings['linkedin'] ) ) : ?>
                        <div class="hpc-cv-linkedin"><a href="<?php echo esc_url( $settings['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $settings['linkedin'] ); ?></a></div>
                    <?php endif; ?>
                </div>

                <?php if ( ! empty( $settings['skills'] ) ) : ?>
                    <div class="hpc-cv-skills">
                        <div class="hpc-pill-row">
                            <?php foreach ( $settings['skills'] as $skill ) :
                                if ( empty( $skill['label'] ) ) {
                                    continue;
                                }
                                ?>
                                <span class="hpc-pill"><?php echo esc_html( $skill['label'] ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['tools'] ) ) : ?>
                    <div class="hpc-cv-tools">
                        <div class="hpc-pill-row">
                            <?php foreach ( $settings['tools'] as $tool ) :
                                if ( empty( $tool['label'] ) ) {
                                    continue;
                                }
                                ?>
                                <span class="hpc-pill"><?php echo esc_html( $tool['label'] ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $settings['workstyle'] ) ) : ?>
                    <div class="hpc-cv-workstyle"><?php echo wp_kses_post( $settings['workstyle'] ); ?></div>
                <?php endif; ?>
            </aside>
            <?php
        }
    }
}
