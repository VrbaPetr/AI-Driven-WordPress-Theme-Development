<?php
/**
 * Block: Text Content
 *
 * @package ai-driven-boilerplate
 */

if ( isset( $block['data']['preview_screenshot'] ) ) :
	echo '<img src="' . esc_url( $block['data']['preview_screenshot'] ) . '" style="width:100%; height:auto;">';
else :

	// Fields.
	$content    = get_field( 'content' );
	$width      = get_field( 'width' );
	$text_align = get_field( 'text_align' );

	if ( empty( $content ) ) {
		return;
	}

	// Defaults.
	$width      = $width ? $width : 'default';
	$text_align = $text_align ? $text_align : 'left';

	$width_map   = array(
		'narrow'  => 'max-w-prose',
		'default' => 'max-w-3xl',
		'wide'    => 'max-w-none',
	);
	$width_class = isset( $width_map[ $width ] ) ? $width_map[ $width ] : $width_map['default'];
	$align_class = 'center' === $text_align ? 'text-center' : 'text-left';

	$wrapper_classes = 'mx-auto ' . $width_class . ' ' . $align_class;
	?>
	<section class="text-content">
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
			<div class="prose">
				<?php echo wp_kses_post( $content ); ?>
			</div>
		</div>
	</section>
<?php endif; ?>
