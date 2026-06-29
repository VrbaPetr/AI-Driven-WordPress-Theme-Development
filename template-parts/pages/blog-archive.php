<?php
/**
 * Page: Blog Archive
 *
 * Shared template part for home.php, archive.php, category.php, and tag.php.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Resolve archive title and optional term description.
if ( is_home() ) {
	$page_id       = (int) get_option( 'page_for_posts' );
	$archive_title = $page_id ? get_the_title( $page_id ) : __( 'Blog', 'ai-driven-boilerplate' );
	$archive_desc  = '';
} elseif ( is_category() || is_tag() ) {
	$archive_title = single_term_title( '', false );
	$term_desc     = term_description();
	$archive_desc  = $term_desc ? wp_strip_all_tags( $term_desc ) : '';
} else {
	$archive_title = get_the_archive_title();
	$archive_desc  = get_the_archive_description();
	$archive_desc  = $archive_desc ? wp_strip_all_tags( $archive_desc ) : '';
}

// Category filter links (always built from all categories).
$blog_categories = get_categories( array( 'hide_empty' => true ) );
$blog_page_url   = get_permalink( get_option( 'page_for_posts' ) );
$current_cat_id  = is_category() ? get_queried_object_id() : 0;
?>
<main id="main-content" class="blog-archive-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'    => $archive_title,
			'subtitle' => $archive_desc,
		)
	);
	?>

	<div class="blog-archive-inner">

		<?php if ( ! empty( $blog_categories ) ) : ?>
			<div class="blog-archive-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'ai-driven-boilerplate' ); ?>">

				<a href="<?php echo esc_url( $blog_page_url ); ?>"
					class="btn btn-sm <?php echo $current_cat_id ? 'btn-ghost' : 'btn-primary'; ?>"
					<?php echo ! $current_cat_id ? 'aria-current="true"' : ''; ?>>
					<?php esc_html_e( 'All', 'ai-driven-boilerplate' ); ?>
				</a>

				<?php foreach ( $blog_categories as $blog_cat ) : ?>
					<?php $is_active = ( $current_cat_id === $blog_cat->term_id ); ?>
					<a href="<?php echo esc_url( get_category_link( $blog_cat->term_id ) ); ?>"
						class="btn btn-sm <?php echo $is_active ? 'btn-primary' : 'btn-ghost'; ?>"
						<?php echo $is_active ? 'aria-current="true"' : ''; ?>>
						<?php echo esc_html( $blog_cat->name ); ?>
					</a>
				<?php endforeach; ?>

			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<ul class="blog-archive-grid" role="list">
				<?php
				while ( have_posts() ) {
					the_post();

					$current_id     = get_the_ID();
					$categories     = get_the_category( $current_id );
					$cat_name       = ! empty( $categories ) ? $categories[0]->name : '';
					$cat_url        = ! empty( $categories ) ? get_category_link( $categories[0]->term_id ) : '';
					$read_time      = aidriven_get_read_time( $current_id );
					$date_formatted = get_the_date( '', $current_id );
					/* translators: %d: number of minutes */
					$read_time_label = sprintf( _n( '%d min read', '%d min read', $read_time, 'ai-driven-boilerplate' ), $read_time );
					$meta_string     = $date_formatted . ' · ' . $read_time_label;
					?>
					<li>
						<?php
						get_template_part(
							'template-parts/components/card',
							null,
							array(
								'image_id'     => get_post_thumbnail_id(),
								'title'        => get_the_title(),
								'title_url'    => get_permalink(),
								'category'     => $cat_name,
								'category_url' => $cat_url,
								'excerpt'      => get_the_excerpt(),
								'meta'         => $meta_string,
								'cta_label'    => __( 'Read More', 'ai-driven-boilerplate' ),
								'cta_url'      => get_permalink(),
							)
						);
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php get_template_part( 'template-parts/components/pagination' ); ?>

		<?php else : ?>
			<p class="blog-archive-empty">
				<?php esc_html_e( 'No posts found.', 'ai-driven-boilerplate' ); ?>
			</p>
		<?php endif; ?>

	</div><!-- .blog-archive-inner -->

</main><!-- #main-content -->
