<?php
/**
 * Event display formatting helpers.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Shared formatting for event date/time labels.
 */
class PMI_Events_Formatter {

	/**
	 * Format event date and time like: 22 Luglio @ 18:30 - 20:00
	 *
	 * Multi-day example: 17 Ottobre @ 8:30 - 18 Ottobre @ 13:00
	 *
	 * @param int $post_id Event post ID.
	 * @return string
	 */
	public static function format_event_datetime( $post_id ) {
		$data = PMI_Events_Meta_Boxes::get_event_data( $post_id );

		if ( empty( $data['start_date'] ) ) {
			return '';
		}

		$start_date = $data['start_date'];
		$end_date   = ! empty( $data['end_date'] ) ? $data['end_date'] : $start_date;
		$start_day  = self::format_day_month( $start_date );
		$start_time = self::format_time( $data['start_time'] );
		$end_time   = self::format_time( $data['end_time'] );

		if ( $start_date !== $end_date ) {
			$end_day = self::format_day_month( $end_date );

			if ( $start_time && $end_time ) {
				return sprintf( '%s @ %s - %s @ %s', $start_day, $start_time, $end_day, $end_time );
			}

			if ( $start_time ) {
				return sprintf( '%s @ %s - %s', $start_day, $start_time, $end_day );
			}

			return sprintf( '%s - %s', $start_day, $end_day );
		}

		if ( $start_time && $end_time ) {
			return sprintf( '%s @ %s - %s', $start_day, $start_time, $end_time );
		}

		if ( $start_time ) {
			return sprintf( '%s @ %s', $start_day, $start_time );
		}

		return $start_day;
	}

	/**
	 * Day + full month name.
	 *
	 * @param string $date Y-m-d.
	 * @return string
	 */
	public static function format_day_month( $date ) {
		return date_i18n( 'j F', strtotime( $date . ' UTC' ) );
	}

	/**
	 * 24-hour time label.
	 *
	 * @param string $time H:i.
	 * @return string
	 */
	public static function format_time( $time ) {
		if ( empty( $time ) ) {
			return '';
		}

		return date_i18n( 'H:i', strtotime( '1970-01-01 ' . $time . ' UTC' ) );
	}
}
