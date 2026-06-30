<?php
/**
 * Block: Process
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$section_heading    = get_field( 'section_heading' );
	$section_subheading = get_field( 'section_subheading' );
	$layout             = get_field( 'layout' );
	$steps              = get_field( 'steps' );

	$layout = $layout ? $layout : 'horizontal';

	if ( empty( $steps ) ) {
		return;
	}

	$section_classes = 'process';
	if ( 'vertical' === $layout ) {
		$section_classes .= ' process--vertical';
	} else {
		$section_classes .= ' process--horizontal';
	}

	$has_header = ! empty( $section_heading ) || ! empty( $section_subheading );
	?>
	<section class="<?php echo esc_attr( $section_classes ); ?>">
		<div class="process-inner">
			<?php if ( $has_header ) : ?>
			<div class="process-header">
				<?php if ( ! empty( $section_heading ) ) : ?>
				<h2 class="process-heading"><?php echo esc_html( $section_heading ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section_subheading ) ) : ?>
				<p class="process-subheading"><?php echo esc_html( $section_subheading ); ?></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<ol class="process-steps">
				<?php
				$step_index = 0;
				foreach ( $steps as $step ) :
					++$step_index;
					$icon        = isset( $step['ui_icon'] ) ? $step['ui_icon'] : '';
					$step_title  = isset( $step['title'] ) ? $step['title'] : '';
					$description = isset( $step['description'] ) ? $step['description'] : '';

					if ( empty( $step_title ) ) {
						continue;
					}

					$step_number = str_pad( $step_index, 2, '0', STR_PAD_LEFT );
					?>
				<li class="process-step">
					<div class="process-step-badge" aria-hidden="true">
						<span class="process-step-number"><?php echo esc_html( $step_number ); ?></span>
					</div>
					<div class="process-step-body">
						<?php
						if ( ! empty( $icon ) ) :
							$icon_path = aidriven_get_icon_path( $icon );
							if ( $icon_path ) :
								?>
						<span class="process-step-icon" aria-hidden="true"><?php include $icon_path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable ?></span>
							<?php endif; ?>
						<?php endif; ?>
						<h3 class="process-step-title"><?php echo esc_html( $step_title ); ?></h3>
						<?php if ( ! empty( $description ) ) : ?>
						<p class="process-step-desc"><?php echo esc_html( $description ); ?></p>
						<?php endif; ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>
<?php endif; ?>
