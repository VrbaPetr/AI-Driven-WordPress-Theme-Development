<?php
/**
 * Coming Soon / Maintenance Mode template.
 *
 * Standalone full-page template — no get_header() or get_footer().
 * Loaded directly by aidriven_maybe_show_maintenance_page() via include.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$logo_variant  = get_field( 'maintenance_logo_variant', 'option' );
$logo_field    = ( 'dark' === $logo_variant ) ? 'logo_dark' : 'logo_light';
$logo          = get_field( $logo_field, 'option' );
$heading       = get_field( 'maintenance_heading', 'option' );
$message       = get_field( 'maintenance_message', 'option' );
$contact_email = get_field( 'email_address', 'option' );
$theme_version = wp_get_theme()->get( 'Version' );

if ( empty( $heading ) ) {
	$heading = __( "We'll be back soon", 'ai-driven-boilerplate' );
}

$css_url = get_template_directory_uri() . '/assets/css/coming-soon.css';
?>
<!DOCTYPE html>
<html lang="<?php echo esc_attr( get_bloginfo( 'language' ) ); ?>">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
	<title><?php echo esc_html( get_bloginfo( 'name' ) ); ?> &mdash; <?php esc_html_e( 'Coming Soon', 'ai-driven-boilerplate' ); ?></title>
	<link rel="stylesheet" href="<?php echo esc_url( $css_url . '?v=' . $theme_version ); ?>"> <?php // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet -- standalone template, no wp_head() available. ?>
</head>
<body>

	<div class="coming-soon">

		<?php if ( ! empty( $logo ) ) : ?>
			<div class="coming-soon__logo">
				<img
					src="<?php echo esc_url( $logo['url'] ); ?>"
					alt="<?php echo esc_attr( $logo['alt'] ? $logo['alt'] : get_bloginfo( 'name' ) ); ?>"
					width="<?php echo absint( $logo['width'] ); ?>"
					height="<?php echo absint( $logo['height'] ); ?>"
				>
			</div>
		<?php endif; ?>

		<h1 class="coming-soon__heading"><?php echo esc_html( $heading ); ?></h1>

		<?php if ( ! empty( $message ) ) : ?>
			<div class="coming-soon__message">
				<?php echo wp_kses_post( $message ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $contact_email ) ) : ?>
			<p class="coming-soon__contact">
				<?php esc_html_e( 'Questions? Reach us at', 'ai-driven-boilerplate' ); ?>
				<a href="mailto:<?php echo esc_attr( $contact_email ); ?>">
					<?php echo esc_html( $contact_email ); ?>
				</a>
			</p>
		<?php endif; ?>

	</div><!-- .coming-soon -->

</body>
</html>
