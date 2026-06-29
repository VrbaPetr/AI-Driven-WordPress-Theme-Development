<?php
/**
 * Register Custom Post Types.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Register all Custom Post Types.
 */
function ai_driven_register_post_types() {

	// Services.
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'               => __( 'Services', 'ai-driven-boilerplate' ),
				'singular_name'      => __( 'Service', 'ai-driven-boilerplate' ),
				'add_new'            => __( 'Add New', 'ai-driven-boilerplate' ),
				'add_new_item'       => __( 'Add New Service', 'ai-driven-boilerplate' ),
				'edit_item'          => __( 'Edit Service', 'ai-driven-boilerplate' ),
				'new_item'           => __( 'New Service', 'ai-driven-boilerplate' ),
				'view_item'          => __( 'View Service', 'ai-driven-boilerplate' ),
				'search_items'       => __( 'Search Services', 'ai-driven-boilerplate' ),
				'not_found'          => __( 'No services found', 'ai-driven-boilerplate' ),
				'not_found_in_trash' => __( 'No services found in Trash', 'ai-driven-boilerplate' ),
				'menu_name'          => __( 'Services', 'ai-driven-boilerplate' ),
			),
			'public'       => true,
			'has_archive'  => 'services',
			'rewrite'      => array( 'slug' => 'services' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'menu_icon'    => 'dashicons-hammer',
			'show_in_rest' => true,
		)
	);

	// Portfolio / Projects.
	register_post_type(
		'project',
		array(
			'labels'       => array(
				'name'               => __( 'Portfolio', 'ai-driven-boilerplate' ),
				'singular_name'      => __( 'Project', 'ai-driven-boilerplate' ),
				'add_new'            => __( 'Add New', 'ai-driven-boilerplate' ),
				'add_new_item'       => __( 'Add New Project', 'ai-driven-boilerplate' ),
				'edit_item'          => __( 'Edit Project', 'ai-driven-boilerplate' ),
				'new_item'           => __( 'New Project', 'ai-driven-boilerplate' ),
				'view_item'          => __( 'View Project', 'ai-driven-boilerplate' ),
				'search_items'       => __( 'Search Projects', 'ai-driven-boilerplate' ),
				'not_found'          => __( 'No projects found', 'ai-driven-boilerplate' ),
				'not_found_in_trash' => __( 'No projects found in Trash', 'ai-driven-boilerplate' ),
				'menu_name'          => __( 'Portfolio', 'ai-driven-boilerplate' ),
			),
			'public'       => true,
			'has_archive'  => 'portfolio',
			'rewrite'      => array( 'slug' => 'portfolio' ),
			'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'menu_icon'    => 'dashicons-portfolio',
			'show_in_rest' => true,
		)
	);

	// Team Members.
	register_post_type(
		'team-member',
		array(
			'labels'       => array(
				'name'               => __( 'Team Members', 'ai-driven-boilerplate' ),
				'singular_name'      => __( 'Team Member', 'ai-driven-boilerplate' ),
				'add_new'            => __( 'Add New', 'ai-driven-boilerplate' ),
				'add_new_item'       => __( 'Add New Team Member', 'ai-driven-boilerplate' ),
				'edit_item'          => __( 'Edit Team Member', 'ai-driven-boilerplate' ),
				'new_item'           => __( 'New Team Member', 'ai-driven-boilerplate' ),
				'view_item'          => __( 'View Team Member', 'ai-driven-boilerplate' ),
				'search_items'       => __( 'Search Team Members', 'ai-driven-boilerplate' ),
				'not_found'          => __( 'No team members found', 'ai-driven-boilerplate' ),
				'not_found_in_trash' => __( 'No team members found in Trash', 'ai-driven-boilerplate' ),
				'menu_name'          => __( 'Team Members', 'ai-driven-boilerplate' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'team' ),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-groups',
			'show_in_rest' => true,
		)
	);

	// Testimonials.
	register_post_type(
		'testimonial',
		array(
			'labels'       => array(
				'name'               => __( 'Testimonials', 'ai-driven-boilerplate' ),
				'singular_name'      => __( 'Testimonial', 'ai-driven-boilerplate' ),
				'add_new'            => __( 'Add New', 'ai-driven-boilerplate' ),
				'add_new_item'       => __( 'Add New Testimonial', 'ai-driven-boilerplate' ),
				'edit_item'          => __( 'Edit Testimonial', 'ai-driven-boilerplate' ),
				'new_item'           => __( 'New Testimonial', 'ai-driven-boilerplate' ),
				'view_item'          => __( 'View Testimonial', 'ai-driven-boilerplate' ),
				'search_items'       => __( 'Search Testimonials', 'ai-driven-boilerplate' ),
				'not_found'          => __( 'No testimonials found', 'ai-driven-boilerplate' ),
				'not_found_in_trash' => __( 'No testimonials found in Trash', 'ai-driven-boilerplate' ),
				'menu_name'          => __( 'Testimonials', 'ai-driven-boilerplate' ),
			),
			'public'       => true,
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'testimonials' ),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-format-quote',
			'show_in_rest' => true,
		)
	);
}
add_action( 'init', 'ai_driven_register_post_types' );
