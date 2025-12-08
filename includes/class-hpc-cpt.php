<?php
/**
 * Custom post types and taxonomies.
 *
 * @package Hanjo_Portfolio_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_CPT' ) ) {
    /**
     * Registers CPTs and taxonomies.
     */
    class HPC_CPT {
        /**
         * Singleton instance.
         *
         * @var HPC_CPT
         */
        private static $instance;

        /**
         * Get instance.
         *
         * @return HPC_CPT
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
            add_action( 'init', array( $this, 'register_post_types' ) );
            add_action( 'init', array( $this, 'register_taxonomies' ) );
        }

        /**
         * Register custom post types.
         */
        public function register_post_types() {
            $projekt_labels = array(
                'name'                  => __( 'Projekte', 'hanjo-portfolio-core' ),
                'singular_name'         => __( 'Projekt', 'hanjo-portfolio-core' ),
                'add_new'               => __( 'Neues Projekt', 'hanjo-portfolio-core' ),
                'add_new_item'          => __( 'Projekt hinzufügen', 'hanjo-portfolio-core' ),
                'edit_item'             => __( 'Projekt bearbeiten', 'hanjo-portfolio-core' ),
                'new_item'              => __( 'Neues Projekt', 'hanjo-portfolio-core' ),
                'view_item'             => __( 'Projekt ansehen', 'hanjo-portfolio-core' ),
                'view_items'            => __( 'Projekte ansehen', 'hanjo-portfolio-core' ),
                'search_items'          => __( 'Projekte durchsuchen', 'hanjo-portfolio-core' ),
                'not_found'             => __( 'Keine Projekte gefunden', 'hanjo-portfolio-core' ),
                'not_found_in_trash'    => __( 'Keine Projekte im Papierkorb', 'hanjo-portfolio-core' ),
                'all_items'             => __( 'Alle Projekte', 'hanjo-portfolio-core' ),
                'archives'              => __( 'Projekt-Archiv', 'hanjo-portfolio-core' ),
                'attributes'            => __( 'Projekt-Attribute', 'hanjo-portfolio-core' ),
                'insert_into_item'      => __( 'In Projekt einfügen', 'hanjo-portfolio-core' ),
                'uploaded_to_this_item' => __( 'Zu diesem Projekt hochgeladen', 'hanjo-portfolio-core' ),
                'featured_image'        => __( 'Projektbild', 'hanjo-portfolio-core' ),
                'set_featured_image'    => __( 'Beitragsbild setzen', 'hanjo-portfolio-core' ),
                'remove_featured_image' => __( 'Beitragsbild entfernen', 'hanjo-portfolio-core' ),
                'use_featured_image'    => __( 'Als Beitragsbild verwenden', 'hanjo-portfolio-core' ),
            );

            register_post_type(
                'projekt',
                array(
                    'labels'       => $projekt_labels,
                    'public'       => true,
                    'has_archive'  => true,
                    'show_in_rest' => true,
                    'menu_icon'    => 'dashicons-portfolio',
                    'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
                )
            );

            $experience_labels = array(
                'name'                  => __( 'Stationen', 'hanjo-portfolio-core' ),
                'singular_name'         => __( 'Station', 'hanjo-portfolio-core' ),
                'add_new'               => __( 'Neue Station', 'hanjo-portfolio-core' ),
                'add_new_item'          => __( 'Station hinzufügen', 'hanjo-portfolio-core' ),
                'edit_item'             => __( 'Station bearbeiten', 'hanjo-portfolio-core' ),
                'new_item'              => __( 'Neue Station', 'hanjo-portfolio-core' ),
                'view_item'             => __( 'Station ansehen', 'hanjo-portfolio-core' ),
                'view_items'            => __( 'Stationen ansehen', 'hanjo-portfolio-core' ),
                'search_items'          => __( 'Stationen durchsuchen', 'hanjo-portfolio-core' ),
                'not_found'             => __( 'Keine Stationen gefunden', 'hanjo-portfolio-core' ),
                'not_found_in_trash'    => __( 'Keine Stationen im Papierkorb', 'hanjo-portfolio-core' ),
                'all_items'             => __( 'Alle Stationen', 'hanjo-portfolio-core' ),
            );

            register_post_type(
                'experience',
                array(
                    'labels'       => $experience_labels,
                    'public'       => true,
                    'has_archive'  => false,
                    'show_in_rest' => true,
                    'menu_icon'    => 'dashicons-id',
                    'supports'     => array( 'title', 'editor', 'revisions' ),
                )
            );
        }

        /**
         * Register custom taxonomies.
         */
        public function register_taxonomies() {
            $project_category_labels = array(
                'name'              => __( 'Projektkategorien', 'hanjo-portfolio-core' ),
                'singular_name'     => __( 'Projektkategorie', 'hanjo-portfolio-core' ),
                'search_items'      => __( 'Kategorien durchsuchen', 'hanjo-portfolio-core' ),
                'all_items'         => __( 'Alle Projektkategorien', 'hanjo-portfolio-core' ),
                'parent_item'       => __( 'Übergeordnete Kategorie', 'hanjo-portfolio-core' ),
                'parent_item_colon' => __( 'Übergeordnete Kategorie:', 'hanjo-portfolio-core' ),
                'edit_item'         => __( 'Kategorie bearbeiten', 'hanjo-portfolio-core' ),
                'update_item'       => __( 'Kategorie aktualisieren', 'hanjo-portfolio-core' ),
                'add_new_item'      => __( 'Neue Kategorie hinzufügen', 'hanjo-portfolio-core' ),
                'new_item_name'     => __( 'Neue Kategorie', 'hanjo-portfolio-core' ),
                'menu_name'         => __( 'Projektkategorien', 'hanjo-portfolio-core' ),
            );

            register_taxonomy(
                'project_category',
                array( 'projekt' ),
                array(
                    'hierarchical'      => true,
                    'labels'            => $project_category_labels,
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'query_var'         => true,
                    'show_in_rest'      => true,
                    'rewrite'           => array( 'slug' => 'project-category' ),
                )
            );

            $project_skill_labels = array(
                'name'                       => __( 'Skills & Tools', 'hanjo-portfolio-core' ),
                'singular_name'              => __( 'Skill / Tool', 'hanjo-portfolio-core' ),
                'search_items'               => __( 'Skills durchsuchen', 'hanjo-portfolio-core' ),
                'popular_items'              => __( 'Beliebte Skills', 'hanjo-portfolio-core' ),
                'all_items'                  => __( 'Alle Skills & Tools', 'hanjo-portfolio-core' ),
                'edit_item'                  => __( 'Skill bearbeiten', 'hanjo-portfolio-core' ),
                'update_item'                => __( 'Skill aktualisieren', 'hanjo-portfolio-core' ),
                'add_new_item'               => __( 'Neuen Skill hinzufügen', 'hanjo-portfolio-core' ),
                'new_item_name'              => __( 'Neuer Skill', 'hanjo-portfolio-core' ),
                'separate_items_with_commas' => __( 'Skills mit Komma trennen', 'hanjo-portfolio-core' ),
                'add_or_remove_items'        => __( 'Skills hinzufügen oder entfernen', 'hanjo-portfolio-core' ),
                'choose_from_most_used'      => __( 'Aus den häufigsten wählen', 'hanjo-portfolio-core' ),
                'menu_name'                  => __( 'Skills & Tools', 'hanjo-portfolio-core' ),
            );

            register_taxonomy(
                'project_skill',
                array( 'projekt' ),
                array(
                    'hierarchical'      => false,
                    'labels'            => $project_skill_labels,
                    'show_ui'           => true,
                    'show_admin_column' => true,
                    'update_count_callback' => '_update_post_term_count',
                    'query_var'         => true,
                    'show_in_rest'      => true,
                    'rewrite'           => array( 'slug' => 'skill' ),
                )
            );
        }
    }
}
