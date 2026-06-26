<?php
/**
 * General-purpose helper functions.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Return inline SVG markup for an icon from assets/media/icons/.
 *
 * Reads the SVG file at assets/media/icons/{$icon_name}.svg and returns its
 * raw contents so it can be embedded inline (enabling CSS colour control via
 * currentColor). Returns an empty string when the file does not exist.
 *
 * @param string $icon_name  Filename without extension, e.g. 'arrow-right'.
 * @param string $css_class  Optional CSS classes to add to the wrapping span.
 * @return string SVG markup or empty string.
 */
function aidriven_get_svg_icon( $icon_name, $css_class = '' ) {
	$path = get_template_directory() . '/assets/media/icons/' . sanitize_file_name( $icon_name ) . '.svg';

	if ( ! file_exists( $path ) ) {
		return '';
	}

	ob_start();
	include $path; // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.UsingVariable
	$svg = ob_get_clean();

	if ( empty( $svg ) ) {
		return '';
	}

	if ( $css_class ) {
		return '<span class="' . esc_attr( $css_class ) . '">' . $svg . '</span>';
	}

	return $svg;
}

/**
 * Truncate a string to a maximum character length, appending an ellipsis.
 *
 * Word boundaries are respected — the string is cut at the last complete word
 * that fits within $length characters.
 *
 * @param string $text   The source string.
 * @param int    $length Maximum number of characters (default 160).
 * @param string $more   Suffix appended when the string is truncated (default '…').
 * @return string Truncated string.
 */
function aidriven_truncate_text( $text, $length = 160, $more = '&hellip;' ) {
	$text = wp_strip_all_tags( $text );

	if ( mb_strlen( $text ) <= $length ) {
		return $text;
	}

	$truncated  = mb_substr( $text, 0, $length );
	$last_space = mb_strrpos( $truncated, ' ' );

	if ( false !== $last_space ) {
		$truncated = mb_substr( $truncated, 0, $last_space );
	}

	return rtrim( $truncated ) . $more;
}

/**
 * Return social links from the ACF global options page.
 *
 * Expects a Repeater field named `social_links` on the options page (defined
 * in Step 04) with sub-fields `platform` (text) and `url` (url).
 * Returns an empty array when ACF is unavailable or no links are saved.
 *
 * @return array<int, array{platform: string, url: string, label: string}> Social link rows.
 */
function aidriven_get_social_links() {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	$links = get_field( 'social_links', 'option' );

	return is_array( $links ) ? $links : array();
}
