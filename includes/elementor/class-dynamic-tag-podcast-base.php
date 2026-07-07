<?php
/**
 * Base class for PMI Podcast text dynamic tags.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

abstract class PMI_Podcast_Elementor_Tag_Base extends \Elementor\Core\DynamicTags\Tag {

	public function get_group() {
		return 'pmi-podcast';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
	}

	protected function get_podcast_data() {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return null;
		}

		return PMI_Podcast_Meta_Boxes::get_podcast_data( $post_id );
	}
}
