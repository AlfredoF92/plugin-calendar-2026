<?php
/**
 * Elementor dynamic tag: podcast cover image.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

class PMI_Podcast_Elementor_Tag_Image extends \Elementor\Core\DynamicTags\Data_Tag {
	public function get_name() { return 'pmi-podcast-image'; }
	public function get_title() { return __( 'Immagine copertina episodio', 'pmi-events' ); }
	public function get_group() { return 'pmi-podcast'; }
	public function get_categories() { return array( \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ); }
	public function get_value( array $options = array() ) {
		$post_id = get_the_ID();
		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
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
