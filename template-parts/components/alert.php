<?php
/**
 * Component: Alert
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type string $message     Alert message text. Required.
 *     @type string $variant     Colour/type: 'info' | 'success' | 'warning' | 'error'. Default 'info'.
 *     @type bool   $dismissible Whether to render a dismiss button (Alpine.js). Default false.
 *     @type bool   $icon        Whether to show the variant icon. Default true.
 *     @type string $classes     Additional CSS classes.
 * }
 */

$message     = $args['message'] ?? '';
$variant     = $args['variant'] ?? 'info';
$dismissible = ! empty( $args['dismissible'] );
$show_icon   = isset( $args['icon'] ) ? (bool) $args['icon'] : true;
$classes     = $args['classes'] ?? '';

if ( empty( $message ) ) {
	return;
}

$allowed_variants = array( 'info', 'success', 'warning', 'error' );

if ( ! in_array( $variant, $allowed_variants, true ) ) {
	$variant = 'info';
}

$icon_map = array(
	'info'    => 'circle-info',
	'success' => 'circle-check',
	'warning' => 'triangle-alert',
	'error'   => 'circle-x',
);

$alert_class = 'alert alert-' . $variant;
if ( $classes ) {
	$alert_class .= ' ' . $classes;
}
?>

<div
	class="<?php echo esc_attr( $alert_class ); ?>"
	role="alert"
	<?php if ( $dismissible ) : ?>
		x-data="{ show: true }"
		x-show="show"
		x-transition
	<?php endif; ?>
>

	<?php if ( $show_icon && isset( $icon_map[ $variant ] ) ) : ?>
		<span class="alert-icon" aria-hidden="true">
			<?php echo aidriven_get_svg_icon( $icon_map[ $variant ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme files ?>
		</span>
	<?php endif; ?>

	<p class="alert-message"><?php echo wp_kses_post( $message ); ?></p>

	<?php if ( $dismissible ) : ?>
		<button
			type="button"
			class="alert-dismiss"
			aria-label="<?php esc_attr_e( 'Dismiss notification', 'ai-driven-boilerplate' ); ?>"
			@click="show = false"
		>&times;</button>
	<?php endif; ?>

</div>
