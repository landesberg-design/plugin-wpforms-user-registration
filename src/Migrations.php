<?php

namespace WPFormsUserRegistration;

use WPFormsUserRegistration\EmailNotifications\Helper;

/**
 * Class Migrations handles upgrade routines.
 *
 * @since 2.0.0
 */
class Migrations {

	/**
	 * WP option name to store the migration version.
	 *
	 * @since 2.0.0
	 */
	const OPTION_NAME = 'wpforms_user_registration_version';

	/**
	 * Class init.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		$this->maybe_migrate();
		$this->update_version();
	}

	/**
	 * Run the migration if needed.
	 *
	 * @since 2.0.0
	 */
	private function maybe_migrate() {

		// Retrieve the last known version.
		$version = get_option( self::OPTION_NAME );

		if ( empty( $version ) ) {
			$version = '1.0.0';
		}

		$this->migrate( $version );
	}

	/**
	 * Run the migrations for a specific version.
	 *
	 * @since 2.0.0
	 *
	 * @param string $version Version to run the migrations for.
	 */
	private function migrate( $version ) {

		if ( version_compare( $version, '2.0.0', '<' ) ) {
			$this->v200_upgrade();
		}
	}

	/**
	 * Do all the required migrations for addon version 2.0.0.
	 *
	 * @since 2.0.0
	 */
	private function v200_upgrade() {

		if ( get_option( Helper::LEGACY_EMAILS ) === '1' ) {
			return;
		}

		if ( ! $this->is_used_before() ) {
			return;
		}

		add_option( Helper::LEGACY_EMAILS, 1 );
	}

	/**
	 * Update the version option in database.
	 *
	 * @since 2.0.0
	 */
	private function update_version() {

		update_option( self::OPTION_NAME, WPFORMS_USER_REGISTRATION_VERSION );
	}

	/**
	 * Check if addon was used before.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	private function is_used_before() {

		$forms = wpforms()->get( 'form' )->get(
			'',
			[
				'content_only' => true,
				'cap'          => false,
			]
		);

		if ( ! is_array( $forms ) || empty( $forms ) ) {
			return false;
		}

		$templates = [ 'user_registration', 'user_login' ];

		foreach ( $forms as $form ) {

			$form_data = wpforms_decode( $form->post_content );

			if ( ! empty( $form_data['meta']['template'] ) && in_array( $form_data['meta']['template'], $templates, true ) ) {
				return true;
			}
		}

		return false;
	}
}
