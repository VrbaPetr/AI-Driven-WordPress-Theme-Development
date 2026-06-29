<?php
/**
 * Layout: Page Header
 *
 * Reusable page header section rendered above block content on all inner pages.
 * Used across page templates in Steps 24–29.
 *
 * @package ai-driven-boilerplate
 *
 * @param array $args {
 *     @type string $title            Page title. Defaults to get_the_title().
 *     @type string $subtitle         Optional subtitle text (excerpt or ACF field).
 *     @type bool   $show_breadcrumbs Whether to render breadcrumbs. Default true.
 *     @type string $classes          Extra CSS classes on the wrapper element.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_title       = isset( $args['title'] ) ? $args['title'] : get_the_title();
$subtitle         = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$show_breadcrumbs = isset( $args['show_breadcrumbs'] ) ? (bool) $args['show_breadcrumbs'] : true;
$extra_classes    = isset( $args['classes'] ) ? $args['classes'] : '';

$wrapper_class = 'page-header';
if ( $extra_classes ) {
	$wrapper_class .= ' ' . $extra_classes;
}
?>
<div class="<?php echo esc_attr( $wrapper_class ); ?>">
	<div class="page-header-inner">

		<?php if ( $show_breadcrumbs ) : ?>
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
		<?php endif; ?>

		<?php if ( $page_title ) : ?>
			<h1 class="page-header-title"><?php echo esc_html( $page_title ); ?></h1>
		<?php endif; ?>

		<?php if ( $subtitle ) : ?>
			<p class="page-header-subtitle"><?php echo esc_html( $subtitle ); ?></p>
		<?php endif; ?>

	</div><!-- .page-header-inner -->
</div><!-- .page-header -->
