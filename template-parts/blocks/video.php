<?php
/**
 * Block: Video
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$video_url          = get_field( 'video_url' );
	$thumbnail          = get_field( 'thumbnail' );
	$caption            = get_field( 'caption' );
	$aspect_ratio       = get_field( 'aspect_ratio' );

	$embed_url = $video_url ? aidriven_get_video_embed_url( $video_url ) : '';

	if ( empty( $embed_url ) ) {
		return;
	}

	// Defaults.
	$ratio_class  = ( '4:3' === $aspect_ratio ) ? 'video-ratio-4-3' : 'video-ratio-16-9';
	$iframe_title = $section_heading ? $section_heading : __( 'Video', 'ai-driven-boilerplate' );
	?>
	<section class="video-block">
		<div class="video-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="video-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $section_subheading ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<figure class="video-figure">
				<div class="video-facade <?php echo esc_attr( $ratio_class ); ?>" x-data="{ playing: false }">

					<div class="video-thumbnail-wrap" x-show="!playing">
						<?php if ( ! empty( $thumbnail ) ) : ?>
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$thumbnail['ID'],
								'video-thumbnail',
								false,
								array(
									'class'   => 'video-thumbnail',
									'loading' => 'eager',
									'alt'     => $thumbnail['alt'],
								)
							);
							?>
						<?php endif; ?>

						<button
							type="button"
							class="video-play-button"
							aria-label="<?php esc_attr_e( 'Play video', 'ai-driven-boilerplate' ); ?>"
							@click="playing = true"
						>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
								<polygon points="6 3 20 12 6 21 6 3"/>
							</svg>
						</button>
					</div>

					<template x-if="playing">
						<div class="video-iframe-wrap">
							<iframe
								src="<?php echo esc_url( $embed_url ); ?>"
								title="<?php echo esc_attr( $iframe_title ); ?>"
								allow="autoplay; fullscreen; picture-in-picture"
								allowfullscreen
							></iframe>
						</div>
					</template>

				</div>

				<?php if ( ! empty( $caption ) ) : ?>
				<figcaption class="video-caption"><?php echo esc_html( $caption ); ?></figcaption>
				<?php endif; ?>
			</figure>

		</div>
	</section>
<?php endif; ?>
