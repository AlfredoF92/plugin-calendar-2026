<?php
/**
 * Elementor dynamic tags: podcast URL fields.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

abstract class PMI_Podcast_Elementor_Tag_Url_Base extends \Elementor\Core\DynamicTags\Data_Tag {

	public function get_group() {
		return 'pmi-podcast';
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY );
	}

	/**
	 * @param int $post_id Podcast post ID.
	 * @return string
	 */
	abstract protected function get_url_for_post( $post_id );

	public function get_value( array $options = array() ) {
		$post_id = get_the_ID();

		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}

		$url = $this->get_url_for_post( $post_id );

		return $url ? array( 'url' => $url ) : array();
	}
}

class PMI_Podcast_Elementor_Tag_Permalink extends PMI_Podcast_Elementor_Tag_Url_Base {
	public function get_name() {
		return 'pmi-podcast-permalink';
	}

	public function get_title() {
		return __( 'URL episodio', 'pmi-events' );
	}

	protected function get_url_for_post( $post_id ) {
		return (string) get_permalink( $post_id );
	}
}

class PMI_Podcast_Elementor_Tag_Thumbnail_Url extends PMI_Podcast_Elementor_Tag_Url_Base {
	public function get_name() {
		return 'pmi-podcast-thumbnail-url';
	}

	public function get_title() {
		return __( 'URL immagine copertina', 'pmi-events' );
	}

	protected function get_url_for_post( $post_id ) {
		$url = get_the_post_thumbnail_url( $post_id, 'large' );
		return $url ? (string) $url : '';
	}
}
