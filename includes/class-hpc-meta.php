<?php
/**
 * Meta boxes for custom fields.
 *
 * @package Hanjo_Portfolio_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'HPC_Meta' ) ) {
    /**
     * Handles meta boxes and saving meta data.
     */
    class HPC_Meta {
        /**
         * Instance.
         *
         * @var HPC_Meta
         */
        private static $instance;

        /**
         * Get instance.
         *
         * @return HPC_Meta
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
            add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
            add_action( 'save_post', array( $this, 'save_project_meta' ) );
            add_action( 'save_post', array( $this, 'save_experience_meta' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
            add_action( 'init', array( $this, 'register_meta_fields' ) );
        }

        /**
         * Enqueue admin assets for media uploader.
         */
        public function enqueue_admin_assets( $hook ) {
            if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
                return;
            }

            $screen = get_current_screen();
            if ( ! $screen || 'projekt' !== $screen->post_type ) {
                return;
            }

            wp_enqueue_media();
            wp_enqueue_script(
                'hpc-admin',
                HPC_PLUGIN_URL . 'assets/js/hpc-admin.js',
                array( 'jquery' ),
                HPC_PLUGIN_VERSION,
                true
            );
            wp_enqueue_style(
                'hpc-admin',
                HPC_PLUGIN_URL . 'assets/css/hpc-admin.css',
                array(),
                HPC_PLUGIN_VERSION
            );
        }

        /**
         * Register meta fields for REST/Elementor usage.
         */
        public function register_meta_fields() {
            foreach ( $this->get_project_meta_definitions() as $key => $args ) {
                register_post_meta( 'projekt', $key, $args );
            }

            foreach ( $this->get_experience_meta_definitions() as $key => $args ) {
                register_post_meta( 'experience', $key, $args );
            }
        }

        /**
         * Register meta boxes.
         */
        public function register_meta_boxes() {
            add_meta_box(
                'hpc_projekt_details',
                __( 'Projekt-Details', 'hanjo-portfolio-core' ),
                array( $this, 'render_project_details_meta_box' ),
                'projekt',
                'normal',
                'default'
            );

            add_meta_box(
                'hpc_projekt_gallery',
                __( 'Projekt-Galerie', 'hanjo-portfolio-core' ),
                array( $this, 'render_project_gallery_meta_box' ),
                'projekt',
                'normal',
                'default'
            );

            add_meta_box(
                'hpc_experience_details',
                __( 'Stations-Details', 'hanjo-portfolio-core' ),
                array( $this, 'render_experience_meta_box' ),
                'experience',
                'normal',
                'default'
            );
        }

        /**
         * Render project details meta box.
         */
        public function render_project_details_meta_box( $post ) {
            $fields = $this->get_project_fields();
            wp_nonce_field( 'hpc_save_project', 'hpc_project_nonce' );
            echo '<div class="hpc-meta-grid">';
            foreach ( $fields as $key => $field ) {
                $value = get_post_meta( $post->ID, $key, true );
                echo '<p class="hpc-meta-field">';
                echo '<label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br />';
                switch ( $field['type'] ) {
                    case 'textarea':
                        echo '<textarea class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3">' . esc_textarea( $value ) . '</textarea>';
                        break;
                    case 'checkbox':
                        $checked = $value ? 'checked' : '';
                        echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1" ' . $checked . ' />';
                        echo ' <span>' . esc_html__( 'Aktivieren', 'hanjo-portfolio-core' ) . '</span>';
                        break;
                    default:
                        echo '<input class="widefat" type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
                        break;
                }
                if ( ! empty( $field['description'] ) ) {
                    echo '<small>' . esc_html( $field['description'] ) . '</small>';
                }
                echo '</p>';
            }
            echo '</div>';
        }

        /**
         * Render project gallery meta box.
         */
        public function render_project_gallery_meta_box( $post ) {
            $gallery = get_post_meta( $post->ID, 'projekt_gallery', true );
            $gallery = is_array( $gallery ) ? $gallery : array();
            wp_nonce_field( 'hpc_save_project_gallery', 'hpc_project_gallery_nonce' );
            echo '<div class="hpc-gallery-wrapper">';
            echo '<button type="button" class="button hpc-add-images">' . esc_html__( 'Bilder auswählen', 'hanjo-portfolio-core' ) . '</button>';
            echo '<ul class="hpc-gallery-list">';
            foreach ( $gallery as $attachment_id ) {
                $thumbnail = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                echo '<li data-attachment-id="' . esc_attr( $attachment_id ) . '">' . wp_kses_post( $thumbnail ) . '<span class="hpc-remove">&times;</span></li>';
            }
            echo '</ul>';
            echo '<input type="hidden" id="projekt_gallery" name="projekt_gallery" value="' . esc_attr( implode( ',', $gallery ) ) . '" />';
            echo '</div>';
        }

        /**
         * Render experience meta box.
         */
        public function render_experience_meta_box( $post ) {
            $fields = $this->get_experience_fields();
            wp_nonce_field( 'hpc_save_experience', 'hpc_experience_nonce' );
            echo '<div class="hpc-meta-grid">';
            foreach ( $fields as $key => $field ) {
                $value = get_post_meta( $post->ID, $key, true );
                echo '<p class="hpc-meta-field">';
                echo '<label for="' . esc_attr( $key ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br />';
                switch ( $field['type'] ) {
                    case 'textarea':
                        echo '<textarea class="widefat" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" rows="3">' . esc_textarea( $value ) . '</textarea>';
                        break;
                    case 'checkbox':
                        $checked = $value ? 'checked' : '';
                        echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1" ' . $checked . ' />';
                        echo ' <span>' . esc_html__( 'Aktuelle Position', 'hanjo-portfolio-core' ) . '</span>';
                        break;
                    default:
                        echo '<input class="widefat" type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
                        break;
                }
                if ( ! empty( $field['description'] ) ) {
                    echo '<small>' . esc_html( $field['description'] ) . '</small>';
                }
                echo '</p>';
            }
            echo '</div>';
        }

        /**
         * Save project meta data.
         */
        public function save_project_meta( $post_id ) {
            if ( ! isset( $_POST['hpc_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hpc_project_nonce'] ) ), 'hpc_save_project' ) ) {
                return;
            }

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( isset( $_POST['post_type'] ) && 'projekt' !== $_POST['post_type'] ) {
                return;
            }

            $fields = $this->get_project_fields();
            foreach ( $fields as $key => $field ) {
                $value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : null;
                if ( 'checkbox' === $field['type'] ) {
                    update_post_meta( $post_id, $key, $value ? 1 : 0 );
                } else {
                    $clean = ( 'textarea' === $field['type'] ) ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
                    update_post_meta( $post_id, $key, $clean );
                }
            }

            if ( isset( $_POST['hpc_project_gallery_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hpc_project_gallery_nonce'] ) ), 'hpc_save_project_gallery' ) ) {
                $gallery_raw = isset( $_POST['projekt_gallery'] ) ? sanitize_text_field( wp_unslash( $_POST['projekt_gallery'] ) ) : '';
                $ids         = array_filter( array_map( 'absint', explode( ',', $gallery_raw ) ) );
                update_post_meta( $post_id, 'projekt_gallery', $ids );
            }
        }

        /**
         * Save experience meta data.
         */
        public function save_experience_meta( $post_id ) {
            if ( ! isset( $_POST['hpc_experience_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hpc_experience_nonce'] ) ), 'hpc_save_experience' ) ) {
                return;
            }

            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            if ( isset( $_POST['post_type'] ) && 'experience' !== $_POST['post_type'] ) {
                return;
            }

            $fields = $this->get_experience_fields();
            foreach ( $fields as $key => $field ) {
                $value = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : null;
                if ( 'checkbox' === $field['type'] ) {
                    update_post_meta( $post_id, $key, $value ? 1 : 0 );
                } else {
                    $clean = ( 'textarea' === $field['type'] ) ? sanitize_textarea_field( $value ) : sanitize_text_field( $value );
                    update_post_meta( $post_id, $key, $clean );
                }
            }
        }

        /**
         * Get project field definitions.
         */
        private function get_project_fields() {
            return array(
                'projekt_subtitle'    => array(
                    'label' => __( 'Untertitel / Claim', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'projekt_type'        => array(
                    'label' => __( 'Projekttyp', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'projekt_roles'       => array(
                    'label'       => __( 'Rollen', 'hanjo-portfolio-core' ),
                    'type'        => 'textarea',
                    'description' => __( 'Eine Rolle pro Zeile oder Komma-getrennt.', 'hanjo-portfolio-core' ),
                ),
                'projekt_client'      => array(
                    'label' => __( 'Kunde / Kontext', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'projekt_year'        => array(
                    'label' => __( 'Jahr / Zeitraum', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'projekt_goal'        => array(
                    'label' => __( 'Ziel / Problem', 'hanjo-portfolio-core' ),
                    'type'  => 'textarea',
                ),
                'projekt_what_i_did'  => array(
                    'label' => __( 'Was habe ich gemacht?', 'hanjo-portfolio-core' ),
                    'type'  => 'textarea',
                ),
                'projekt_result'      => array(
                    'label' => __( 'Ergebnis / Impact', 'hanjo-portfolio-core' ),
                    'type'  => 'textarea',
                ),
                'projekt_tools'       => array(
                    'label' => __( 'Tools / Tech Stack', 'hanjo-portfolio-core' ),
                    'type'  => 'textarea',
                ),
                'projekt_link_main'   => array(
                    'label'       => __( 'Hauptlink (URL)', 'hanjo-portfolio-core' ),
                    'type'        => 'text',
                    'description' => __( 'Externer Link zum Projekt.', 'hanjo-portfolio-core' ),
                ),
                'projekt_is_featured' => array(
                    'label'       => __( 'Auf Startseite hervorheben', 'hanjo-portfolio-core' ),
                    'type'        => 'checkbox',
                    'description' => __( 'Aktivieren, um das Projekt als Highlight zu markieren.', 'hanjo-portfolio-core' ),
                ),
                'projekt_sort_order'  => array(
                    'label'       => __( 'Sortierindex', 'hanjo-portfolio-core' ),
                    'type'        => 'text',
                    'description' => __( 'Optionaler numerischer Sortierwert.', 'hanjo-portfolio-core' ),
                ),
            );
        }

        /**
         * Get experience field definitions.
         */
        private function get_experience_fields() {
            return array(
                'experience_type'       => array(
                    'label' => __( 'Typ (Job, Ausbildung, etc.)', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'experience_org'        => array(
                    'label' => __( 'Organisation / Unternehmen', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'experience_location'   => array(
                    'label' => __( 'Ort', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'experience_start_date' => array(
                    'label' => __( 'Startdatum', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'experience_end_date'   => array(
                    'label' => __( 'Enddatum', 'hanjo-portfolio-core' ),
                    'type'  => 'text',
                ),
                'experience_is_current' => array(
                    'label'       => __( 'Aktuelle Position', 'hanjo-portfolio-core' ),
                    'type'        => 'checkbox',
                    'description' => __( 'Kennzeichnen, wenn dies die aktuelle Position ist.', 'hanjo-portfolio-core' ),
                ),
                'experience_short_desc' => array(
                    'label' => __( 'Kurzbeschreibung', 'hanjo-portfolio-core' ),
                    'type'  => 'textarea',
                ),
                'experience_bullets'    => array(
                    'label'       => __( 'Aufgaben (eine pro Zeile)', 'hanjo-portfolio-core' ),
                    'type'        => 'textarea',
                    'description' => __( 'Jede Zeile wird als Bullet ausgegeben.', 'hanjo-portfolio-core' ),
                ),
                'experience_order'      => array(
                    'label'       => __( 'Sortierindex', 'hanjo-portfolio-core' ),
                    'type'        => 'text',
                    'description' => __( 'Optionaler numerischer Sortierwert.', 'hanjo-portfolio-core' ),
                ),
            );
        }

        /**
         * Meta registration config for projects.
         *
         * @return array
         */
        private function get_project_meta_definitions() {
            $meta = array();

            foreach ( $this->get_project_fields() as $key => $field ) {
                $meta[ $key ] = $this->get_rest_meta_args( $field );
            }

            $meta['projekt_gallery'] = array(
                'type'              => 'array',
                'single'            => true,
                'show_in_rest'      => array(
                    'schema' => array(
                        'type'  => 'array',
                        'items' => array(
                            'type' => 'integer',
                        ),
                    ),
                ),
                'sanitize_callback' => array( $this, 'sanitize_gallery_meta' ),
                'auth_callback'     => '__return_true',
            );

            return $meta;
        }

        /**
         * Meta registration config for experiences.
         *
         * @return array
         */
        private function get_experience_meta_definitions() {
            $meta = array();

            foreach ( $this->get_experience_fields() as $key => $field ) {
                $meta[ $key ] = $this->get_rest_meta_args( $field );
            }

            return $meta;
        }

        /**
         * Build REST meta args from field config.
         *
         * @param array $field Field definition.
         *
         * @return array
         */
        private function get_rest_meta_args( $field ) {
            $type = 'string';

            if ( isset( $field['type'] ) && 'checkbox' === $field['type'] ) {
                $type = 'boolean';
            }

            return array(
                'type'          => $type,
                'single'        => true,
                'show_in_rest'  => true,
                'auth_callback' => '__return_true',
            );
        }

        /**
         * Sanitize gallery meta value.
         *
         * @param mixed $value Raw value.
         *
         * @return array
         */
        public function sanitize_gallery_meta( $value ) {
            if ( is_string( $value ) ) {
                $value = explode( ',', $value );
            }

            if ( ! is_array( $value ) ) {
                return array();
            }

            return array_values( array_filter( array_map( 'absint', $value ) ) );
        }
    }
}
