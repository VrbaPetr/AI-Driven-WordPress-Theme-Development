<?php
/**
 * General-purpose helper functions.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Resolve the filesystem path to an icon SVG.
 *
 * Accepts both the new 'category/filename' format (e.g. 'ui/circle-check')
 * and legacy bare filenames (e.g. 'circle-check') for backwards compatibility.
 * Returns an empty string when the resolved file does not exist.
 *
 * @param string $value    ACF stored value: 'category/filename' or bare 'filename' (no extension).
 * @param bool   $absolute True returns the absolute filesystem path; false returns the path relative to the theme root.
 * @return string Resolved path or empty string when the file is not found.
 */
function aidriven_get_icon_path( $value, $absolute = true ) {
	if ( empty( $value ) ) {
		return '';
	}

	$rel_path = 'assets/media/icons/' . $value . '.svg';
	$abs_path = get_template_directory() . '/' . $rel_path;

	if ( ! file_exists( $abs_path ) ) {
		return '';
	}

	return $absolute ? $abs_path : $rel_path;
}

/**
 * Return inline SVG markup for an icon from assets/media/icons/.
 *
 * Accepts both the new 'category/filename' format (e.g. 'ui/circle-check')
 * and legacy bare filenames. Reads the SVG file and returns its raw contents
 * so it can be embedded inline (enabling CSS colour control via currentColor).
 * Returns an empty string when the file does not exist.
 *
 * @param string $icon_name  ACF stored value or bare filename without extension.
 * @param string $css_class  Optional CSS classes to add to the wrapping span.
 * @return string SVG markup or empty string.
 */
function aidriven_get_svg_icon( $icon_name, $css_class = '' ) {
	$path = aidriven_get_icon_path( $icon_name );

	if ( empty( $path ) ) {
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
 * Estimate reading time for a post in minutes.
 *
 * Strips all tags from the content and divides the word count by an average
 * reading speed of 200 words per minute, rounding to the nearest whole minute
 * with a floor of 1.
 *
 * @param int $post_id WP post ID.
 * @return int Estimated reading time in minutes.
 */
function aidriven_get_read_time( $post_id ) {
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	return max( 1, (int) round( $word_count / 200 ) );
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
