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
	}
);
