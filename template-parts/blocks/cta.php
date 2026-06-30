<?php
/**
 * Block: CTA
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$cta_heading        = get_field( 'heading' );
	$section_subheading = get_field( 'section_subheading' );
	$cta_subtext        = get_field( 'subtext' );
	$primary_label      = get_field( 'primary_label' );
	$primary_url        = get_field( 'primary_url' );
	$secondary_label    = get_field( 'secondary_label' );
	$secondary_url      = get_field( 'secondary_url' );
	$bg_style           = get_field( 'background_style' );
	$bg_colour          = get_field( 'background_colour' );
	$gradient_start     = get_field( 'gradient_start' );
	$gradient_end       = get_field( 'gradient_end' );
	$gradient_direction = get_field( 'gradient_direction' );
	$cta_text_colour    = get_field( 'text_colour' );

	if ( empty( $cta_heading ) || empty( $primary_label ) ) {
		return;
	}

	$bg_style        = $bg_style ? $bg_style : 'solid';
	$cta_text_colour = $cta_text_colour ? $cta_text_colour : 'light';

	$allowed_directions = array( 'to right', 'to bottom right', 'to bottom' );
	$gradient_direction = ( $gradient_direction && in_array( $gradient_direction, $allowed_directions, true ) )
		? $gradient_direction
		: 'to right';

	// Build inline background style.
	$inline_style = '';
	if ( 'gradient' === $bg_style ) {
		$grad_start = sanitize_hex_color( $gradient_start );
		$grad_end   = sanitize_hex_color( $gradient_end );
		if ( $grad_start && $grad_end ) {
			$inline_style = 'background: linear-gradient(' . $gradient_direction . ', ' . $grad_start . ', ' . $grad_end . ');';
		}
	} else {
		$solid_colour = sanitize_hex_color( $bg_colour );
		if ( $solid_colour ) {
			$inline_style = 'background-color: ' . $solid_colour . ';';
		}
	}

	// Section classes.
	$section_classes = 'cta';
	if ( 'dark' === $cta_text_colour ) {
		$section_classes .= ' cta--dark-text';
	} else {
		$section_classes .= ' cta--light-text';
	}

	?>
	<section class="<?php echo esc_attr( $section_classes ); ?>"<?php echo $inline_style ? ' style="' . esc_attr( $inline_style ) . '"' : ''; ?>>
		<div class="cta-inner">
			<h2 class="cta-heading"><?php echo esc_html( $cta_heading ); ?></h2>
			<?php if ( $section_subheading ) : ?>
			<p class="block-subheading"><?php echo esc_html( $section_subheading ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $cta_subtext ) ) : ?>
			<p class="cta-subtext"><?php echo esc_html( $cta_subtext ); ?></p>
			<?php endif; ?>
			<div class="cta-buttons">
				<?php
				get_template_part(
					'template-parts/components/button',
					null,
					array(
						'label'   => $primary_label,
						'url'     => $primary_url,
						'variant' => 'primary',
						'size'    => 'lg',
					)
				);
				?>
				<?php if ( ! empty( $secondary_label ) ) : ?>
					<?php
					get_template_part(
						'template-parts/components/button',
						null,
						array(
							'label'   => $secondary_label,
							'url'     => $secondary_url,
							'variant' => 'ghost',
							'size'    => 'lg',
						)
					);
					?>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
