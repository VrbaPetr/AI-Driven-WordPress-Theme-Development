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

/**
 * Register service_cat as a recognised query variable.
 */
add_filter(
	'query_vars',
	function ( $vars ) {
		$vars[] = 'service_cat';
		return $vars;
	}
);

/**
 * Filter the service CPT archive by the service_cat query variable on page reload.
 *
 * Allows filter links on the archive to append ?service_cat={slug} and have
 * WordPress restrict the main query to that taxonomy term.
 *
 * @param WP_Query $query Current query object passed by reference.
 * @return void
 */
function aidriven_filter_service_archive( WP_Query $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_post_type_archive( 'service' ) ) {
		return;
	}

	$cat_slug = get_query_var( 'service_cat' );
	if ( ! $cat_slug ) {
		return;
	}

	$tax_query = array(
		array(
			'taxonomy' => 'service-category',
			'field'    => 'slug',
			'terms'    => sanitize_key( $cat_slug ),
		),
	);
	$query->set( 'tax_query', $tax_query ); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
}
add_action( 'pre_get_posts', 'aidriven_filter_service_archive' );

/**
 * Return up to 3 related services from the same service-category, excluding the current post.
 *
 * @param int $post_id ID of the current service post.
 * @return WP_Query Query object; caller must call wp_reset_postdata() after looping.
 */
function aidriven_get_related_services( $post_id ) {
	$query_args = array(
		'post_type'      => 'service',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	$terms = get_the_terms( $post_id, 'service-category' );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$term_ids                = wp_list_pluck( $terms, 'term_id' );
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'service-category',
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		);
	}

	return new WP_Query( $query_args );
}

/**
 * Return up to 3 related projects from the same portfolio-category, excluding the current post.
 *
 * @param int $post_id ID of the current project post.
 * @return WP_Query Query object; caller must call wp_reset_postdata() after looping.
 */
function aidriven_get_related_projects( $post_id ) {
	$query_args = array(
		'post_type'      => 'project',
		'posts_per_page' => 3,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
	);

	$terms = get_the_terms( $post_id, 'portfolio-category' );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$term_ids                = wp_list_pluck( $terms, 'term_id' );
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'portfolio-category',
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		);
	}

	return new WP_Query( $query_args );
}
