<?php
/**
 * Elementor integration.
 *
 * @package Hanjo_Portfolio_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Elementor' ) ) {
    /**
     * Elementor integration class.
     */
    class HPC_Elementor {
        /**
         * Instance.
         *
         * @var HPC_Elementor
         */
        private static $instance;

        /**
         * Get instance.
         *
         * @return HPC_Elementor
         */
        public static function get_instance() {
            if ( null === self::$instance ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor.
         */
        private function __construct() {
            // Hook after plugins are loaded so Elementor can initialize, but early enough
            // that the widgets are registered before the editor loads.
            add_action( 'init', array( $this, 'init' ) );
        }

        /**
         * Init Elementor hooks.
         */
        public function init() {
            if ( ! did_action( 'elementor/loaded' ) ) {
                add_action( 'admin_notices', array( $this, 'admin_notice_missing_elementor' ) );
                return;
            }

            add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
            add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
        }

        /**
         * Admin notice if Elementor is missing.
         */
        public function admin_notice_missing_elementor() {
            echo '<div class="notice notice-warning"><p>' . esc_html__( 'Hanjo Portfolio Core benötigt Elementor, um die Widgets zu laden.', 'hanjo-portfolio-core' ) . '</p></div>';
        }

        /**
         * Register Elementor category.
         */
        public function register_category( $elements_manager ) {
            $elements_manager->add_category(
                'hpc_cv_portfolio',
                array(
                    'title' => __( 'Hanjo CV & Portfolio', 'hanjo-portfolio-core' ),
                    'icon'  => 'fa fa-plug',
                )
            );
        }

        /**
         * Register custom widgets.
         */
        public function register_widgets( $widgets_manager ) {
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-project-grid.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-project-highlight.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-experience-timeline.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-skill-chips.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-hero.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-section-header.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-about-text.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-about-details.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-cv-sidebar.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-facts.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-workflow.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-cta-banner.php';
            require_once HPC_PLUGIN_DIR . 'includes/elementor-widgets/class-hpc-widget-logo-grid.php';

            $widgets_manager->register( new \HPC_Widget_Project_Grid() );
            $widgets_manager->register( new \HPC_Widget_Project_Highlight() );
            $widgets_manager->register( new \HPC_Widget_Experience_Timeline() );
            $widgets_manager->register( new \HPC_Widget_Skill_Chips() );
            $widgets_manager->register( new \HPC_Widget_Hero() );
            $widgets_manager->register( new \HPC_Widget_Section_Header() );
            $widgets_manager->register( new \HPC_Widget_About_Text() );
            $widgets_manager->register( new \HPC_Widget_About_Details() );
            $widgets_manager->register( new \HPC_Widget_CV_Sidebar() );
            $widgets_manager->register( new \HPC_Widget_Facts() );
            $widgets_manager->register( new \HPC_Widget_Workflow() );
            $widgets_manager->register( new \HPC_Widget_CTA_Banner() );
            $widgets_manager->register( new \HPC_Widget_Logo_Grid() );
        }
    }
}
