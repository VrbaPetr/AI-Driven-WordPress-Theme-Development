<?php
/**
 * Block: Awards & Certifications
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );

	if ( ! have_rows( 'awards' ) ) {
		return;
	}
	?>
	<section class="awards-cert-block">
		<div class="awards-cert-inner">

			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="awards-cert-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $section_subheading ) ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>

			<ul class="awards-cert-list" role="list">
				<?php
				while ( have_rows( 'awards' ) ) :
					the_row();

					$badge_image = get_sub_field( 'badge_image' );
					$award_name  = get_sub_field( 'award_name' );
					$issued_by   = get_sub_field( 'issued_by' );
					$award_year  = get_sub_field( 'year' );

					if ( empty( $award_name ) ) {
						continue;
					}
					?>
				<li class="awards-cert-item">
					<?php if ( ! empty( $badge_image ) ) : ?>
					<div class="awards-cert-badge">
						<?php
						echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$badge_image['ID'],
							'awards-badge',
							false,
							array(
								'alt' => ! empty( $badge_image['alt'] ) ? $badge_image['alt'] : $award_name,
							)
						);
						?>
					</div>
					<?php endif; ?>
					<p class="awards-cert-name"><strong><?php echo esc_html( $award_name ); ?></strong></p>
					<?php if ( ! empty( $issued_by ) ) : ?>
					<p class="awards-cert-issuer"><?php echo esc_html( $issued_by ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $award_year ) ) : ?>
					<p class="awards-cert-year"><?php echo esc_html( $award_year ); ?></p>
					<?php endif; ?>
				</li>
				<?php endwhile; ?>
			</ul>

		</div>
	</section>
<?php endif; ?>
