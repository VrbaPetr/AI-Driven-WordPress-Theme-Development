<?php
/**
 * Component: Card
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type int    $image_id     WP attachment ID for the thumbnail. Optional.
 *     @type string $image_size   Registered image size. Default 'card-thumbnail'.
 *     @type string $category     Category label text. Optional.
 *     @type string $category_url Category archive URL. Optional.
 *     @type string $title        Card title. Required. Mapped to $card_title internally to avoid WP global conflict.
 *     @type string $card_title_url    Permalink for the title link. Required. Mapped to $card_title_url internally.
 *     @type string $excerpt      Short description. Optional.
 *     @type string $cta_label    CTA link text. Optional.
 *     @type string $cta_url      CTA link href. Optional.
 *     @type string $meta         Optional meta line (e.g. "Jan 1, 2025 · 3 min read"). Rendered as small muted text.
 *     @type string $classes      Additional CSS classes on the root element.
 * }
 */

$image_id       = isset( $args['image_id'] ) ? (int) $args['image_id'] : 0;
$image_size     = $args['image_size'] ?? 'card-thumbnail';
$category       = $args['category'] ?? '';
$category_url   = $args['category_url'] ?? '';
$card_title     = $args['title'] ?? '';
$card_title_url = $args['title_url'] ?? '';
$excerpt        = $args['excerpt'] ?? '';
$meta           = $args['meta'] ?? '';
$cta_label      = $args['cta_label'] ?? '';
$cta_url        = $args['cta_url'] ?? '';
$classes        = $args['classes'] ?? '';

if ( empty( $card_title ) || empty( $card_title_url ) ) {
	return;
}

$card_class = 'card';
if ( $classes ) {
	$card_class .= ' ' . $classes;
}
?>

<article class="<?php echo esc_attr( $card_class ); ?>">

	<?php if ( $image_id ) : ?>
		<a href="<?php echo esc_url( $card_title_url ); ?>" class="card-image-link" tabindex="-1" aria-hidden="true">
			<?php
			echo wp_get_attachment_image(
				$image_id,
				$image_size,
				false,
				array(
					'class'   => 'card-image',
					'loading' => 'lazy',
				)
			);
			?>
		</a>
	<?php endif; ?>

	<div class="card-body">

		<?php if ( $category ) : ?>
			<div class="card-category">
				<?php if ( $category_url ) : ?>
					<a href="<?php echo esc_url( $category_url ); ?>" class="card-category-link">
						<span class="card-badge"><?php echo esc_html( $category ); ?></span>
					</a>
				<?php else : ?>
					<span class="card-badge"><?php echo esc_html( $category ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $meta ) : ?>
			<span class="card-meta"><?php echo esc_html( $meta ); ?></span>
		<?php endif; ?>

		<h3 class="card-title">
			<a href="<?php echo esc_url( $card_title_url ); ?>" class="card-title-link">
				<?php echo esc_html( $card_title ); ?>
			</a>
		</h3>

		<?php if ( $excerpt ) : ?>
			<p class="card-excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 20, '&hellip;' ) ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_label && $cta_url ) : ?>
			<div class="card-cta">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => $cta_label,
						'url'     => $cta_url,
						'variant' => 'ghost',
						'size'    => 'sm',
					)
				);
				?>
			</div>
		<?php endif; ?>

	</div>

</article>
