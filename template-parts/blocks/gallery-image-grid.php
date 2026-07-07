<?php
/**
 * Block: Gallery / Image Grid
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$images             = get_field( 'images' );
	$columns            = get_field( 'columns' );
	$enable_lightbox    = get_field( 'enable_lightbox' );

	if ( empty( $images ) ) {
		return;
	}

	$columns = $columns ? $columns : '3';

	$alt_texts = array_map(
		function ( $image ) {
			return ! empty( $image['alt'] ) ? $image['alt'] : $image['title'];
		},
		$images
	);
	?>
	<section class="gallery-image-grid-block">
		<div class="gallery-image-grid-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="gallery-image-grid-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_subheading ) ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div class="gallery-image-grid" data-columns="<?php echo esc_attr( $columns ); ?>">
				<?php foreach ( $images as $image_index => $image ) : ?>
					<?php
					$image_attr = array(
						'loading' => 'lazy',
						'alt'     => $alt_texts[ $image_index ],
					);

					if ( $enable_lightbox ) {
						$image_attr['class']         = 'gallery-image-grid-trigger';
						$image_attr['data-lightbox'] = $image['url'];
					}
					?>
					<figure class="gallery-image-grid-item">
						<?php echo wp_get_attachment_image( $image['ID'], 'gallery-grid', false, $image_attr ); ?>
					</figure>
				<?php endforeach; ?>
			</div>

		</div>
	</section>
<?php endif; ?>
