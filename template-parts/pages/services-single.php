<?php
/**
 * Page: Services Single
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! have_posts() ) {
	return;
}

the_post();

$service_id = get_the_ID();
$short_desc = get_field( 'short_description', $service_id );
$icon_name  = get_field( 'ui_icon', $service_id );
$thumb_id   = get_post_thumbnail_id( $service_id );

$cta_heading   = get_field( 'cta_heading', 'option' );
$cta_subtext   = get_field( 'cta_subtext', 'option' );
$cta_btn_label = get_field( 'primary_button_label', 'option' );
$cta_btn_url   = get_field( 'primary_button_url', 'option' );

$related_query = aidriven_get_related_services( $service_id );
?>

<main id="main-content" class="service-single-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title' => get_the_title(),
		)
	);
	?>

	<?php if ( $thumb_id ) : ?>
		<div class="service-single-hero">
			<?php
			echo wp_get_attachment_image(
				$thumb_id,
				'hero-full',
				false,
				array(
					'class'   => 'service-single-hero-img',
					'loading' => 'eager',
					'alt'     => esc_attr( get_the_title() ),
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="service-single-body">

		<div class="service-single-content entry-content">
			<?php the_content(); ?>
		</div>

		<?php if ( $short_desc || $icon_name ) : ?>
			<aside class="service-single-summary" aria-label="<?php esc_attr_e( 'Service Summary', 'ai-driven-boilerplate' ); ?>">

				<?php if ( $icon_name ) : ?>
					<span class="service-single-summary-icon" aria-hidden="true">
						<?php echo aidriven_get_svg_icon( $icon_name ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme files ?>
					</span>
				<?php endif; ?>

				<?php if ( $short_desc ) : ?>
					<p class="service-single-summary-desc"><?php echo esc_html( $short_desc ); ?></p>
				<?php endif; ?>

			</aside>
		<?php endif; ?>

	</div><!-- .service-single-body -->

	<?php if ( $related_query->have_posts() ) : ?>
		<section class="service-single-related">
			<div class="service-single-related-inner">

				<h2 class="service-single-related-heading">
					<?php esc_html_e( 'Related Services', 'ai-driven-boilerplate' ); ?>
				</h2>

				<ul class="service-single-related-grid" role="list">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();
						?>
						<li>
							<?php
							get_template_part(
								'template-parts/components/card',
								null,
								array(
									'image_id'  => get_post_thumbnail_id(),
									'title'     => get_the_title(),
									'title_url' => get_permalink(),
									'excerpt'   => get_field( 'short_description' ) ? get_field( 'short_description' ) : get_the_excerpt(),
									'cta_label' => __( 'Learn More', 'ai-driven-boilerplate' ),
									'cta_url'   => get_permalink(),
								)
							);
							?>
						</li>
						<?php
					}
					wp_reset_postdata();
					?>
				</ul>

			</div>
		</section>
	<?php endif; ?>

	<?php if ( $cta_heading ) : ?>
		<section class="service-single-cta">
			<div class="service-single-cta-inner">

				<h2 class="service-single-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>

				<?php if ( $cta_subtext ) : ?>
					<p class="service-single-cta-subtext"><?php echo esc_html( $cta_subtext ); ?></p>
				<?php endif; ?>

				<?php if ( $cta_btn_label && $cta_btn_url ) : ?>
					<div class="service-single-cta-actions">
						<?php
						get_template_part(
							'template-parts/components/button',
							null,
							array(
								'label'   => $cta_btn_label,
								'url'     => $cta_btn_url,
								'variant' => 'primary',
								'size'    => 'lg',
							)
						);
						?>
					</div>
				<?php endif; ?>

			</div>
		</section>
	<?php endif; ?>

</main><!-- #main-content -->
