<?php
/**
 * Elementor dynamic tags for podcast YouTube video.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

class PMI_Podcast_Elementor_Tag_Youtube_Video_Url extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() {
		return 'pmi-podcast-youtube-video-url';
	}

	public function get_title() {
		return __( 'URL video YouTube', 'pmi-events' );
	}

	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::URL_CATEGORY );
	}

	public function render() {
		$d = $this->get_podcast_data();

		if ( ! $d || empty( $d['youtube_video'] ) ) {
			return;
		}

		echo esc_url( $d['youtube_video'] );
	}
}
