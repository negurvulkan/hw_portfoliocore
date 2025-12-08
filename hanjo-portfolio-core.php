<?php
/**
 * Plugin Name: Hanjo Portfolio Core
 * Description: Custom post types, meta fields, and Elementor widgets for a CV & portfolio site.
 * Version: 1.2.1
 * Author: Hanjo Winter
 * Text Domain: hanjo-portfolio-core
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Plugin' ) ) {
    /**
     * Main plugin class.
     */
    class HPC_Plugin {
        /**
         * Plugin instance.
         *
         * @var HPC_Plugin
         */
        private static $instance;

        /**
         * Get singleton instance.
         *
         * @return HPC_Plugin
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
            $this->define_constants();
            $this->includes();
            $this->hooks();
        }

        /**
         * Define constants.
         */
        private function define_constants() {
            define( 'HPC_PLUGIN_FILE', __FILE__ );
            define( 'HPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            define( 'HPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
            define( 'HPC_PLUGIN_VERSION', '1.0.0' );
        }

        /**
         * Include required files.
         */
        private function includes() {
            require_once HPC_PLUGIN_DIR . 'includes/class-hpc-cpt.php';
            require_once HPC_PLUGIN_DIR . 'includes/class-hpc-meta.php';
            require_once HPC_PLUGIN_DIR . 'includes/class-hpc-elementor.php';
        }

        /**
         * Initialize hooks.
         */
        private function hooks() {
            register_activation_hook( HPC_PLUGIN_FILE, array( $this, 'activate' ) );
            add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
        }

        /**
         * Plugin activation callback.
         */
        public function activate() {
            HPC_CPT::get_instance()->register_post_types();
            HPC_CPT::get_instance()->register_taxonomies();
            flush_rewrite_rules();
        }

        /**
         * Initialize components.
         */
        public function init_plugin() {
            HPC_CPT::get_instance();
            HPC_Meta::get_instance();
            HPC_Elementor::get_instance();
        }
    }
}

HPC_Plugin::get_instance();
