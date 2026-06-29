<?php
/**
 * Component: Portfolio Card
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type int    $post_id       Project post ID. Required.
 *     @type bool   $with_alpine   Add x-show Alpine.js directive for client-side filtering. Default false.
 *     @type string $categories    Comma-separated category slugs for data-categories attribute.
 *     @type string $category_name First category name for badge display.
 * }
 */

$card_post_id  = isset( $args['post_id'] ) ? absint( $args['post_id'] ) : 0;
$with_alpine   = ! empty( $args['with_alpine'] );
$categories    = isset( $args['categories'] ) ? $args['categories'] : '';
$category_name = isset( $args['category_name'] ) ? $args['category_name'] : '';

if ( ! $card_post_id ) {
	return;
}

$card_title  = get_the_title( $card_post_id );
$url         = get_permalink( $card_post_id );
$thumb_id    = get_post_thumbnail_id( $card_post_id );
$description = get_field( 'short_description', $card_post_id );

if ( empty( $card_title ) ) {
	return;
}
?>
<li class="portfolio-card"
	data-categories="<?php echo esc_attr( $categories ); ?>"
	<?php if ( $with_alpine ) : ?>
	x-show="activeFilter === 'all' || $el.dataset.categories.split(',').includes(activeFilter)"
	<?php endif; ?>
>
	<a href="<?php echo esc_url( $url ); ?>" class="portfolio-card-link" aria-label="<?php echo esc_attr( $card_title ); ?>">

		<div class="portfolio-card-thumb">
			<?php if ( $thumb_id ) : ?>
				<?php
				echo wp_get_attachment_image(
					$thumb_id,
					'portfolio-thumb',
					false,
					array(
						'class'   => 'portfolio-card-img',
						'loading' => 'lazy',
					)
				);
				?>
			<?php else : ?>
				<div class="portfolio-card-img-placeholder" aria-hidden="true"></div>
			<?php endif; ?>
			<div class="portfolio-card-overlay" aria-hidden="true">
				<span class="portfolio-card-overlay-title"><?php echo esc_html( $card_title ); ?></span>
				<svg class="portfolio-card-overlay-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
					<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
					<circle cx="12" cy="12" r="3"/>
				</svg>
			</div>
		</div>

		<div class="portfolio-card-body">
			<?php if ( ! empty( $category_name ) ) : ?>
				<?php
				get_template_part(
					'template-parts/components/badge',
					null,
					array(
						'label'   => $category_name,
						'variant' => 'primary',
					)
				);
				?>
			<?php endif; ?>
			<h3 class="portfolio-card-title"><?php echo esc_html( $card_title ); ?></h3>
			<?php if ( ! empty( $description ) ) : ?>
				<p class="portfolio-card-desc"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

	</a>
</li>
