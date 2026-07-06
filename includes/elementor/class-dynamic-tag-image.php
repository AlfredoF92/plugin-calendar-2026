<?php
/**
 * Elementor dynamic tag: event cover image.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Outputs the event featured image for Elementor Image widgets.
 */
class PMI_Events_Elementor_Tag_Image extends \Elementor\Core\DynamicTags\Data_Tag {

	/**
	 * Tag identifier.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'pmi-event-image';
	}

	/**
	 * Tag label in Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Immagine copertina evento', 'pmi-events' );
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
		return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY );
	}

	/**
	 * Return image value expected by Elementor Image control.
	 *
	 * @param array $options Tag options.
	 * @return array
	 */
	public function get_value( array $options = array() ) {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Events_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$attachment_id = get_post_thumbnail_id( $post_id );

		if ( ! $attachment_id ) {
			return array();
		}

		return array(
			'id'  => $attachment_id,
			'url' => wp_get_attachment_url( $attachment_id ),
		);
	}
}
