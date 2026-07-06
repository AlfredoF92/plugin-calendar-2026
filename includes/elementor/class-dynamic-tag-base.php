<?php
/**
 * Base class for PMI Events text dynamic tags.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shared logic for simple text-based event dynamic tags.
 */
abstract class PMI_Events_Elementor_Tag_Base extends \Elementor\Core\DynamicTags\Tag {

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
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}

	/**
	 * Get current event data, if the current post is a PMI event.
	 *
	 * @return array|null
	 */
	protected function get_event_data() {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Events_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return null;
		}

		return PMI_Events_Meta_Boxes::get_event_data( $post_id );
	}
}
