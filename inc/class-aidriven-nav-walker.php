<?php
/**
 * Navigation walker class for the primary menu.
 *
 * @package ai-driven-boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom nav walker — injects Alpine.js attributes for two-level dropdown menus.
 *
 * Depth-0 items with children receive x-data / @mouseenter / @mouseleave on the <li>
 * and aria-haspopup / :aria-expanded / keyboard handlers on the <a>. The child <ul>
 * receives id and x-show so Alpine can show/hide it.
 */
class Aidriven_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * Stores the current parent item ID so start_lvl() can build the sub-menu id attribute.
	 *
	 * @var int
	 */
	private int $current_parent_id = 0;

	/**
	 * Start the sub-menu list element.
	 *
	 * @param string        $output Used to append additional content (passed by reference).
	 * @param int           $depth  Depth of menu item.
	 * @param stdClass|null $args   An object of wp_nav_menu() arguments.
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent     = str_repeat( "\t", $depth );
		$submenu_id = 'submenu-' . $this->current_parent_id;
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Alpine directive; submenu_id is cast to int in start_el.
		$output .= "\n" . $indent . '<ul id="' . esc_attr( $submenu_id ) . '" class="nav-dropdown" x-show="open" x-cloak>' . "\n";
	}

	/**
	 * Start the menu item element.
	 *
	 * @param string        $output      Used to append additional content (passed by reference).
	 * @param WP_Post       $data_object Menu item data object.
	 * @param int           $depth       Depth of menu item; 0 for top-level items.
	 * @param stdClass|null $args        An object of wp_nav_menu() arguments.
	 * @param int           $id          Current item ID.
	 * @return void
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $id = 0 ) {
		$item   = $data_object;
		$indent = $depth ? str_repeat( "\t", $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$has_children = ! empty( $args->has_children );
		$is_cta       = in_array( 'btn-cta', $classes, true );

		if ( $has_children && 0 === $depth ) {
			$this->current_parent_id = (int) $item->ID;
		}

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$li_id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$li_id = $li_id ? ' id="' . esc_attr( $li_id ) . '"' : '';

		if ( $has_children && 0 === $depth ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Alpine directives are hardcoded strings, not user input.
			$output .= $indent . '<li' . $li_id . ' class="' . esc_attr( $class_names ) . '" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">';
		} else {
			$output .= $indent . '<li' . $li_id . ' class="' . esc_attr( $class_names ) . '">';
		}

		// Build <a> attributes.
		$atts           = array();
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ( '_blank' === $item->target && empty( $item->xfn ) )
			? 'noopener noreferrer'
			: ( ! empty( $item->xfn ) ? $item->xfn : '' );
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['class']  = $is_cta ? 'nav-cta-btn' : ( 0 === $depth ? 'nav-link' : 'nav-dropdown-link' );

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$link = '<a';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value ) {
				$link .= 'href' === $attr
					? ' href="' . esc_url( $value ) . '"'
					: ' ' . esc_attr( $attr ) . '="' . esc_attr( $value ) . '"';
			}
		}

		if ( $has_children && 0 === $depth && ! $is_cta ) {
			$submenu_id = 'submenu-' . (int) $item->ID;
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Alpine directives; $submenu_id is cast to int above.
			$link .= ' aria-haspopup="true" :aria-expanded="open.toString()" aria-controls="' . $submenu_id . '" @keydown.enter.prevent="open = ! open" @keydown.space.prevent="open = ! open" @keydown.escape="open = false"';
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$item_output  = isset( $args->before ) ? $args->before : '';
		$item_output .= $link . '>';
		$item_output .= isset( $args->link_before ) ? $args->link_before : '';
		$item_output .= esc_html( $title );

		if ( $has_children && 0 === $depth && ! $is_cta ) {
			$item_output .= '<span class="nav-chevron" aria-hidden="true">'
				. '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
				. '<polyline points="6 9 12 15 18 9"/>'
				. '</svg>'
				. '</span>';
		}

		$item_output .= isset( $args->link_after ) ? $args->link_after : '';
		$item_output .= '</a>';
		$item_output .= isset( $args->after ) ? $args->after : '';

		/** This filter is documented in wp-includes/class-walker-nav-menu.php */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
