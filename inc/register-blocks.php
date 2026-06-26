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
	}
);
