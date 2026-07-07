<?php
/**
 * YouTube video helpers for podcast episodes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Parses YouTube URLs and builds responsive embed markup.
 */
class PMI_Podcast_Video {

	/**
	 * Extract a YouTube video ID from common share URL formats.
	 *
	 * @param string $url YouTube URL.
	 * @return string
	 */
	public static function get_youtube_id( $url ) {
		$url = trim( (string) $url );

		if ( '' === $url ) {
			return '';
		}

		$patterns = array(
			'#(?:youtube\.com/watch\?(?:.*&)?v=|youtube\.com/embed/|youtube\.com/shorts/|youtu\.be/)([a-zA-Z0-9_-]{11})#',
		);

		foreach ( $patterns as $pattern ) {
			if ( preg_match( $pattern, $url, $matches ) ) {
				return $matches[1];
			}
		}

		return '';
	}

	/**
	 * Get stored YouTube video URL for an episode.
	 *
	 * @param int $post_id Podcast post ID.
	 * @return string
	 */
	public static function get_video_url( $post_id ) {
		return (string) get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_YOUTUBE_VIDEO, true );
	}

	/**
	 * Build embed HTML for a YouTube URL.
	 *
	 * @param string $url YouTube share URL.
	 * @return string
	 */
	public static function get_embed_html( $url ) {
		$url = trim( (string) $url );

		if ( '' === $url ) {
			return '';
		}

		$embed = wp_oembed_get( $url, array( 'width' => 1280 ) );

		if ( $embed ) {
			return $embed;
		}

		$video_id = self::get_youtube_id( $url );

		if ( '' === $video_id ) {
			return '';
		}

		return sprintf(
			'<iframe class="pmi-podcast-video__iframe" width="1280" height="720" src="https://www.youtube.com/embed/%1$s" title="%2$s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>',
			esc_attr( $video_id ),
			esc_attr__( 'Video YouTube', 'pmi-events' )
		);
	}

	/**
	 * Render responsive video wrapper for an episode.
	 *
	 * @param int $post_id Podcast post ID.
	 * @return string
	 */
	public static function render_for_post( $post_id ) {
		$embed = self::get_embed_html( self::get_video_url( $post_id ) );

		if ( '' === $embed ) {
			return '';
		}

		return sprintf(
			'<div class="pmi-podcast-video-shell"><div class="pmi-podcast-video">%s</div></div>',
			$embed
		);
	}
}
