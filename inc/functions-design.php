<?php
/**
 * Design-related functions: theme supports, menu locations, and image sizes.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Register theme supports, navigation menus, and custom image sizes.
 *
 * Hooked to after_setup_theme with priority 10.
 *
 * Image sizes registered:
 *   card-thumbnail  — 800 × 533 px  (3:2, cropped) — blog/portfolio/services cards
 *   hero-full       — 1920 × 900 px (cropped)       — full-width hero background
 *   hero-split      — 960 × 900 px  (cropped)       — split hero, image side
 *   team-portrait   — 600 × 750 px  (4:5, cropped)  — team member photos
 *   portfolio-thumb — 800 × 600 px  (4:3, cropped)  — portfolio grid tiles
 *
 * @return void
 */
function aidriven_setup_theme() {
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'disable-custom-colors' );
	add_theme_support( 'editor-gradient-presets', array() );
	add_theme_support(
		'editor-color-palette',
		array(
			// Primary — Mountain Blue.
			array(
				'name'  => __( 'Primary 50', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-50',
				'color' => 'oklch(97% 0.01 237)',
			),
			array(
				'name'  => __( 'Primary 100', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-100',
				'color' => 'oklch(93% 0.03 237)',
			),
			array(
				'name'  => __( 'Primary 200', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-200',
				'color' => 'oklch(89% 0.06 237)',
			),
			array(
				'name'  => __( 'Primary 300', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-300',
				'color' => 'oklch(84% 0.09 237)',
			),
			array(
				'name'  => __( 'Primary 400', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-400',
				'color' => 'oklch(80% 0.11 237)',
			),
			array(
				'name'  => __( 'Primary 500', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-500',
				'color' => 'oklch(76% 0.14 237)',
			),
			array(
				'name'  => __( 'Primary 600', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-600',
				'color' => 'oklch(63% 0.14 237)',
			),
			array(
				'name'  => __( 'Primary 700', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-700',
				'color' => 'oklch(50% 0.12 237)',
			),
			array(
				'name'  => __( 'Primary 800', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-800',
				'color' => 'oklch(38% 0.09 237)',
			),
			array(
				'name'  => __( 'Primary 900', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-900',
				'color' => 'oklch(27% 0.07 237)',
			),
			array(
				'name'  => __( 'Primary 950', 'ai-driven-boilerplate' ),
				'slug'  => 'primary-950',
				'color' => 'oklch(17% 0.05 237)',
			),
			// Secondary — Rock Black.
			array(
				'name'  => __( 'Secondary 50', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-50',
				'color' => 'oklch(96% 0 0)',
			),
			array(
				'name'  => __( 'Secondary 100', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-100',
				'color' => 'oklch(90% 0 0)',
			),
			array(
				'name'  => __( 'Secondary 200', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-200',
				'color' => 'oklch(80% 0 0)',
			),
			array(
				'name'  => __( 'Secondary 300', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-300',
				'color' => 'oklch(65% 0 0)',
			),
			array(
				'name'  => __( 'Secondary 400', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-400',
				'color' => 'oklch(45% 0 0)',
			),
			array(
				'name'  => __( 'Secondary 500', 'ai-driven-boilerplate' ),
				'slug'  => 'secondary-500',
				'color' => 'oklch(4% 0 0)',
			),
			// Neutral.
			array(
				'name'  => __( 'Neutral 50', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-50',
				'color' => 'oklch(100% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 100', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-100',
				'color' => 'oklch(96% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 200', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-200',
				'color' => 'oklch(91% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 300', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-300',
				'color' => 'oklch(84% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 400', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-400',
				'color' => 'oklch(73% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 500', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-500',
				'color' => 'oklch(62% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 600', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-600',
				'color' => 'oklch(51% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 700', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-700',
				'color' => 'oklch(40% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 800', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-800',
				'color' => 'oklch(30% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 900', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-900',
				'color' => 'oklch(20% 0 0)',
			),
			array(
				'name'  => __( 'Neutral 950', 'ai-driven-boilerplate' ),
				'slug'  => 'neutral-950',
				'color' => 'oklch(10% 0 0)',
			),
			// Semantic.
			array(
				'name'  => __( 'Success', 'ai-driven-boilerplate' ),
				'slug'  => 'success',
				'color' => 'oklch(60% 0.15 145)',
			),
			array(
				'name'  => __( 'Warning', 'ai-driven-boilerplate' ),
				'slug'  => 'warning',
				'color' => 'oklch(75% 0.17 70)',
			),
			array(
				'name'  => __( 'Error', 'ai-driven-boilerplate' ),
				'slug'  => 'error',
				'color' => 'oklch(55% 0.20 25)',
			),
			array(
				'name'  => __( 'Info', 'ai-driven-boilerplate' ),
				'slug'  => 'info',
				'color' => 'oklch(60% 0.13 240)',
			),
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'ai-driven-boilerplate' ),
			'footer'  => __( 'Footer Navigation', 'ai-driven-boilerplate' ),
		)
	);

	add_image_size( 'card-thumbnail', 800, 533, true );
	add_image_size( 'hero-full', 1920, 900, true );
	add_image_size( 'hero-split', 960, 900, true );
	add_image_size( 'team-portrait', 600, 750, true );
	add_image_size( 'portfolio-thumb', 800, 600, true );
}
add_action( 'after_setup_theme', 'aidriven_setup_theme' );

/**
 * Register the ACF global options page.
 *
 * @return void
 */
function aidriven_register_options_pages() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Theme Settings', 'ai-driven-boilerplate' ),
			'menu_title' => __( 'Theme Settings', 'ai-driven-boilerplate' ),
			'menu_slug'  => 'theme-settings',
			'capability' => 'manage_options',
			'icon_url'   => 'dashicons-admin-appearance',
			'position'   => 25,
			'autoload'   => true,
		)
	);
}
add_action( 'acf/init', 'aidriven_register_options_pages' );

/**
 * Output the GTM <script> snippet inside <head>.
 *
 * Only fires when enable_gtm is true and gtm_container_id is non-empty.
 *
 * @return void
 */
function aidriven_output_gtm_head() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	$enabled      = get_field( 'enable_gtm', 'option' );
	$container_id = get_field( 'gtm_container_id', 'option' );
	if ( ! $enabled || empty( $container_id ) ) {
		return;
	}
	?>
	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','<?php echo esc_attr( $container_id ); ?>');</script>
	<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'aidriven_output_gtm_head', 1 );

/**
 * Output the GTM <noscript> iframe immediately after <body> opens.
 *
 * Only fires when enable_gtm is true and gtm_container_id is non-empty.
 *
 * @return void
 */
function aidriven_output_gtm_body() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}
	$enabled      = get_field( 'enable_gtm', 'option' );
	$container_id = get_field( 'gtm_container_id', 'option' );
	if ( ! $enabled || empty( $container_id ) ) {
		return;
	}
	$ns_src = esc_url( 'https://www.googletagmanager.com/ns.html?id=' . $container_id );
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="<?php echo $ns_src; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'aidriven_output_gtm_body' );

/**
 * Return ACF select choices for icon fields.
 *
 * Scans the assets/media/icons/ subdirectories and builds an array of choices.
 * When $category is null all three subdirectories are merged and keys use the
 * 'category/filename' format ('ui/circle-check'). When a valid category slug
 * is given only that subdirectory is scanned and keys are bare filenames.
 *
 * @param string|null $category Subdirectory name: 'social', 'tech', or 'ui'. Null returns all.
 * @return array<string, string> Choices array suitable for an ACF select field.
 */
function aidriven_get_icon_choices( $category = null ) {
	$base_dir   = get_template_directory() . '/assets/media/icons/';
	$categories = array( 'social', 'tech', 'ui' );
	$choices    = array();

	if ( null !== $category ) {
		if ( ! in_array( $category, $categories, true ) ) {
			return array();
		}
		$dir   = $base_dir . $category . '/';
		$files = is_dir( $dir ) ? glob( $dir . '*.svg' ) : false;
		if ( ! $files ) {
			return array();
		}
		foreach ( $files as $file ) {
			$filename             = pathinfo( $file, PATHINFO_FILENAME );
			$choices[ $filename ] = ucwords( str_replace( '-', ' ', $filename ) );
		}
		asort( $choices );
		return $choices;
	}

	foreach ( $categories as $cat ) {
		$dir   = $base_dir . $cat . '/';
		$files = is_dir( $dir ) ? glob( $dir . '*.svg' ) : false;
		if ( ! $files ) {
			continue;
		}
		$cat_label = ucfirst( $cat );
		foreach ( $files as $file ) {
			$filename                          = pathinfo( $file, PATHINFO_FILENAME );
			$choices[ $cat . '/' . $filename ] = $cat_label . ': ' . ucwords( str_replace( '-', ' ', $filename ) );
		}
	}

	asort( $choices );
	return $choices;
}

/**
 * Populate choices for any ACF select field named 'icon' or ending in '_icon'.
 *
 * Fields named exactly 'icon' receive choices from all categories. Fields whose
 * name ends in '_icon' (e.g. 'ui_icon') have the suffix stripped to derive a
 * category slug; unknown slugs fall back to the full merged list.
 *
 * @param array $field ACF field definition array.
 * @return array Modified field definition.
 */
function aidriven_load_icon_field_choices( $field ) {
	if ( 'select' !== $field['type'] ) {
		return $field;
	}

	if ( 'icon' === $field['name'] ) {
		$field['choices'] = aidriven_get_icon_choices();
		return $field;
	}

	if ( str_ends_with( $field['name'], '_icon' ) ) {
		$category = substr( $field['name'], 0, -5 );
		$known    = array( 'social', 'tech', 'ui' );
		if ( in_array( $category, $known, true ) ) {
			$field['choices'] = aidriven_get_icon_choices( $category );
		} else {
			$field['choices'] = aidriven_get_icon_choices();
		}
	}

	return $field;
}
add_filter( 'acf/load_field', 'aidriven_load_icon_field_choices' );
