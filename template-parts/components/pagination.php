<?php
/**
 * Component: Pagination
 *
 * Wraps paginate_links() in accessible nav markup.
 * Does not render when there is only one page of results.
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type WP_Query $query   Custom query to paginate. Falls back to the global $wp_query.
 *     @type string   $classes Additional CSS classes on the <nav> element.
 * }
 */

global $wp_query;

$query   = isset( $args['query'] ) && $args['query'] instanceof WP_Query ? $args['query'] : $wp_query;
$classes = $args['classes'] ?? '';

$max_pages    = (int) $query->max_num_pages;
$current_page = max( 1, (int) get_query_var( 'paged' ) );

if ( $max_pages <= 1 ) {
	return;
}

$links = paginate_links(
	array(
		'base'      => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $current_page,
		'total'     => $max_pages,
		'type'      => 'array',
		'prev_text' => '&lsaquo;',
		'next_text' => '&rsaquo;',
	)
);

if ( empty( $links ) ) {
	return;
}

$nav_class = 'pagination';
if ( $classes ) {
	$nav_class .= ' ' . $classes;
}

$prev_label = esc_attr__( 'Previous page', 'ai-driven-boilerplate' );
$next_label = esc_attr__( 'Next page', 'ai-driven-boilerplate' );
?>

<nav class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php esc_attr_e( 'Page navigation', 'ai-driven-boilerplate' ); ?>">
	<ul class="pagination-list">
		<?php foreach ( $links as $page_link ) : ?>
			<?php
			// Add aria-label to prev/next arrows.
			$page_link = str_replace( 'class="prev page-numbers"', 'class="prev page-numbers" aria-label="' . $prev_label . '"', $page_link );
			$page_link = str_replace( 'class="next page-numbers"', 'class="next page-numbers" aria-label="' . $next_label . '"', $page_link );

			// Add aria-current to the active page span.
			$page_link = str_replace( 'class="page-numbers current"', 'class="page-numbers current" aria-current="page"', $page_link );

			// Determine item modifier class.
			$item_class = 'pagination-item';
			if ( str_contains( $page_link, 'class="prev' ) ) {
				$item_class .= ' pagination-item--prev';
			} elseif ( str_contains( $page_link, 'class="next' ) ) {
				$item_class .= ' pagination-item--next';
			} elseif ( str_contains( $page_link, 'page-numbers current' ) ) {
				$item_class .= ' pagination-item--current';
			} elseif ( str_contains( $page_link, 'page-numbers dots' ) ) {
				$item_class .= ' pagination-item--dots';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<?php echo wp_kses_post( $page_link ); ?>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
