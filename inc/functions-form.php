<?php
/**
 * Form handling, logic, and validation functions.
 *
 * @package ai-driven-boilerplate
 */

/**
 * Validate contact form data.
 *
 * @param array $data Sanitized form data with keys: name, email, subject, message.
 * @return array Associative array of field => error message. Empty array on success.
 */
function ai_driven_validate_contact_form( $data ) {
	$errors = array();

	// Name — required, max 100 chars.
	if ( empty( $data['name'] ) ) {
		$errors['name'] = __( 'Name is required.', 'ai-driven-boilerplate' );
	} elseif ( mb_strlen( $data['name'] ) > 100 ) {
		$errors['name'] = __( 'Name must be 100 characters or fewer.', 'ai-driven-boilerplate' );
	}

	// Email — required, valid format.
	if ( empty( $data['email'] ) ) {
		$errors['email'] = __( 'Email address is required.', 'ai-driven-boilerplate' );
	} elseif ( ! is_email( $data['email'] ) ) {
		$errors['email'] = __( 'Please enter a valid email address.', 'ai-driven-boilerplate' );
	}

	// Subject — required, max 200 chars.
	if ( empty( $data['subject'] ) ) {
		$errors['subject'] = __( 'Subject is required.', 'ai-driven-boilerplate' );
	} elseif ( mb_strlen( $data['subject'] ) > 200 ) {
		$errors['subject'] = __( 'Subject must be 200 characters or fewer.', 'ai-driven-boilerplate' );
	}

	// Message — required, min 10 chars.
	if ( empty( $data['message'] ) ) {
		$errors['message'] = __( 'Message is required.', 'ai-driven-boilerplate' );
	} elseif ( mb_strlen( $data['message'] ) < 10 ) {
		$errors['message'] = __( 'Message must be at least 10 characters.', 'ai-driven-boilerplate' );
	}

	return $errors;
}
