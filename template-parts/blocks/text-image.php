<?php
/**
 * Block: Text & Image
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$heading        = get_field( 'heading' );
	$body           = get_field( 'body' );
	$cta_label      = get_field( 'cta_label' );
	$cta_url        = get_field( 'cta_url' );
	$image          = get_field( 'image' );
	$image_position = get_field( 'image_position' );
	$image_position = $image_position ? $image_position : 'right';

	if ( empty( $heading ) ) {
		return;
	}

	$has_cta = ! empty( $cta_label ) && ! empty( $cta_url );

	$section_classes = 'text-image';
	if ( 'left' === $image_position ) {
		$section_classes .= ' text-image--image-left';
	}
	?>
	<section class="<?php echo esc_attr( $section_classes ); ?>">
		<div class="text-image-inner">
			<div class="text-image-content">
				<h2 class="text-image-heading"><?php echo esc_html( $heading ); ?></h2>
				<?php if ( ! empty( $body ) ) : ?>
				<div class="text-image-body"><?php echo wp_kses_post( $body ); ?></div>
				<?php endif; ?>
				<?php if ( $has_cta ) : ?>
				<div class="text-image-cta">
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => $cta_label,
							'url'     => $cta_url,
							'variant' => 'primary',
						)
					);
					?>
				</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $image ) ) : ?>
			<div class="text-image-media">
				<?php echo wp_get_attachment_image( $image['ID'], 'hero-split' ); ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
<?php endif; ?>
