<?php
/**
 * Component: Button
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type string $label       Button text. Required.
 *     @type string $url         Href — renders <a> when set, <button> otherwise.
 *     @type string $variant     Visual style: 'primary' | 'secondary' | 'outline' | 'ghost'. Default 'primary'.
 *     @type string $size        Size: 'sm' | 'md' | 'lg'. Default 'md'.
 *     @type string $icon_before Icon name (SVG filename without extension) rendered before label.
 *     @type string $icon_after  Icon name rendered after label.
 *     @type array  $attributes  Extra HTML attributes, e.g. ['target' => '_blank', 'type' => 'submit'].
 *     @type string $classes     Additional CSS classes appended to the element.
 * }
 */

$label       = $args['label'] ?? '';
$url         = $args['url'] ?? '';
$variant     = $args['variant'] ?? 'primary';
$size        = $args['size'] ?? 'md';
$icon_before = $args['icon_before'] ?? '';
$icon_after  = $args['icon_after'] ?? '';
$attributes  = $args['attributes'] ?? array();
$classes     = $args['classes'] ?? '';

if ( empty( $label ) ) {
	return;
}

$allowed_variants = array( 'primary', 'secondary', 'outline', 'ghost' );
$allowed_sizes    = array( 'sm', 'md', 'lg' );

if ( ! in_array( $variant, $allowed_variants, true ) ) {
	$variant = 'primary';
}

if ( ! in_array( $size, $allowed_sizes, true ) ) {
	$size = 'md';
}

$btn_class = 'btn btn-' . $variant . ' btn-' . $size;
if ( $classes ) {
	$btn_class .= ' ' . $classes;
}

$extra_attrs = '';
foreach ( $attributes as $attr_name => $attr_value ) {
	$extra_attrs .= ' ' . esc_attr( $attr_name ) . '="' . esc_attr( $attr_value ) . '"';
}
?>

<?php if ( $url ) : ?>
<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $btn_class ); ?>"<?php echo $extra_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attrs are escaped above ?>>
<?php else : ?>
<button type="button" class="<?php echo esc_attr( $btn_class ); ?>"<?php echo $extra_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
<?php endif; ?>

	<?php if ( $icon_before ) : ?>
		<?php echo aidriven_get_svg_icon( $icon_before, 'btn-icon btn-icon-before' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG from theme files ?>
	<?php endif; ?>

	<span class="btn-label"><?php echo esc_html( $label ); ?></span>

	<?php if ( $icon_after ) : ?>
		<?php echo aidriven_get_svg_icon( $icon_after, 'btn-icon btn-icon-after' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>

<?php if ( $url ) : ?>
</a>
<?php else : ?>
</button>
<?php endif; ?>
