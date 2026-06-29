<?php
/**
 * Block: Clients
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading = get_field( 'section_heading' );
	$subtext         = get_field( 'subtext' );
	$display_style   = get_field( 'display_style' );
	$colour_mode     = get_field( 'colour_mode' );
	$logos           = get_field( 'logos' );

	// Defaults.
	$display_style = $display_style ? $display_style : 'static_grid';
	$colour_mode   = $colour_mode ? $colour_mode : 'greyscale';

	if ( empty( $logos ) ) {
		return;
	}

	$is_marquee   = ( 'marquee' === $display_style );
	$colour_class = 'clients--' . $colour_mode;
	?>
	<section class="clients-block <?php echo esc_attr( $colour_class ); ?>">
		<div class="clients-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="clients-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $subtext ) ) : ?>
			<p class="clients-subtext"><?php echo esc_html( $subtext ); ?></p>
			<?php endif; ?>

			<?php if ( $is_marquee ) : ?>
			<div class="clients-marquee">
				<div class="clients-marquee-track">

					<ul class="clients-logo-list" role="list">
						<?php foreach ( $logos as $logo ) : ?>
							<?php
							$logo_image = isset( $logo['logo_image'] ) ? $logo['logo_image'] : null;
							$alt_text   = isset( $logo['alt_text'] ) ? trim( $logo['alt_text'] ) : '';
							$link_url   = isset( $logo['link_url'] ) ? $logo['link_url'] : '';

							if ( empty( $logo_image ) ) {
								continue;
							}

							$img_alt = ! empty( $alt_text ) ? $alt_text : $logo_image['alt'];
							?>
							<li class="clients-logo-item">
								<?php if ( ! empty( $link_url ) ) : ?>
								<a href="<?php echo esc_url( $link_url ); ?>" class="clients-logo-link" aria-label="<?php echo esc_attr( $img_alt ); ?>">
									<?php
									echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										$logo_image['ID'],
										'medium',
										false,
										array(
											'alt'   => $img_alt,
											'class' => 'clients-logo-img',
										)
									);
									?>
								</a>
								<?php else : ?>
									<?php
									echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										$logo_image['ID'],
										'medium',
										false,
										array(
											'alt'   => $img_alt,
											'class' => 'clients-logo-img',
										)
									);
									?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php /* Duplicate set — visual only; hidden from assistive technology and keyboard navigation. */ ?>
					<ul class="clients-logo-list" role="list" aria-hidden="true">
						<?php foreach ( $logos as $logo ) : ?>
							<?php
							$logo_image = isset( $logo['logo_image'] ) ? $logo['logo_image'] : null;
							$link_url   = isset( $logo['link_url'] ) ? $logo['link_url'] : '';

							if ( empty( $logo_image ) ) {
								continue;
							}
							?>
							<li class="clients-logo-item">
								<?php if ( ! empty( $link_url ) ) : ?>
								<a href="<?php echo esc_url( $link_url ); ?>" class="clients-logo-link" tabindex="-1">
									<?php
									echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										$logo_image['ID'],
										'medium',
										false,
										array(
											'alt'   => '',
											'class' => 'clients-logo-img',
										)
									);
									?>
								</a>
								<?php else : ?>
									<?php
									echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										$logo_image['ID'],
										'medium',
										false,
										array(
											'alt'   => '',
											'class' => 'clients-logo-img',
										)
									);
									?>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>

				</div>
			</div>
			<?php else : ?>
			<ul class="clients-grid" role="list">
				<?php foreach ( $logos as $logo ) : ?>
					<?php
					$logo_image = isset( $logo['logo_image'] ) ? $logo['logo_image'] : null;
					$alt_text   = isset( $logo['alt_text'] ) ? trim( $logo['alt_text'] ) : '';
					$link_url   = isset( $logo['link_url'] ) ? $logo['link_url'] : '';

					if ( empty( $logo_image ) ) {
						continue;
					}

					$img_alt = ! empty( $alt_text ) ? $alt_text : $logo_image['alt'];
					?>
					<li class="clients-logo-item">
						<?php if ( ! empty( $link_url ) ) : ?>
						<a href="<?php echo esc_url( $link_url ); ?>" class="clients-logo-link" aria-label="<?php echo esc_attr( $img_alt ); ?>">
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$logo_image['ID'],
								'medium',
								false,
								array(
									'alt'   => $img_alt,
									'class' => 'clients-logo-img',
								)
							);
							?>
						</a>
						<?php else : ?>
							<?php
							echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								$logo_image['ID'],
								'medium',
								false,
								array(
									'alt'   => $img_alt,
									'class' => 'clients-logo-img',
								)
							);
							?>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

		</div>
	</section>
<?php endif; ?>
