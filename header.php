<?php
/**
 * The theme header template.
 *
 * Outputs the <head>, opens <body>, fires wp_body_open(), and renders the
 * accessibility skip link. The visible header UI is added in Step 05 via
 * get_template_part( 'template-parts/layout/header' ).
 *
 * @package ai-driven-boilerplate
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content">
	<?php esc_html_e( 'Skip to content', 'ai-driven-boilerplate' ); ?>
</a>
