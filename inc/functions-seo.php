<?php
/**
 * SEO output functions: Open Graph, Twitter Card, structured data, and canonical URL.
 *
 * All functions are hooked to wp_head at priority 1 and silently skip output
 * when Yoast SEO (WPSEO_VERSION) or RankMath (RANK_MATH_VERSION) is active.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Collect SEO-relevant metadata for the current page.
 *
 * Called by aidriven_og_meta() and aidriven_twitter_meta() to avoid repeated
 * ACF and attachment lookups across two hooks on the same request.
 *
 * @return array SEO data with keys: title, description, url, og_type, image_url, image_width, image_height, site_name.
 */
function aidriven_seo_get_page_data() {
	$company_name = get_field( 'company_name', 'option' );
	$site_name    = $company_name ? $company_name : get_bloginfo( 'name' );

	// Title.
	if ( is_front_page() ) {
		$title = get_bloginfo( 'name' );
	} elseif ( is_singular() ) {
		$title = get_the_title( get_queried_object_id() );
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$title = sprintf( __( 'Search results for: %s', 'ai-driven-boilerplate' ), get_search_query() );
	} elseif ( is_404() ) {
		$title = __( 'Page Not Found', 'ai-driven-boilerplate' );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
	} else {
		$title = get_bloginfo( 'name' );
	}

	// Description.
	if ( is_singular() ) {
		$description = get_the_excerpt( get_queried_object_id() );
	} else {
		$tagline     = get_field( 'tagline', 'option' );
		$description = $tagline ? $tagline : get_bloginfo( 'description' );
	}
	$description = wp_strip_all_tags( $description );

	// Canonical URL.
	if ( is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_singular() ) {
		$url = (string) get_permalink( get_queried_object_id() );
	} else {
		$url = get_pagenum_link();
	}

	// OG type.
	$og_type = is_singular( 'post' ) ? 'article' : 'website';

	// Image: featured image with logo fallback.
	$image_url    = '';
	$image_width  = 0;
	$image_height = 0;

	if ( is_singular() ) {
		$thumbnail_id = get_post_thumbnail_id( get_queried_object_id() );
		if ( $thumbnail_id ) {
			$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
			if ( is_array( $image_src ) ) {
				$image_url    = $image_src[0];
				$image_width  = (int) $image_src[1];
				$image_height = (int) $image_src[2];
			}
		}
	}

	if ( ! $image_url ) {
		$logo = get_field( 'logo_dark', 'option' );
		if ( is_array( $logo ) && ! empty( $logo['url'] ) ) {
			$image_url    = $logo['url'];
			$image_width  = isset( $logo['width'] ) ? (int) $logo['width'] : 0;
			$image_height = isset( $logo['height'] ) ? (int) $logo['height'] : 0;
		}
	}

	return array(
		'title'        => $title,
		'description'  => $description,
		'url'          => $url,
		'og_type'      => $og_type,
		'image_url'    => $image_url,
		'image_width'  => $image_width,
		'image_height' => $image_height,
		'site_name'    => $site_name,
	);
}

/**
 * Output Open Graph meta tags in <head>.
 *
 * Covers og:type, og:title, og:description, og:url, og:site_name, og:image,
 * og:image:width, and og:image:height.
 *
 * @return void
 */
function aidriven_og_meta() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	$data = aidriven_seo_get_page_data();
	?>
	<meta property="og:type" content="<?php echo esc_attr( $data['og_type'] ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $data['title'] ); ?>">
	<meta property="og:url" content="<?php echo esc_url( $data['url'] ); ?>">
	<meta property="og:site_name" content="<?php echo esc_attr( $data['site_name'] ); ?>">
	<?php if ( $data['description'] ) : ?>
	<meta property="og:description" content="<?php echo esc_attr( $data['description'] ); ?>">
	<?php endif; ?>
	<?php if ( $data['image_url'] ) : ?>
	<meta property="og:image" content="<?php echo esc_url( $data['image_url'] ); ?>">
		<?php if ( $data['image_width'] && $data['image_height'] ) : ?>
	<meta property="og:image:width" content="<?php echo esc_attr( (string) $data['image_width'] ); ?>">
	<meta property="og:image:height" content="<?php echo esc_attr( (string) $data['image_height'] ); ?>">
	<?php endif; ?>
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'aidriven_og_meta', 1 );

/**
 * Output Twitter Card meta tags in <head>.
 *
 * Uses summary_large_image when a featured image is available; summary otherwise.
 *
 * @return void
 */
function aidriven_twitter_meta() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	$data      = aidriven_seo_get_page_data();
	$card_type = $data['image_url'] ? 'summary_large_image' : 'summary';
	?>
	<meta name="twitter:card" content="<?php echo esc_attr( $card_type ); ?>">
	<meta name="twitter:title" content="<?php echo esc_attr( $data['title'] ); ?>">
	<?php if ( $data['description'] ) : ?>
	<meta name="twitter:description" content="<?php echo esc_attr( $data['description'] ); ?>">
	<?php endif; ?>
	<?php if ( $data['image_url'] ) : ?>
	<meta name="twitter:image" content="<?php echo esc_url( $data['image_url'] ); ?>">
	<?php endif; ?>
	<?php
}
add_action( 'wp_head', 'aidriven_twitter_meta', 1 );

/**
 * Output a canonical <link> tag in <head>.
 *
 * Skips on 404 pages. Uses wp_get_canonical_url() for singular content and
 * get_pagenum_link() for paginated archives.
 *
 * @return void
 */
function aidriven_canonical_url() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	if ( is_404() ) {
		return;
	}

	if ( is_singular() ) {
		$canonical = wp_get_canonical_url( get_queried_object_id() );
	} elseif ( is_front_page() ) {
		$canonical = home_url( '/' );
	} elseif ( is_home() ) {
		$blog_page_id = (int) get_option( 'page_for_posts' );
		$canonical    = $blog_page_id ? get_permalink( $blog_page_id ) : home_url( '/' );
	} else {
		$canonical = get_pagenum_link();
	}

	if ( ! $canonical ) {
		return;
	}
	?>
	<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>">
	<?php
}
add_action( 'wp_head', 'aidriven_canonical_url', 1 );

/**
 * Output Organisation JSON-LD structured data in <head>.
 *
 * Only rendered on the front page. Includes name, URL, logo, contact point,
 * and sameAs links from the ACF social links options.
 *
 * @return void
 */
function aidriven_organisation_schema() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	if ( ! is_front_page() ) {
		return;
	}

	$company_name = get_field( 'company_name', 'option' );
	$name         = $company_name ? $company_name : get_bloginfo( 'name' );

	$logo     = get_field( 'logo_dark', 'option' );
	$logo_url = ( is_array( $logo ) && ! empty( $logo['url'] ) ) ? $logo['url'] : '';

	$phone = get_field( 'phone_number', 'option' );

	$social_links = aidriven_get_social_links();
	$same_as      = array();
	foreach ( $social_links as $link ) {
		if ( ! empty( $link['url'] ) ) {
			$same_as[] = $link['url'];
		}
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => $name,
		'url'      => home_url( '/' ),
	);

	if ( $logo_url ) {
		$schema['logo'] = $logo_url;
	}

	if ( $phone ) {
		$schema['contactPoint'] = array(
			'@type'       => 'ContactPoint',
			'telephone'   => $phone,
			'contactType' => 'Customer Service',
		);
	}

	if ( ! empty( $same_as ) ) {
		$schema['sameAs'] = $same_as;
	}
	?>
	<script type="application/ld+json">
	<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode handles escaping ?>

	</script>
	<?php
}
add_action( 'wp_head', 'aidriven_organisation_schema', 1 );

/**
 * Output Article JSON-LD structured data in <head>.
 *
 * Only rendered on single blog posts. Includes headline, author, dates,
 * featured image, and publisher with logo.
 *
 * @return void
 */
function aidriven_article_schema() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	if ( ! is_singular( 'post' ) ) {
		return;
	}

	$post_id      = get_queried_object_id();
	$company_name = get_field( 'company_name', 'option' );
	$publisher    = $company_name ? $company_name : get_bloginfo( 'name' );

	$logo     = get_field( 'logo_dark', 'option' );
	$logo_url = ( is_array( $logo ) && ! empty( $logo['url'] ) ) ? $logo['url'] : '';

	$thumbnail_id = get_post_thumbnail_id( $post_id );
	$image_url    = '';
	if ( $thumbnail_id ) {
		$image_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		$image_url = is_array( $image_src ) ? $image_src[0] : '';
	}

	$author_id = (int) get_post_field( 'post_author', $post_id );

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => get_the_title( $post_id ),
		'author'        => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $author_id ),
		),
		'datePublished' => get_the_date( 'c', $post_id ),
		'dateModified'  => get_the_modified_date( 'c', $post_id ),
		'publisher'     => array(
			'@type' => 'Organization',
			'name'  => $publisher,
		),
	);

	if ( $image_url ) {
		$schema['image'] = $image_url;
	}

	if ( $logo_url ) {
		$schema['publisher']['logo'] = array(
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		);
	}
	?>
	<script type="application/ld+json">
	<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode handles escaping ?>

	</script>
	<?php
}
add_action( 'wp_head', 'aidriven_article_schema', 1 );
