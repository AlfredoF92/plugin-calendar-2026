<?php
/**
 * Elementor dynamic tag: event registration URL.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs the event registration URL for Elementor Link controls (buttons, etc.).
 */
class PMI_Events_Elementor_Tag_Registration_Url extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Tag identifier.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'pmi-event-registration-url';
	}

	/**
	 * Tag label in Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'URL iscrizione evento', 'pmi-events' );
	}

	/**
	 * Tag group in Elementor panel.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'pmi-events';
	}

	/**
	 * Supported widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY );
	}

	/**
	 * Return URL value expected by Elementor Link/URL controls.
	 *
	 * @param array $options Tag options.
	 * @return array
	 */
	public function get_value( array $options = array() ) {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Events_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$url = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_REGISTRATION_URL, true );

		if ( empty( $url ) ) {
			return array();
		}

		return array(
			'url' => $url,
		);
	}
}
