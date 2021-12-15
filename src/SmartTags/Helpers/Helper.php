<?php

namespace WPFormsUserRegistration\SmartTags\Helpers;

/**
 * SmartTags Helper class.
 *
 * @since 2.0.0
 */
class Helper {

	/**
	 * Registered user.
	 *
	 * @since 2.0.0
	 *
	 * @var false|\WP_User
	 */
	private static $user = false;

	/**
	 * Set user.
	 *
	 * @since 2.0.0
	 *
	 * @param int|\WP_User $user User to set.
	 */
	public static function set_user( $user ) {

		self::$user = is_object( $user ) ? $user : get_user_by( 'id', $user );
	}

	/**
	 * Get User from submitted registration form.
	 *
	 * @since 2.0.0
	 *
	 * @return false|\WP_User
	 */
	public static function get_user() {

		if ( self::$user ) {
			return self::$user;
		}

		if ( empty( $_POST['wpforms']['id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return false;
		}

		$form_data = wpforms()->get( 'form' )->get( sanitize_key( wp_unslash( $_POST['wpforms']['id'] ) ), [ 'content_only' => true ] ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( isset( $form_data['settings']['registration_username'], $_POST['wpforms']['fields'][ $form_data['settings']['registration_username'] ] ) && ! empty( $_POST['wpforms']['fields'][ $form_data['settings']['registration_username'] ] ) && ! is_array( $_POST['wpforms']['fields'][ $form_data['settings']['registration_username'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			self::$user = get_user_by( 'login', sanitize_user( wp_unslash( $_POST['wpforms']['fields'][ $form_data['settings']['registration_username'] ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			return self::$user;
		}

		if ( isset( $form_data['settings']['registration_email'], $_POST['wpforms']['fields'][ $form_data['settings']['registration_email'] ] ) && ! empty( $_POST['wpforms']['fields'][ $form_data['settings']['registration_email'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			self::$user = get_user_by( 'email', sanitize_email( wp_unslash( $_POST['wpforms']['fields'][ $form_data['settings']['registration_email'] ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			return self::$user;
		}

		$email_field = wpforms_get_form_fields_by_meta( 'nickname', 'email', $form_data );
		$email_field = reset( $email_field );

		if ( $email_field && isset( $_POST['wpforms']['fields'][ $email_field['id'] ] ) && ! empty( $_POST['wpforms']['fields'][ $email_field['id'] ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			self::$user = get_user_by( 'email', sanitize_email( wp_unslash( $_POST['wpforms']['fields'][ $email_field['id'] ] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			return self::$user;
		}

		return false;
	}
}
