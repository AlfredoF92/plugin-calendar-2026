<?php
/**
 * Elementor dynamic tags: podcast platform links.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

abstract class PMI_Podcast_Elementor_Tag_Link_Base extends \Elementor\Core\DynamicTags\Data_Tag {
	abstract protected function get_meta_key();
	public function get_group() { return 'pmi-podcast'; }
	public function get_categories() { return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ); }
	public function get_value( array $options = array() ) {
		$post_id = get_the_ID();
		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return array();
		}
		$url = get_post_meta( $post_id, $this->get_meta_key(), true );
		return $url ? array( 'url' => $url ) : array();
	}
}

class PMI_Podcast_Elementor_Tag_Link_Apple extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-apple'; }
	public function get_title() { return __( 'Link Apple Podcast', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_APPLE; }
}
class PMI_Podcast_Elementor_Tag_Link_Spotify extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-spotify'; }
	public function get_title() { return __( 'Link Spotify', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_SPOTIFY; }
}
class PMI_Podcast_Elementor_Tag_Link_Youtube extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-youtube'; }
	public function get_title() { return __( 'Link YouTube', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_YOUTUBE; }
}
class PMI_Podcast_Elementor_Tag_Link_Youtube_Music extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-youtube-music'; }
	public function get_title() { return __( 'Link YouTube Music', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_YOUTUBE_MUSIC; }
}
class PMI_Podcast_Elementor_Tag_Link_Amazon_Music extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-amazon-music'; }
	public function get_title() { return __( 'Link Amazon Music', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_AMAZON_MUSIC; }
}
class PMI_Podcast_Elementor_Tag_Link_Spreaker extends PMI_Podcast_Elementor_Tag_Link_Base {
	public function get_name() { return 'pmi-podcast-link-spreaker'; }
	public function get_title() { return __( 'Link Spreaker', 'pmi-events' ); }
	protected function get_meta_key() { return PMI_Podcast_Meta_Boxes::META_LINK_SPREAKER; }
}
