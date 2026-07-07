<?php
/**
 * Register ACF Gutenberg blocks and block-related hooks.
 *
 * Block registrations are added per block step (Steps 11–22). Each block is
 * registered via acf_register_block_type() inside an acf/init action.
 *
 * @package ai-driven-boilerplate
 */

add_filter(
	'block_categories_all',
	function ( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'ai-driven-boilerplate-blocks',
					'title' => __( 'AI-Driven Boilerplate', 'ai-driven-boilerplate' ),
					'icon'  => null,
				),
			),
			$categories
		);
	}
);

add_action(
	'acf/init',
	function () {
		if ( ! function_exists( 'acf_register_block_type' ) ) {
			return;
		}

		// Hero Block.
		acf_register_block_type(
			array(
				'name'            => 'hero',
				'title'           => __( 'Hero', 'ai-driven-boilerplate' ),
				'description'     => __( 'Full-width image-background or split text/image hero section with up to two CTA buttons.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/hero.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'cover-image',
				'keywords'        => array( __( 'hero', 'ai-driven-boilerplate' ), __( 'banner', 'ai-driven-boilerplate' ), __( 'header', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/hero.jpg',
						),
					),
				),
			)
		);

		// Stats Block.
		acf_register_block_type(
			array(
				'name'            => 'stats',
				'title'           => __( 'Stats', 'ai-driven-boilerplate' ),
				'description'     => __( 'Display up to four key metrics with an animated count-up effect on scroll.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/stats.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'chart-bar',
				'keywords'        => array( __( 'stats', 'ai-driven-boilerplate' ), __( 'counters', 'ai-driven-boilerplate' ), __( 'numbers', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/stats.jpg',
						),
					),
				),
			)
		);

		// CTA Block.
		acf_register_block_type(
			array(
				'name'            => 'cta',
				'title'           => __( 'CTA', 'ai-driven-boilerplate' ),
				'description'     => __( 'Full-width call-to-action banner with headline, subtext, up to two buttons, and a configurable solid or gradient background.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/cta.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'megaphone',
				'keywords'        => array( __( 'cta', 'ai-driven-boilerplate' ), __( 'call to action', 'ai-driven-boilerplate' ), __( 'banner', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/cta.jpg',
						),
					),
				),
			)
		);

		// Process Block.
		acf_register_block_type(
			array(
				'name'            => 'process',
				'title'           => __( 'Process', 'ai-driven-boilerplate' ),
				'description'     => __( 'Numbered step sequence showing how a project or service is delivered. Supports horizontal and vertical layouts.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/process.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'list-view',
				'keywords'        => array( __( 'process', 'ai-driven-boilerplate' ), __( 'steps', 'ai-driven-boilerplate' ), __( 'workflow', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/process.jpg',
						),
					),
				),
			)
		);

		// FAQ Block.
		acf_register_block_type(
			array(
				'name'            => 'faq',
				'title'           => __( 'FAQ', 'ai-driven-boilerplate' ),
				'description'     => __( 'Accordion-style FAQ section with questions and answers. Outputs JSON-LD FAQ structured data for Google rich snippets.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/faq.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'editor-help',
				'keywords'        => array( __( 'faq', 'ai-driven-boilerplate' ), __( 'accordion', 'ai-driven-boilerplate' ), __( 'questions', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/faq.jpg',
						),
					),
				),
			)
		);

		// Text & Image Block.
		acf_register_block_type(
			array(
				'name'            => 'text-image',
				'title'           => __( 'Text & Image', 'ai-driven-boilerplate' ),
				'description'     => __( 'Two-column section pairing rich text with an image. Toggle image position left or right.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/text-image.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'align-pull-right',
				'keywords'        => array( __( 'text', 'ai-driven-boilerplate' ), __( 'image', 'ai-driven-boilerplate' ), __( 'split', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/text-image.jpg',
						),
					),
				),
			)
		);

		// Testimonials Block.
		acf_register_block_type(
			array(
				'name'            => 'testimonials',
				'title'           => __( 'Testimonials', 'ai-driven-boilerplate' ),
				'description'     => __( 'Auto-advancing slider pulling quotes from the Testimonials CPT with prev/next navigation and dot indicators.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/testimonials.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'format-quote',
				'keywords'        => array( __( 'testimonials', 'ai-driven-boilerplate' ), __( 'reviews', 'ai-driven-boilerplate' ), __( 'slider', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/testimonials.jpg',
						),
					),
				),
			)
		);

		// Portfolio Grid Block.
		acf_register_block_type(
			array(
				'name'            => 'portfolio-grid',
				'title'           => __( 'Portfolio Grid', 'ai-driven-boilerplate' ),
				'description'     => __( 'Filterable project grid pulled from the Portfolio CPT with Alpine.js category filters and AJAX load-more.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/portfolio-grid.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'grid-view',
				'keywords'        => array( __( 'portfolio', 'ai-driven-boilerplate' ), __( 'projects', 'ai-driven-boilerplate' ), __( 'grid', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/portfolio-grid.jpg',
						),
					),
				),
			)
		);

		// Clients Block.
		acf_register_block_type(
			array(
				'name'            => 'clients',
				'title'           => __( 'Clients', 'ai-driven-boilerplate' ),
				'description'     => __( 'Display client or partner logos in a static grid or a continuous scrolling marquee. Supports greyscale/colour toggle.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/clients.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'awards',
				'keywords'        => array( __( 'clients', 'ai-driven-boilerplate' ), __( 'logos', 'ai-driven-boilerplate' ), __( 'partners', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/clients.jpg',
						),
					),
				),
			)
		);

		// Pricing Block.
		acf_register_block_type(
			array(
				'name'            => 'pricing',
				'title'           => __( 'Pricing', 'ai-driven-boilerplate' ),
				'description'     => __( 'Side-by-side pricing plan cards with feature lists, CTA buttons, and an optional featured plan highlight.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/pricing.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'tag',
				'keywords'        => array( __( 'pricing', 'ai-driven-boilerplate' ), __( 'plans', 'ai-driven-boilerplate' ), __( 'packages', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/pricing.jpg',
						),
					),
				),
			)
		);

		// Team Block.
		acf_register_block_type(
			array(
				'name'            => 'team',
				'title'           => __( 'Team', 'ai-driven-boilerplate' ),
				'description'     => __( 'Responsive card grid displaying team members pulled from the Team Members CPT with photos, job titles, bios, and social links.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/team.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'groups',
				'keywords'        => array( __( 'team', 'ai-driven-boilerplate' ), __( 'people', 'ai-driven-boilerplate' ), __( 'staff', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/team.jpg',
						),
					),
				),
			)
		);

		// Services Block.
		acf_register_block_type(
			array(
				'name'            => 'services',
				'title'           => __( 'Services', 'ai-driven-boilerplate' ),
				'description'     => __( 'Responsive card grid displaying services pulled from the Services CPT or entered manually.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/services.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'hammer',
				'keywords'        => array( __( 'services', 'ai-driven-boilerplate' ), __( 'grid', 'ai-driven-boilerplate' ), __( 'cards', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/services.jpg',
						),
					),
				),
			)
		);

		// Welcome Slider Block.
		acf_register_block_type(
			array(
				'name'            => 'welcome-slider',
				'title'           => __( 'Welcome Slider', 'ai-driven-boilerplate' ),
				'description'     => __( 'Full-viewport homepage slider with background images, overlays, headings, and up to two CTAs per slide.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/welcome-slider.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'images-alt2',
				'keywords'        => array( __( 'slider', 'ai-driven-boilerplate' ), __( 'carousel', 'ai-driven-boilerplate' ), __( 'welcome', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/welcome-slider.jpg',
						),
					),
				),
			)
		);

		// Text Content Block.
		acf_register_block_type(
			array(
				'name'            => 'text-content',
				'title'           => __( 'Text Content', 'ai-driven-boilerplate' ),
				'description'     => __( 'WYSIWYG rich text block with consistent typography, configurable reading width and text alignment.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/text-content.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'editor-alignleft',
				'keywords'        => array( __( 'text', 'ai-driven-boilerplate' ), __( 'content', 'ai-driven-boilerplate' ), __( 'wysiwyg', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/text-content.jpg',
						),
					),
				),
			)
		);

		// Recent Blog Posts Block.
		acf_register_block_type(
			array(
				'name'            => 'recent-blog-posts',
				'title'           => __( 'Recent Blog Posts', 'ai-driven-boilerplate' ),
				'description'     => __( 'Displays a grid of recent blog posts, optionally filtered by category, with an optional view-all link.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/recent-blog-posts.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'admin-post',
				'keywords'        => array( __( 'blog', 'ai-driven-boilerplate' ), __( 'posts', 'ai-driven-boilerplate' ), __( 'articles', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/recent-blog-posts.jpg',
						),
					),
				),
			)
		);

		// Icon Grid Block.
		acf_register_block_type(
			array(
				'name'            => 'icon-grid',
				'title'           => __( 'Icon Grid', 'ai-driven-boilerplate' ),
				'description'     => __( 'Manually authored grid of icon, title, and description items for communicating key benefits or differentiators.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/icon-grid.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'screenoptions',
				'keywords'        => array( __( 'icon', 'ai-driven-boilerplate' ), __( 'grid', 'ai-driven-boilerplate' ), __( 'benefits', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/icon-grid.jpg',
						),
					),
				),
			)
		);

		// Video Block.
		acf_register_block_type(
			array(
				'name'            => 'video',
				'title'           => __( 'Video', 'ai-driven-boilerplate' ),
				'description'     => __( 'Lightweight YouTube or Vimeo facade embed — loads a thumbnail and play button, deferring the iframe until clicked.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/video.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'video-alt3',
				'keywords'        => array( __( 'video', 'ai-driven-boilerplate' ), __( 'youtube', 'ai-driven-boilerplate' ), __( 'vimeo', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/video.jpg',
						),
					),
				),
			)
		);

		// Timeline Block.
		acf_register_block_type(
			array(
				'name'            => 'timeline',
				'title'           => __( 'Timeline', 'ai-driven-boilerplate' ),
				'description'     => __( 'Vertical chronological timeline of dated milestones with alternating desktop layout.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/timeline.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'clock',
				'keywords'        => array( __( 'timeline', 'ai-driven-boilerplate' ), __( 'history', 'ai-driven-boilerplate' ), __( 'milestones', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/timeline.jpg',
						),
					),
				),
			)
		);

		// Gallery / Image Grid Block.
		acf_register_block_type(
			array(
				'name'            => 'gallery-image-grid',
				'title'           => __( 'Gallery / Image Grid', 'ai-driven-boilerplate' ),
				'description'     => __( 'Uniform image grid with 2, 3, or 4 columns and an optional LiteLight lightbox.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/gallery-image-grid.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'format-gallery',
				'keywords'        => array( __( 'gallery', 'ai-driven-boilerplate' ), __( 'images', 'ai-driven-boilerplate' ), __( 'grid', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/gallery-image-grid.jpg',
						),
					),
				),
			)
		);

		// Awards & Certifications Block.
		acf_register_block_type(
			array(
				'name'            => 'awards-certifications',
				'title'           => __( 'Awards & Certifications', 'ai-driven-boilerplate' ),
				'description'     => __( 'Centred row of award, certification, and partner badges with name, issuer, and year.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/awards-certifications.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'star-filled',
				'keywords'        => array( __( 'awards', 'ai-driven-boilerplate' ), __( 'certifications', 'ai-driven-boilerplate' ), __( 'badges', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/awards-certifications.jpg',
						),
					),
				),
			)
		);

		// Newsletter Block.
		acf_register_block_type(
			array(
				'name'            => 'newsletter',
				'title'           => __( 'Newsletter / Email Capture', 'ai-driven-boilerplate' ),
				'description'     => __( 'Inline email opt-in strip with honeypot + nonce security and an optional provider webhook.', 'ai-driven-boilerplate' ),
				'render_template' => 'template-parts/blocks/newsletter.php',
				'category'        => 'ai-driven-boilerplate-blocks',
				'icon'            => 'email-alt',
				'keywords'        => array( __( 'newsletter', 'ai-driven-boilerplate' ), __( 'email', 'ai-driven-boilerplate' ), __( 'subscribe', 'ai-driven-boilerplate' ) ),
				'mode'            => 'preview',
				'supports'        => array(
					'mode'  => true,
					'align' => false,
					'jsx'   => true,
				),
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview',
						'data' => array(
							'preview_screenshot' => get_template_directory_uri() . '/assets/media/block-preview/newsletter.jpg',
						),
					),
				),
			)
		);
	}
);

/**
 * Enqueue the LiteLight lightbox assets, but only on pages that actually
 * contain the Gallery / Image Grid block.
 *
 * The Gallery / Image Grid block previously loaded LiteLight via ACF's
 * per-block 'enqueue_assets' callback, which is invoked as a side effect of
 * WordPress rendering that block's render_callback. Because get_the_excerpt()
 * runs the full 'the_content' filter pipeline (including block rendering)
 * whenever a post has no manual excerpt, that callback could fire during
 * wp_head (via the theme's meta description output) before the page's real
 * content is rendered — causing LiteLight's CSS/JS to print in <head> and
 * block first paint even though the gallery is typically below the fold.
 * Hooking a plain has_block() check to wp_enqueue_scripts keeps the assets
 * off every page that doesn't use the block and enqueues them at the normal,
 * predictable point in the page lifecycle.
 *
 * @return void
 */
function aidriven_enqueue_gallery_lightbox_assets() {
	if ( ! has_block( 'acf/gallery-image-grid' ) ) {
		return;
	}

	wp_enqueue_script(
		'lite-light',
		get_template_directory_uri() . '/assets/js/lite-light.min.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
	wp_add_inline_script( 'lite-light', 'document.addEventListener( "DOMContentLoaded", function () { LiteLight.init(); } );' );

	wp_enqueue_style(
		'lite-light',
		get_template_directory_uri() . '/assets/css/lite-light.min.css',
		array(),
		wp_get_theme()->get( 'Version' )
	);
	wp_add_inline_style(
		'lite-light',
		'.lite-light{--ll-overlay:var(--color-secondary-950);--ll-radius:var(--radius-md);--ll-image-bg:var(--color-neutral-50);--ll-control:var(--color-neutral-50);z-index:var(--z-modal);}'
	);
}
add_action( 'wp_enqueue_scripts', 'aidriven_enqueue_gallery_lightbox_assets' );
