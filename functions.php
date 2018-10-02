<?php
/**
 * Codeline Movies Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package codeline-movies
 */

add_action( 'wp_enqueue_scripts', 'unite_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function unite_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'unite-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'codeline-movies-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'unite-style' )
	);

}
