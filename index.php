<?php
/**
 * The main template file.
 *
 * WordPress requires this file as a fallback when no more-specific template
 * matches. Dedicated templates are added per step in Phase 6. This file
 * renders a bare loop sufficient for development until then.
 *
 * @package ai-driven-boilerplate
 */

get_header();
?>
<main id="main-content">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<h1><?php the_title(); ?></h1>
				<div class="entry-content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		}
	}
	?>
</main>
<?php
get_footer();
