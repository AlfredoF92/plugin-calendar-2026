<?php
/**
 * Shortcodes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers plugin shortcodes.
 */
class PMI_Events_Shortcodes {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_shortcode( 'pmi_calendar', array( __CLASS__, 'calendar' ) );
		add_shortcode( 'pmi_calendar_full', array( __CLASS__, 'calendar_full' ) );
	}

	/**
	 * Calendar widget shortcode.
	 *
	 * Attributes:
	 * - title         Header title.
	 * - calendar_url  Link for "view calendar" footer.
	 * - calendar_link Footer link label.
	 * - month         Initial month (1-12).
	 * - year          Initial year.
	 * - date          Initially selected date (Y-m-d).
	 * - width         Widget width (e.g. 600, 600px, 40rem).
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function calendar( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'         => __( 'Calendario Eventi', 'pmi-events' ),
				'calendar_url'  => get_post_type_archive_link( PMI_Events_Post_Type::POST_TYPE ),
				'calendar_link' => __( 'Vedi calendario', 'pmi-events' ),
				'month'         => (int) gmdate( 'n' ),
				'year'          => (int) gmdate( 'Y' ),
				'date'          => gmdate( 'Y-m-d' ),
				'width'         => '480',
			),
			$atts,
			'pmi_calendar'
		);

		PMI_Events_Assets::enqueue_calendar();

		$width      = self::sanitize_width( $atts['width'] );
		$wrapper_id = 'pmi-events-calendar-' . wp_unique_id();

		$html = PMI_Events_Calendar::render(
			array(
				'title'         => $atts['title'],
				'calendar_url'  => $atts['calendar_url'],
				'calendar_link' => $atts['calendar_link'],
				'year'          => (int) $atts['year'],
				'month'         => (int) $atts['month'],
				'selected_date' => $atts['date'],
				'wrapper_id'    => $wrapper_id,
			)
		);

		return sprintf(
			'<div class="pmi-events-calendar-wrap" style="--pmi-calendar-width:%s">%s</div>',
			esc_attr( $width ),
			$html
		);
	}

	/**
	 * Full month calendar grid shortcode.
	 *
	 * Attributes:
	 * - title        Optional internal title (used for AJAX round-trips).
	 * - month        Initial month (1-12).
	 * - year         Initial year.
	 * - event_limit  Max events shown per day before "+N altri" (default 2).
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public static function calendar_full( $atts ) {
		$atts = shortcode_atts(
			array(
				'title'       => __( 'Eventi', 'pmi-events' ),
				'month'       => (int) gmdate( 'n' ),
				'year'        => (int) gmdate( 'Y' ),
				'event_limit' => 2,
			),
			$atts,
			'pmi_calendar_full'
		);

		PMI_Events_Assets::enqueue_calendar_full();

		$wrapper_id = 'pmi-events-full-calendar-' . wp_unique_id();

		$html = PMI_Events_Calendar::render_full(
			array(
				'title'       => $atts['title'],
				'year'        => (int) $atts['year'],
				'month'       => (int) $atts['month'],
				'event_limit' => (int) $atts['event_limit'],
				'wrapper_id'  => $wrapper_id,
			)
		);

		return sprintf( '<div class="pmi-events-full-calendar-shell">%s</div>', $html );
	}

	/**
	 * Normalize width attribute.
	 *
	 * @param string $width Raw width value.
	 * @return string
	 */
	private static function sanitize_width( $width ) {
		$width = trim( (string) $width );

		if ( preg_match( '/^\d+$/', $width ) ) {
			return $width . 'px';
		}

		if ( preg_match( '/^\d+(\.\d+)?(px|rem|em|%)$/i', $width ) ) {
			return strtolower( $width );
		}

		return '480px';
	}
}
