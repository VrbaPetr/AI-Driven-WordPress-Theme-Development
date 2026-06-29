<?php
/**
 * The template for displaying all single posts.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
get_template_part( 'template-parts/pages/blog-single' );
get_footer();
