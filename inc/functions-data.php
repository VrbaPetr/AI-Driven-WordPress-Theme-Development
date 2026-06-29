<?php
/**
 * Data-related functions and custom queries.
 *
 * Query helpers and data-transformation functions are added here in later
 * build steps (Steps 17–29). Keep templates free of WP_Query logic — move
 * it here and call from the template.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Query portfolio (project CPT) posts with optional category filtering.
 *
 * Returns an array of normalised post data plus a has_more flag indicating
 * whether additional pages exist beyond the requested page.
 *
 * @param int    $paged         Page number (1-based).
 * @param int    $per_page      Number of posts per page.
 * @param string $category_slug Portfolio-category term slug, or empty / 'all' for no filter.
 * @return array {
 *     @type array $posts    Array of post data arrays (id, title, url, thumb_id, categories, category_name, description).
 *     @type bool  $has_more Whether more posts exist beyond the current page.
 * }
 */
function ai_driven_get_portfolio_posts( $paged, $per_page, $category_slug = '' ) {
	$query_args = array(
		'post_type'      => 'project',
		'posts_per_page' => $per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	if ( ! empty( $category_slug ) && 'all' !== $category_slug ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'portfolio-category',
				'field'    => 'slug',
				'terms'    => $category_slug,
			),
		);
	}

	$query    = new WP_Query( $query_args );
	$has_more = $query->max_num_pages > $paged;
	$posts    = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$current_id = get_the_ID();
			$terms      = wp_get_post_terms( $current_id, 'portfolio-category', array( 'fields' => 'all' ) );
			$cat_slugs  = array();
			$cat_name   = '';

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$cat_slugs[] = $term->slug;
				}
				$cat_name = $terms[0]->name;
			}

			$posts[] = array(
				'id'            => $current_id,
				'title'         => get_the_title(),
				'url'           => get_permalink(),
				'thumb_id'      => get_post_thumbnail_id(),
				'categories'    => implode( ',', $cat_slugs ),
				'category_name' => $cat_name,
				'description'   => get_field( 'short_description', $current_id ),
			);
		}
		wp_reset_postdata();
	}

	return array(
		'posts'    => $posts,
		'has_more' => $has_more,
	);
}
