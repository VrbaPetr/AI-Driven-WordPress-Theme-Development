<?php
/**
 * Block: Stats
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading = get_field( 'section_heading' );
	$stats           = get_field( 'stats' );

	if ( empty( $stats ) ) {
		return;
	}
	?>
	<section class="stats">
		<div class="stats-inner">
			<?php if ( ! empty( $section_heading ) ) : ?>
			<h2 class="stats-heading"><?php echo esc_html( $section_heading ); ?></h2>
			<?php endif; ?>
			<div class="stats-grid">
				<?php
				foreach ( $stats as $stat ) :
					$number = isset( $stat['number'] ) ? $stat['number'] : '';
					$suffix = isset( $stat['suffix'] ) ? $stat['suffix'] : '';
					$label  = isset( $stat['label'] ) ? $stat['label'] : '';
					$icon   = isset( $stat['icon'] ) ? $stat['icon'] : '';

					if ( empty( $number ) || empty( $label ) ) {
						continue;
					}

					?>
				<div class="stat-item" aria-label="<?php echo esc_attr( $number . ( $suffix ? ' ' . $suffix : '' ) . ' ' . $label ); ?>">
					<?php
					if ( ! empty( $icon ) ) :
						$icon_name = sanitize_file_name( basename( $icon ) );
						$icon_path = get_template_directory() . '/assets/media/icons/' . $icon_name . '.svg';
						if ( file_exists( $icon_path ) ) :
							?>
					<span class="stat-icon" aria-hidden="true"><?php include $icon_path; ?></span>
						<?php endif; ?>
					<?php endif; ?>
					<div class="stat-number-wrap" aria-hidden="true">
						<span class="stat-number" data-counter data-target="<?php echo esc_attr( $number ); ?>" data-duration="1800">
							<?php echo esc_html( $number ); ?>
						</span>
						<?php if ( ! empty( $suffix ) ) : ?>
						<span class="stat-suffix"><?php echo esc_html( $suffix ); ?></span>
						<?php endif; ?>
					</div>
					<span class="stat-label" aria-hidden="true"><?php echo esc_html( $label ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
