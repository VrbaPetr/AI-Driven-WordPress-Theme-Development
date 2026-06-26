<?php
/**
 * Component: Breadcrumbs
 *
 * Auto-generates a breadcrumb trail with BreadcrumbList JSON-LD schema.
 * Does not render on the front page.
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type string $classes Additional CSS classes on the <nav> element.
 * }
 */

if ( is_front_page() ) {
	return;
}

$classes = $args['classes'] ?? '';

$crumbs = array();

// Home is always the first crumb.
$crumbs[] = array(
	'name' => __( 'Home', 'ai-driven-boilerplate' ),
	'url'  => home_url( '/' ),
);

if ( is_singular() ) {

	$current_post_type = get_post_type();

	if ( 'post' === $current_post_type ) {

		// Blog post: Home → Blog page → Post title.
		$blog_page_id = (int) get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$crumbs[] = array(
				'name' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}

		// Primary category crumb between blog page and post.
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$crumbs[] = array(
				'name' => esc_html( $categories[0]->name ),
				'url'  => esc_url( get_category_link( $categories[0]->term_id ) ),
			);
		}
	} elseif ( 'page' !== $current_post_type ) {

		// CPT single: Home → CPT archive → Post title.
		$current_post_type_obj = get_post_type_object( $current_post_type );
		if ( $current_post_type_obj && $current_post_type_obj->has_archive ) {
			$crumbs[] = array(
				'name' => $current_post_type_obj->labels->name,
				'url'  => get_post_type_archive_link( $current_post_type ),
			);
		}
	} else {

		// Page: Home → (ancestor pages) → Current page.
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor_id ) {
			$crumbs[] = array(
				'name' => get_the_title( $ancestor_id ),
				'url'  => get_permalink( $ancestor_id ),
			);
		}
	}

	// Current singular item — no URL (last crumb).
	$crumbs[] = array(
		'name' => get_the_title(),
		'url'  => '',
	);

} elseif ( is_post_type_archive() ) {

	$current_post_type_obj = get_queried_object();
	$crumbs[]              = array(
		'name' => $current_post_type_obj->labels->name,
		'url'  => '',
	);

} elseif ( is_category() || is_tag() || is_tax() ) {

	$queried_term = get_queried_object();

	if ( is_category() || is_tag() ) {

		// Blog taxonomy: link to the blog posts page first.
		$blog_page_id = (int) get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$crumbs[] = array(
				'name' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}
	} else {

		// Custom taxonomy: link to the associated CPT archive.
		$queried_taxonomy = get_taxonomy( $queried_term->taxonomy );
		if ( $queried_taxonomy && ! empty( $queried_taxonomy->object_type ) ) {
			$cpt_obj = get_post_type_object( $queried_taxonomy->object_type[0] );
			if ( $cpt_obj && $cpt_obj->has_archive ) {
				$crumbs[] = array(
					'name' => $cpt_obj->labels->name,
					'url'  => get_post_type_archive_link( $queried_taxonomy->object_type[0] ),
				);
			}
		}
	}

	$crumbs[] = array(
		'name' => $queried_term->name,
		'url'  => '',
	);

} elseif ( is_search() ) {

	$crumbs[] = array(
		/* translators: %s: search query string */
		'name' => sprintf( __( 'Search: %s', 'ai-driven-boilerplate' ), get_search_query() ),
		'url'  => '',
	);

} elseif ( is_404() ) {

	$crumbs[] = array(
		'name' => __( 'Page not found', 'ai-driven-boilerplate' ),
		'url'  => '',
	);
}

if ( count( $crumbs ) < 2 ) {
	return;
}

// Build JSON-LD schema.
$schema_items = array();
foreach ( $crumbs as $position => $crumb ) {
	$item = array(
		'@type'    => 'ListItem',
		'position' => $position + 1,
		'name'     => $crumb['name'],
	);
	if ( ! empty( $crumb['url'] ) ) {
		$item['item'] = $crumb['url'];
	}
	$schema_items[] = $item;
}

$schema = array(
	'@context'        => 'https://schema.org',
	'@type'           => 'BreadcrumbList',
	'itemListElement' => $schema_items,
);

$nav_class = 'breadcrumbs';
if ( $classes ) {
	$nav_class .= ' ' . $classes;
}

$last_index = count( $crumbs ) - 1;
?>

<nav class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumb', 'ai-driven-boilerplate' ); ?>">
	<ol class="breadcrumbs-list">
		<?php foreach ( $crumbs as $index => $crumb ) : ?>
			<?php $is_last = ( $index === $last_index ); ?>
			<li class="breadcrumbs-item<?php echo $is_last ? ' breadcrumbs-item--current' : ''; ?>">
				<?php if ( ! $is_last && ! empty( $crumb['url'] ) ) : ?>
					<a href="<?php echo esc_url( $crumb['url'] ); ?>" class="breadcrumbs-link">
						<?php echo esc_html( $crumb['name'] ); ?>
					</a>
				<?php else : ?>
					<span class="breadcrumbs-current" aria-current="page">
						<?php echo esc_html( $crumb['name'] ); ?>
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode is safe ?>
</script>
