<?php
/**
 * Hanjo Portfolio Theme functions and definitions.
 *
 * @package Hanjo_Portfolio_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'HPT_VERSION' ) ) {
    define( 'HPT_VERSION', '1.0.0' );
}

if ( ! defined( 'HPT_THEME_DIR' ) ) {
    define( 'HPT_THEME_DIR', get_template_directory() . '/' );
}

if ( ! defined( 'HPT_THEME_URI' ) ) {
    define( 'HPT_THEME_URI', get_template_directory_uri() . '/' );
}

/**
 * Theme setup.
 */
function hpt_setup() {
    load_theme_textdomain( 'hanjo-portfolio-theme', HPT_THEME_DIR . 'languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array(
        'height'      => 64,
        'width'       => 64,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    register_nav_menus(
        array(
            'primary' => __( 'Primäre Navigation', 'hanjo-portfolio-theme' ),
        )
    );

    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    add_image_size( 'hpt-project-thumb', 720, 480, true );
    add_image_size( 'hpt-project-card', 540, 340, true );
}
add_action( 'after_setup_theme', 'hpt_setup' );

/**
 * Enqueue styles and scripts.
 */
function hpt_enqueue_assets() {
    wp_enqueue_style(
        'hpt-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600&family=Inter:wght@400;500;600;700&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'hpt-theme',
        HPT_THEME_URI . 'assets/css/theme.css',
        array(),
        HPT_VERSION
    );

    wp_enqueue_style(
        'hpt-style',
        get_stylesheet_uri(),
        array( 'hpt-theme' ),
        HPT_VERSION
    );

    wp_enqueue_script(
        'hpt-theme',
        HPT_THEME_URI . 'assets/js/theme.js',
        array(),
        HPT_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hpt_enqueue_assets' );

/**
 * Helper to format multiline meta fields (newline or comma separated) into arrays.
 *
 * @param string $value Raw string from post meta.
 * @return string[]
 */
function hpt_parse_list_field( $value ) {
    if ( empty( $value ) ) {
        return array();
    }

    $items = preg_split( '/\r?\n|,/', wp_strip_all_tags( (string) $value ) );
    return array_filter( array_map( 'trim', $items ) );
}

/**
 * Retrieve project meta with default fallback.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function hpt_get_project_meta( $post_id, $key, $default = '' ) {
    $value = get_post_meta( $post_id, $key, true );
    return $value ? $value : $default;
}

/**
 * Retrieve experience meta with default fallback.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Meta key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function hpt_get_experience_meta( $post_id, $key, $default = '' ) {
    $value = get_post_meta( $post_id, $key, true );
    return ( '' !== $value && null !== $value ) ? $value : $default;
}

