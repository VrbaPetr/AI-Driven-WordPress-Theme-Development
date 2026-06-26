<?php
/**
 * Register custom taxonomies.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Register all custom taxonomies.
 */
function ai_driven_register_taxonomies() {

	// Service Category.
	register_taxonomy(
		'service-category',
		'service',
		array(
			'labels'       => array(
				'name'              => __( 'Service Categories', 'ai-driven-boilerplate' ),
				'singular_name'     => __( 'Service Category', 'ai-driven-boilerplate' ),
				'search_items'      => __( 'Search Service Categories', 'ai-driven-boilerplate' ),
				'all_items'         => __( 'All Service Categories', 'ai-driven-boilerplate' ),
				'parent_item'       => __( 'Parent Service Category', 'ai-driven-boilerplate' ),
				'parent_item_colon' => __( 'Parent Service Category:', 'ai-driven-boilerplate' ),
				'edit_item'         => __( 'Edit Service Category', 'ai-driven-boilerplate' ),
				'update_item'       => __( 'Update Service Category', 'ai-driven-boilerplate' ),
				'add_new_item'      => __( 'Add New Service Category', 'ai-driven-boilerplate' ),
				'new_item_name'     => __( 'New Service Category Name', 'ai-driven-boilerplate' ),
				'menu_name'         => __( 'Categories', 'ai-driven-boilerplate' ),
			),
			'hierarchical' => true,
			'public'       => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'service-category' ),
		)
	);

	// Portfolio Category.
	register_taxonomy(
		'portfolio-category',
		'project',
		array(
			'labels'       => array(
				'name'              => __( 'Portfolio Categories', 'ai-driven-boilerplate' ),
				'singular_name'     => __( 'Portfolio Category', 'ai-driven-boilerplate' ),
				'search_items'      => __( 'Search Portfolio Categories', 'ai-driven-boilerplate' ),
				'all_items'         => __( 'All Portfolio Categories', 'ai-driven-boilerplate' ),
				'parent_item'       => __( 'Parent Portfolio Category', 'ai-driven-boilerplate' ),
				'parent_item_colon' => __( 'Parent Portfolio Category:', 'ai-driven-boilerplate' ),
				'edit_item'         => __( 'Edit Portfolio Category', 'ai-driven-boilerplate' ),
				'update_item'       => __( 'Update Portfolio Category', 'ai-driven-boilerplate' ),
				'add_new_item'      => __( 'Add New Portfolio Category', 'ai-driven-boilerplate' ),
				'new_item_name'     => __( 'New Portfolio Category Name', 'ai-driven-boilerplate' ),
				'menu_name'         => __( 'Categories', 'ai-driven-boilerplate' ),
			),
			'hierarchical' => true,
			'public'       => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'portfolio-category' ),
		)
	);
}
add_action( 'init', 'ai_driven_register_taxonomies' );
