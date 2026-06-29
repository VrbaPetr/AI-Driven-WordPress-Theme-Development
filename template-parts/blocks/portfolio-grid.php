<?php
/**
 * Block: Portfolio Grid
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading     = get_field( 'section_heading' );
	$initial_items_count = get_field( 'initial_items_count' );
	$items_per_load      = get_field( 'items_per_load' );
	$show_filter_buttons = get_field( 'show_filter_buttons' );
	$show_load_more      = get_field( 'show_load_more' );

	// Defaults.
	$initial_items_count = $initial_items_count ? absint( $initial_items_count ) : 6;
	$items_per_load      = $items_per_load ? absint( $items_per_load ) : 3;
	$show_load_more      = ( false !== $show_load_more ) ? (bool) $show_load_more : true;

	// Initial query.
	$result          = ai_driven_get_portfolio_posts( 1, $initial_items_count, '' );
	$portfolio_posts = $result['posts'];

	if ( empty( $portfolio_posts ) ) {
		return;
	}

	$has_more = $result['has_more'];

	// Load portfolio categories that have at least one published post.
	$filter_terms = array();
	if ( $show_filter_buttons ) {
		$filter_terms = get_terms(
			array(
				'taxonomy'   => 'portfolio-category',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			)
		);
		if ( is_wp_error( $filter_terms ) ) {
			$filter_terms = array();
		}
	}

	$ajax_url = admin_url( 'admin-ajax.php' );
	$nonce    = wp_create_nonce( 'ai_driven_load_portfolio' );
	?>
	<section
		class="portfolio-grid-block"
		x-data="portfolioFilter"
		data-ajax-url="<?php echo esc_url( $ajax_url ); ?>"
		data-nonce="<?php echo esc_attr( $nonce ); ?>"
		data-per-page="<?php echo esc_attr( $items_per_load ); ?>"
		data-has-more="<?php echo esc_attr( $has_more ? 'true' : 'false' ); ?>"
	>
		<div class="portfolio-grid-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
				<h2 class="portfolio-grid-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $filter_terms ) ) : ?>
				<div class="portfolio-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'ai-driven-boilerplate' ); ?>">
					<button
						type="button"
						class="portfolio-filter-btn"
						:class="{ 'is-active': activeFilter === 'all' }"
						:aria-pressed="activeFilter === 'all' ? 'true' : 'false'"
						@click="setFilter('all')"
					>
						<?php esc_html_e( 'All', 'ai-driven-boilerplate' ); ?>
					</button>
					<?php foreach ( $filter_terms as $filter_term ) : ?>
						<button
							type="button"
							class="portfolio-filter-btn"
							:class="{ 'is-active': activeFilter === '<?php echo esc_js( $filter_term->slug ); ?>' }"
							:aria-pressed="activeFilter === '<?php echo esc_js( $filter_term->slug ); ?>' ? 'true' : 'false'"
							@click="setFilter('<?php echo esc_js( $filter_term->slug ); ?>')"
						>
							<?php echo esc_html( $filter_term->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<ul
				class="portfolio-grid"
				role="list"
				x-ref="grid"
				aria-live="polite"
				aria-atomic="false"
			>
				<?php foreach ( $portfolio_posts as $post_data ) : ?>
					<?php
					get_template_part(
						'template-parts/components/portfolio-card',
						null,
						array(
							'post_id'       => $post_data['id'],
							'with_alpine'   => true,
							'categories'    => $post_data['categories'],
							'category_name' => $post_data['category_name'],
						)
					);
					?>
				<?php endforeach; ?>
			</ul>

			<?php if ( $show_load_more ) : ?>
				<div class="portfolio-load-more" x-show="hasMore">
					<button
						type="button"
						class="portfolio-load-more-btn"
						@click="loadMore()"
						:disabled="isLoading"
						:aria-busy="isLoading ? 'true' : 'false'"
					>
						<span x-show="! isLoading"><?php esc_html_e( 'Load More', 'ai-driven-boilerplate' ); ?></span>
						<span x-show="isLoading" aria-live="polite"><?php esc_html_e( 'Loading…', 'ai-driven-boilerplate' ); ?></span>
					</button>
				</div>
			<?php endif; ?>

		</div>
	</section>
<?php endif; ?>
