<?php
/**
 * Page: Portfolio Single
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

$project_id = get_the_ID();

// ACF fields.
$client_name   = get_field( 'client_name', $project_id );
$project_year  = get_field( 'project_year', $project_id );
$services_used = get_field( 'services_used', $project_id );
$live_url      = get_field( 'live_project_url', $project_id );
$challenge     = get_field( 'challenge', $project_id );
$solution      = get_field( 'solution', $project_id );
$results       = get_field( 'results', $project_id );
$gallery       = get_field( 'gallery', $project_id );
$thumb_id      = get_post_thumbnail_id( $project_id );

// Category name for page-header subtitle.
$terms    = get_the_terms( $project_id, 'portfolio-category' );
$cat_name = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->name : '';

// Global CTA options.
$cta_heading   = get_field( 'cta_heading', 'option' );
$cta_subtext   = get_field( 'cta_subtext', 'option' );
$cta_btn_label = get_field( 'primary_button_label', 'option' );
$cta_btn_url   = get_field( 'primary_button_url', 'option' );

$related_query = aidriven_get_related_projects( $project_id );

$has_meta = $client_name || $project_year || ! empty( $services_used ) || $live_url;
?>

<main id="main-content" class="project-single-main">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'    => get_the_title(),
			'subtitle' => $cat_name,
		)
	);
	?>

	<?php if ( $thumb_id ) : ?>
		<div class="project-single-hero">
			<?php
			echo wp_get_attachment_image(
				$thumb_id,
				'hero-full',
				false,
				array(
					'class'   => 'project-single-hero-img',
					'loading' => 'eager',
					'alt'     => esc_attr( get_the_title() ),
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="project-single-body">

		<div class="project-single-content">

			<?php if ( get_the_content() ) : ?>
				<div class="project-single-editor entry-content">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

			<?php if ( $challenge ) : ?>
				<div class="project-single-section">
					<h2 class="project-single-section-heading"><?php esc_html_e( 'The Challenge', 'ai-driven-boilerplate' ); ?></h2>
					<div class="project-single-section-body entry-content">
						<?php echo wp_kses_post( $challenge ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $solution ) : ?>
				<div class="project-single-section">
					<h2 class="project-single-section-heading"><?php esc_html_e( 'Our Solution', 'ai-driven-boilerplate' ); ?></h2>
					<div class="project-single-section-body entry-content">
						<?php echo wp_kses_post( $solution ); ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $results ) : ?>
				<div class="project-single-section">
					<h2 class="project-single-section-heading"><?php esc_html_e( 'The Results', 'ai-driven-boilerplate' ); ?></h2>
					<div class="project-single-section-body entry-content">
						<?php echo wp_kses_post( $results ); ?>
					</div>
				</div>
			<?php endif; ?>

		</div><!-- .project-single-content -->

		<?php if ( $has_meta ) : ?>
			<aside class="project-single-meta" aria-label="<?php esc_attr_e( 'Project Details', 'ai-driven-boilerplate' ); ?>">

				<h2 class="project-single-meta-heading"><?php esc_html_e( 'Project Details', 'ai-driven-boilerplate' ); ?></h2>

				<dl class="project-single-meta-list">

					<?php if ( $client_name ) : ?>
						<div class="project-single-meta-item">
							<dt class="project-single-meta-term"><?php esc_html_e( 'Client', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="project-single-meta-desc"><?php echo esc_html( $client_name ); ?></dd>
						</div>
					<?php endif; ?>

					<?php if ( $project_year ) : ?>
						<div class="project-single-meta-item">
							<dt class="project-single-meta-term"><?php esc_html_e( 'Year', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="project-single-meta-desc"><?php echo esc_html( $project_year ); ?></dd>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $services_used ) ) : ?>
						<div class="project-single-meta-item">
							<dt class="project-single-meta-term"><?php esc_html_e( 'Services', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="project-single-meta-desc">
								<ul class="project-single-meta-services">
									<?php foreach ( $services_used as $service_row ) : ?>
										<?php if ( ! empty( $service_row['service_name'] ) ) : ?>
											<li><?php echo esc_html( $service_row['service_name'] ); ?></li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							</dd>
						</div>
					<?php endif; ?>

					<?php if ( $live_url ) : ?>
						<div class="project-single-meta-item">
							<dt class="project-single-meta-term"><?php esc_html_e( 'Live Project', 'ai-driven-boilerplate' ); ?></dt>
							<dd class="project-single-meta-desc">
								<a href="<?php echo esc_url( $live_url ); ?>" class="project-single-meta-link" target="_blank" rel="noopener noreferrer">
									<?php esc_html_e( 'View Project', 'ai-driven-boilerplate' ); ?>
									<span class="screen-reader-text"><?php esc_html_e( '(opens in new tab)', 'ai-driven-boilerplate' ); ?></span>
								</a>
							</dd>
						</div>
					<?php endif; ?>

				</dl>

			</aside>
		<?php endif; ?>

	</div><!-- .project-single-body -->

	<?php if ( ! empty( $gallery ) ) : ?>
		<section class="project-single-gallery">
			<div class="project-single-gallery-inner">

				<h2 class="project-single-gallery-heading"><?php esc_html_e( 'Project Gallery', 'ai-driven-boilerplate' ); ?></h2>

				<ul class="project-single-gallery-grid" role="list">
					<?php foreach ( $gallery as $gallery_image ) : ?>
						<li class="project-single-gallery-item">
							<?php
							echo wp_get_attachment_image(
								$gallery_image['ID'],
								'medium_large',
								false,
								array(
									'class'   => 'project-single-gallery-img',
									'loading' => 'lazy',
								)
							);
							?>
						</li>
					<?php endforeach; ?>
				</ul>

			</div>
		</section>
	<?php endif; ?>

	<?php if ( $related_query->have_posts() ) : ?>
		<section class="project-single-related">
			<div class="project-single-related-inner">

				<h2 class="project-single-related-heading"><?php esc_html_e( 'Related Projects', 'ai-driven-boilerplate' ); ?></h2>

				<ul class="project-single-related-grid" role="list">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();

						$rel_terms = get_the_terms( get_the_ID(), 'portfolio-category' );
						$rel_cat   = ( ! is_wp_error( $rel_terms ) && ! empty( $rel_terms ) ) ? $rel_terms[0]->name : '';
						$rel_slugs = array();

						if ( ! is_wp_error( $rel_terms ) && ! empty( $rel_terms ) ) {
							foreach ( $rel_terms as $rel_term ) {
								$rel_slugs[] = $rel_term->slug;
							}
						}

						get_template_part(
							'template-parts/components/portfolio-card',
							null,
							array(
								'post_id'       => get_the_ID(),
								'with_alpine'   => false,
								'categories'    => implode( ',', $rel_slugs ),
								'category_name' => $rel_cat,
							)
						);
					}
					wp_reset_postdata();
					?>
				</ul>

			</div>
		</section>
	<?php endif; ?>

	<?php if ( $cta_heading ) : ?>
		<section class="project-single-cta">
			<div class="project-single-cta-inner">

				<h2 class="project-single-cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>

				<?php if ( $cta_subtext ) : ?>
					<p class="project-single-cta-subtext"><?php echo esc_html( $cta_subtext ); ?></p>
				<?php endif; ?>

				<?php if ( $cta_btn_label && $cta_btn_url ) : ?>
					<div class="project-single-cta-actions">
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
