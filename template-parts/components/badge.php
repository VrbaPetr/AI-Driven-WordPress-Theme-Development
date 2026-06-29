<?php
/**
 * Component: Badge
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type string $label   Badge text. Required.
 *     @type string $variant Colour variant: 'primary' | 'success' | 'warning' | 'error' | 'neutral'. Default 'neutral'.
 *     @type string $url     Optional href — wraps badge in an <a> tag.
 *     @type string $classes Additional CSS classes.
 * }
 */

$label   = $args['label'] ?? '';
$variant = $args['variant'] ?? 'neutral';
$url     = $args['url'] ?? '';
$classes = $args['classes'] ?? '';

if ( empty( $label ) ) {
	return;
}

$allowed_variants = array( 'primary', 'success', 'warning', 'error', 'neutral' );

if ( ! in_array( $variant, $allowed_variants, true ) ) {
	$variant = 'neutral';
}

$badge_class = 'badge badge-' . $variant;
if ( $classes ) {
	$badge_class .= ' ' . $classes;
}
?>

<?php if ( $url ) : ?>
<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $badge_class ); ?>">
	<?php echo esc_html( $label ); ?>
</a>
<?php else : ?>
<span class="<?php echo esc_attr( $badge_class ); ?>">
	<?php echo esc_html( $label ); ?>
</span>
<?php endif; ?>
