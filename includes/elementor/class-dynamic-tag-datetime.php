<?php
/**
 * Elementor dynamic tag: event date and time.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs formatted event datetime for Elementor widgets.
 */
class PMI_Events_Elementor_Tag_Datetime extends PMI_Events_Elementor_Tag_Base {

	/**
	 * Tag identifier.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'pmi-event-datetime';
	}

	/**
	 * Tag label in Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Data e ora evento', 'pmi-events' );
	}

	/**
	 * Render tag value.
	 */
	public function render() {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Events_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return;
		}

		$value = PMI_Events_Formatter::format_event_datetime( $post_id );

		if ( '' === $value ) {
			return;
		}

		echo esc_html( $value );
	}
}
