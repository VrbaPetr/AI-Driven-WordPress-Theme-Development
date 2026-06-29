<?php
/**
 * Theme setup, asset enqueueing, and includes of all inc/ files.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/class-aidriven-nav-walker.php';
require_once get_template_directory() . '/inc/functions-design.php';
require_once get_template_directory() . '/inc/functions-helpers.php';
require_once get_template_directory() . '/inc/functions-data.php';
require_once get_template_directory() . '/inc/functions-form.php';
require_once get_template_directory() . '/inc/functions-ajax.php';
require_once get_template_directory() . '/inc/functions-seo.php';
require_once get_template_directory() . '/inc/register-blocks.php';
require_once get_template_directory() . '/inc/register-cpt.php';
require_once get_template_directory() . '/inc/register-taxonomy.php';

/**
 * Enqueue theme assets resolved from the Vite manifest.
 *
 * Reads assets/.vite/manifest.json once per request (static cache) and
 * registers the hashed output filenames. Critical JS is enqueued blocking
 * in <head>; main JS is deferred; main CSS is enqueued normally.
 *
 * @return void
 */
function aidriven_enqueue_assets() {
	$manifest_path = get_template_directory() . '/assets/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		return;
	}

	static $manifest      = null;
	static $manifest_read = false;

	if ( ! $manifest_read ) {
		$manifest_read = true;
		$manifest      = wp_json_file_decode( $manifest_path, array( 'associative' => true ) );
	}

	if ( ! is_array( $manifest ) ) {
		return;
	}

	if ( isset( $manifest['src/css/main.css']['file'] ) ) {
		wp_enqueue_style(
			'aidriven-main',
			get_template_directory_uri() . '/assets/' . $manifest['src/css/main.css']['file'],
			array(),
			null
		);
	}

	// Critical JS must load blocking in <head> — intentionally not in footer.
	if ( isset( $manifest['src/js/critical.js']['file'] ) ) {
		wp_enqueue_script( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
			'aidriven-critical',
			get_template_directory_uri() . '/assets/' . $manifest['src/js/critical.js']['file'],
			array(),
			null,
			false
		);
	}

	if ( isset( $manifest['src/js/main.js']['file'] ) ) {
		wp_enqueue_script(
			'aidriven-main',
			get_template_directory_uri() . '/assets/' . $manifest['src/js/main.js']['file'],
			array(),
			null,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'aidriven_enqueue_assets' );
