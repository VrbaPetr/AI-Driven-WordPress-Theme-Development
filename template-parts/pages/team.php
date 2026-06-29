<?php
/**
 * Page: Team
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<main id="main-content" class="team-main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			get_template_part(
				'template-parts/layout/page-header',
				null,
				array(
					'title'    => get_the_title(),
					'subtitle' => get_the_excerpt(),
				)
			);

			the_content();
		}
	}
	?>
</main><!-- #main-content -->
