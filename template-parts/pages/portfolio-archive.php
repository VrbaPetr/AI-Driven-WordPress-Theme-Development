<?php
/**
 * Page: Portfolio Archive
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$portfolio_cats = get_terms(
	array(
		'taxonomy'   => 'portfolio-category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);
?>
<main id="main-content" class="portfolio-archive-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title' => post_type_archive_title( '', false ),
		)
	);
	?>

	<div
		class="portfolio-archive-inner"
		x-data="{ activeFilter: 'all' }"
	>

		<?php if ( ! is_wp_error( $portfolio_cats ) && ! empty( $portfolio_cats ) ) : ?>
			<div class="portfolio-archive-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'ai-driven-boilerplate' ); ?>">

				<button
					type="button"
					class="portfolio-filter-btn"
					:class="{ 'is-active': activeFilter === 'all' }"
					:aria-pressed="activeFilter === 'all' ? 'true' : 'false'"
					@click="activeFilter = 'all'"
				>
					<?php esc_html_e( 'All', 'ai-driven-boilerplate' ); ?>
				</button>

				<?php foreach ( $portfolio_cats as $portfolio_cat ) : ?>
					<button
						type="button"
						class="portfolio-filter-btn"
						:class="{ 'is-active': activeFilter === '<?php echo esc_js( $portfolio_cat->slug ); ?>' }"
						:aria-pressed="activeFilter === '<?php echo esc_js( $portfolio_cat->slug ); ?>' ? 'true' : 'false'"
						@click="activeFilter = '<?php echo esc_js( $portfolio_cat->slug ); ?>'"
					>
						<?php echo esc_html( $portfolio_cat->name ); ?>
					</button>
				<?php endforeach; ?>

			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<ul
				class="portfolio-archive-grid"
				role="list"
				aria-live="polite"
				aria-atomic="false"
			>
				<?php
				while ( have_posts() ) {
					the_post();

					$terms = get_the_terms( get_the_ID(), 'portfolio-category' );

					$cat_name  = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
					$cat_slugs = array();

					if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $portfolio_term ) {
							$cat_slugs[] = $portfolio_term->slug;
						}
					}

					get_template_part(
						'template-parts/components/portfolio-card',
						null,
						array(
							'post_id'       => get_the_ID(),
							'with_alpine'   => true,
							'categories'    => implode( ',', $cat_slugs ),
							'category_name' => $cat_name,
						)
					);
				}
				?>
			</ul>

			<?php
			get_template_part( 'template-parts/components/pagination' );
			?>

		<?php else : ?>
			<p class="portfolio-archive-empty">
				<?php esc_html_e( 'No projects found.', 'ai-driven-boilerplate' ); ?>
			</p>
		<?php endif; ?>

	</div><!-- .portfolio-archive-inner -->

</main><!-- #main-content -->
