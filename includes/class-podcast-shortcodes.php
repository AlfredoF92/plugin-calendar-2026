<?php
/**
 * Podcast shortcodes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers podcast shortcodes.
 */
class PMI_Podcast_Shortcodes {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_shortcode( 'pmi_podcast_grid', array( __CLASS__, 'grid' ) );
		add_shortcode( 'pmi_podcast_links', array( __CLASS__, 'links' ) );
		add_shortcode( 'pmi_podcast_video', array( __CLASS__, 'video' ) );
	}

	/**
	 * Podcast grid shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function grid( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'          => '',
				'posts_per_page' => 6,
				'category'       => '',
				'columns'        => 3,
				'archive_link'   => 'yes',
				'archive_label'  => __( 'Vedi tutti gli episodi', 'pmi-events' ),
			),
			$atts,
			'pmi_podcast_grid'
		);

		PMI_Podcast_Assets::enqueue_grid();

		$query_args = array(
			'post_type'      => PMI_Podcast_Post_Type::POST_TYPE,
			'posts_per_page' => (int) $atts['posts_per_page'],
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( ! empty( $atts['category'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => PMI_Podcast_Taxonomy::TAXONOMY,
					'field'    => 'slug',
					'terms'    => sanitize_title( $atts['category'] ),
				),
			);
		}

		$query   = new WP_Query( $query_args );
		$columns = max( 2, min( 4, (int) $atts['columns'] ) );

		ob_start();
		include PMI_EVENTS_DIR . 'templates/podcast-grid.php';
		$html = ob_get_clean();

		wp_reset_postdata();

		return $html;
	}

	/**
	 * Listening platform links for a single podcast episode.
	 *
	 * Use on the episode template page in Elementor (Shortcode widget).
	 *
	 * Attributes:
	 * - title   Optional heading above the list (default: "Ascolta su").
	 * - post_id Optional post ID (default: current post in the loop).
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function links( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'   => __( 'Ascolta su', 'pmi-events' ),
				'post_id' => 0,
			),
			$atts,
			'pmi_podcast_links'
		);

		$post_id = (int) $atts['post_id'];

		if ( $post_id <= 0 ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return '';
		}

		$links = PMI_Podcast_Icons::get_listening_links( $post_id );

		if ( empty( $links ) ) {
			return '';
		}

		PMI_Podcast_Assets::enqueue_grid();

		ob_start();
		include PMI_EVENTS_DIR . 'templates/podcast-links.php';
		return ob_get_clean();
	}

	/**
	 * Embedded YouTube video for a single podcast episode.
	 *
	 * Use on the episode template page in Elementor (Shortcode widget).
	 *
	 * Attributes:
	 * - post_id Optional post ID (default: current post in the loop).
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function video( $atts ) {
		$atts = shortcode_atts(
			array(
				'post_id' => 0,
			),
			$atts,
			'pmi_podcast_video'
		);

		$post_id = (int) $atts['post_id'];

		if ( $post_id <= 0 ) {
			$post_id = get_the_ID();
		}

		if ( ! $post_id || PMI_Podcast_Post_Type::POST_TYPE !== get_post_type( $post_id ) ) {
			return '';
		}

		$embed_html = PMI_Podcast_Video::get_embed_html( PMI_Podcast_Video::get_video_url( $post_id ) );

		if ( '' === $embed_html ) {
			return '';
		}

		PMI_Podcast_Assets::enqueue_grid();

		ob_start();
		include PMI_EVENTS_DIR . 'templates/podcast-video.php';
		return ob_get_clean();
	}
}
