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

	if ( $enable_lightbox ) {
		$alpine_data = sprintf(
			'{
				lightboxOpen: false,
				currentIndex: 0,
				total: %1$d,
				open( index ) {
					this.currentIndex = index;
					this.lightboxOpen = true;
				},
				close() {
					this.lightboxOpen = false;
				},
				prev() {
					this.currentIndex = ( this.currentIndex - 1 + this.total ) %% this.total;
				},
				next() {
					this.currentIndex = ( this.currentIndex + 1 ) %% this.total;
				}
			}',
			absint( count( $images ) )
		);
	}
	?>
	<section class="gallery-image-grid-block">
		<div class="gallery-image-grid-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="gallery-image-grid-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_subheading ) ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<div
				class="gallery-image-grid"
				data-columns="<?php echo esc_attr( $columns ); ?>"
				<?php if ( $enable_lightbox ) : ?>
				x-data="<?php echo esc_attr( $alpine_data ); ?>"
				@keydown.escape.window="close()"
				<?php endif; ?>
			>
				<?php foreach ( $images as $image_index => $image ) : ?>
					<?php if ( $enable_lightbox ) : ?>
					<figure class="gallery-image-grid-item">
						<button
							type="button"
							class="gallery-image-grid-trigger"
							@click="open( <?php echo absint( $image_index ); ?> )"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %d: image number */ __( 'Open image %d in lightbox', 'ai-driven-boilerplate' ), $image_index + 1 ) ); ?>"
						>
							<?php
							echo wp_get_attachment_image(
								$image['ID'],
								'gallery-grid',
								false,
								array(
									'loading' => 'lazy',
									'alt'     => $alt_texts[ $image_index ],
								)
							);
							?>
						</button>
					</figure>
					<?php else : ?>
					<figure class="gallery-image-grid-item">
						<?php
						echo wp_get_attachment_image(
							$image['ID'],
							'gallery-grid',
							false,
							array(
								'loading' => 'lazy',
								'alt'     => $alt_texts[ $image_index ],
							)
						);
						?>
					</figure>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php if ( $enable_lightbox ) : ?>
			<div
				class="gallery-image-grid-lightbox"
				x-show="lightboxOpen"
				x-cloak
				role="dialog"
				aria-modal="true"
				aria-label="<?php esc_attr_e( 'Image lightbox', 'ai-driven-boilerplate' ); ?>"
				x-effect="if ( lightboxOpen ) { $nextTick( () => $refs.galleryLightboxClose.focus() ); }"
			>
				<button
					type="button"
					class="gallery-image-grid-lightbox-close"
					x-ref="galleryLightboxClose"
					@click="close()"
					aria-label="<?php esc_attr_e( 'Close lightbox', 'ai-driven-boilerplate' ); ?>"
				>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<line x1="18" y1="6" x2="6" y2="18"/>
						<line x1="6" y1="6" x2="18" y2="18"/>
					</svg>
				</button>

				<button
					type="button"
					class="gallery-image-grid-lightbox-prev"
					@click="prev()"
					aria-label="<?php esc_attr_e( 'Previous image', 'ai-driven-boilerplate' ); ?>"
				>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<polyline points="15 18 9 12 15 6"/>
					</svg>
				</button>

				<img
					class="gallery-image-grid-lightbox-image"
					:src='<?php echo wp_json_encode( array_column( $images, 'url' ) ); ?>[currentIndex]'
					:alt='<?php echo wp_json_encode( $alt_texts ); ?>[currentIndex]'
					loading="eager"
				/>

				<button
					type="button"
					class="gallery-image-grid-lightbox-next"
					@click="next()"
					aria-label="<?php esc_attr_e( 'Next image', 'ai-driven-boilerplate' ); ?>"
				>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
						<polyline points="9 18 15 12 9 6"/>
					</svg>
				</button>
			</div>
			<?php endif; ?>

		</div>
	</section>
<?php endif; ?>
