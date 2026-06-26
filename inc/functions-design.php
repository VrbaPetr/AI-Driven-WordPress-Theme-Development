<?php
/**
 * Design-related functions: theme supports, menu locations, and image sizes.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Register theme supports, navigation menus, and custom image sizes.
 *
 * Hooked to after_setup_theme with priority 10.
 *
 * Image sizes registered:
 *   card-thumbnail  — 800 × 533 px  (3:2, cropped) — blog/portfolio/services cards
 *   hero-full       — 1920 × 900 px (cropped)       — full-width hero background
 *   hero-split      — 960 × 900 px  (cropped)       — split hero, image side
 *   team-portrait   — 600 × 750 px  (4:5, cropped)  — team member photos
 *   portfolio-thumb — 800 × 600 px  (4:3, cropped)  — portfolio grid tiles
 *
 * @return void
 */
function aidriven_setup_theme() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'ai-driven-boilerplate' ),
			'footer'  => __( 'Footer Navigation', 'ai-driven-boilerplate' ),
		)
	);

	add_image_size( 'card-thumbnail', 800, 533, true );
	add_image_size( 'hero-full', 1920, 900, true );
	add_image_size( 'hero-split', 960, 900, true );
	add_image_size( 'team-portrait', 600, 750, true );
	add_image_size( 'portfolio-thumb', 800, 600, true );
}
add_action( 'after_setup_theme', 'aidriven_setup_theme' );
