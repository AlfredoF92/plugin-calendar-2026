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
}
