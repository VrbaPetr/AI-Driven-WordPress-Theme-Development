<?php
/**
 * The template for displaying the static front page.
 *
 * WordPress loads this file automatically when a static front page is set in
 * Settings → Reading. All visible content is driven by Gutenberg blocks added
 * in the editor — no hardcoded sections.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output Open Graph meta tags for the home page.
 * Step 33 (SEO Foundations) will centralise OG output into inc/functions-seo.php.
 */
add_action(
	'wp_head',
	function () {
		$site_name   = get_bloginfo( 'name' );
		$tagline     = get_field( 'tagline', 'option' );
		$description = $tagline ? $tagline : get_bloginfo( 'description' );
		$home_url    = home_url( '/' );
		$logo        = get_field( 'logo_light', 'option' );
		$og_image    = ! empty( $logo['url'] ) ? $logo['url'] : '';
		?>
		<meta property="og:type"        content="website">
		<meta property="og:title"       content="<?php echo esc_attr( $site_name ); ?>">
		<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
		<meta property="og:url"         content="<?php echo esc_url( $home_url ); ?>">
		<?php if ( $og_image ) : ?>
		<meta property="og:image"       content="<?php echo esc_url( $og_image ); ?>">
		<?php endif; ?>
		<?php
	},
	5
);

get_header();
get_template_part( 'template-parts/pages/home' );
get_footer();
