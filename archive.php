<?php
/**
 * The template for displaying archive pages (date, author, etc.).
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/pages/blog-archive' );
get_footer();
