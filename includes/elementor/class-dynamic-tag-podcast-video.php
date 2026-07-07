<?php
/**
 * Elementor dynamic tags for podcast YouTube video.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

class PMI_Podcast_Elementor_Tag_Youtube_Video_Url extends PMI_Podcast_Elementor_Tag_Url_Base {
	public function get_name() {
		return 'pmi-podcast-youtube-video-url';
	}

	public function get_title() {
		return __( 'URL video YouTube', 'pmi-events' );
	}

	protected function get_url_for_post( $post_id ) {
		return (string) get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_YOUTUBE_VIDEO, true );
	}
}

class PMI_Podcast_Elementor_Tag_Youtube_Video_Embed extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() {
		return 'pmi-podcast-youtube-video-embed';
	}

	public function get_title() {
		return __( 'Embed video YouTube', 'pmi-events' );
	}

	public function render() {
		$d = $this->get_podcast_data();

		if ( ! $d || empty( $d['youtube_video'] ) ) {
			return;
		}

		$html = PMI_Podcast_Video::render_for_post( $d['id'] );

		if ( '' === $html ) {
			return;
		}

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
