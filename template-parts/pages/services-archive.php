<?php
/**
 * Page: Services Archive
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$service_cats = get_terms(
	array(
		'taxonomy'   => 'service-category',
		'hide_empty' => true,
	)
);

$active_cat  = get_query_var( 'service_cat', '' );
$archive_url = get_post_type_archive_link( 'service' );
?>
<main id="main-content" class="services-archive-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title' => post_type_archive_title( '', false ),
		)
	);
	?>

	<div class="services-archive-inner">

		<?php if ( ! is_wp_error( $service_cats ) && ! empty( $service_cats ) ) : ?>
			<div class="services-archive-filters" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'ai-driven-boilerplate' ); ?>">

				<a href="<?php echo esc_url( $archive_url ); ?>"
					class="btn btn-sm <?php echo $active_cat ? 'btn-ghost' : 'btn-primary'; ?>"
					<?php echo ! $active_cat ? 'aria-current="true"' : ''; ?>>
					<?php esc_html_e( 'All', 'ai-driven-boilerplate' ); ?>
				</a>

				<?php foreach ( $service_cats as $service_cat ) : ?>
					<?php $is_active = ( $active_cat === $service_cat->slug ); ?>
					<a href="<?php echo esc_url( add_query_arg( 'service_cat', $service_cat->slug, $archive_url ) ); ?>"
						class="btn btn-sm <?php echo $is_active ? 'btn-primary' : 'btn-ghost'; ?>"
						<?php echo $is_active ? 'aria-current="true"' : ''; ?>>
						<?php echo esc_html( $service_cat->name ); ?>
					</a>
				<?php endforeach; ?>

			</div>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<ul class="services-archive-grid" role="list">
				<?php
				while ( have_posts() ) {
					the_post();

					$terms    = get_the_terms( get_the_ID(), 'service-category' );
					$cat_name = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';
					$cat_link = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? get_term_link( $terms[0] ) : '';
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
								'category_url' => ! is_wp_error( $cat_link ) ? $cat_link : '',
								'excerpt'      => get_field( 'short_description' ) ? get_field( 'short_description' ) : get_the_excerpt(),
								'cta_label'    => __( 'Learn More', 'ai-driven-boilerplate' ),
								'cta_url'      => get_permalink(),
							)
						);
						?>
					</li>
					<?php
				}
				?>
			</ul>

			<?php
			get_template_part( 'template-parts/components/pagination' );
			?>

		<?php else : ?>
			<p class="services-archive-empty">
				<?php esc_html_e( 'No services found.', 'ai-driven-boilerplate' ); ?>
			</p>
		<?php endif; ?>

	</div><!-- .services-archive-inner -->

</main><!-- #main-content -->
