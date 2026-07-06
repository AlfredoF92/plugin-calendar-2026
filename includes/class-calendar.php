<?php
/**
 * Calendar rendering and queries.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Calendar logic.
 */
class PMI_Events_Calendar {

	/**
	 * Register AJAX handlers.
	 */
	public static function register() {
		add_action( 'wp_ajax_pmi_events_calendar', array( __CLASS__, 'ajax_render' ) );
		add_action( 'wp_ajax_nopriv_pmi_events_calendar', array( __CLASS__, 'ajax_render' ) );
		add_action( 'wp_ajax_pmi_events_day', array( __CLASS__, 'ajax_day_events' ) );
		add_action( 'wp_ajax_nopriv_pmi_events_day', array( __CLASS__, 'ajax_day_events' ) );
		add_action( 'wp_ajax_pmi_events_calendar_full', array( __CLASS__, 'ajax_render_full' ) );
		add_action( 'wp_ajax_nopriv_pmi_events_calendar_full', array( __CLASS__, 'ajax_render_full' ) );
	}

	/**
	 * Render calendar widget HTML.
	 *
	 * @param array $args Widget arguments.
	 * @return string
	 */
	public static function render( $args = array() ) {
		$defaults = array(
			'title'         => __( 'Calendario Eventi', 'pmi-events' ),
			'calendar_url'  => get_post_type_archive_link( PMI_Events_Post_Type::POST_TYPE ),
			'calendar_link' => __( 'Vedi calendario', 'pmi-events' ),
			'year'          => (int) gmdate( 'Y' ),
			'month'         => (int) gmdate( 'n' ),
			'selected_date' => gmdate( 'Y-m-d' ),
		);

		$args = wp_parse_args( $args, $defaults );

		$args['year']          = max( 1970, (int) $args['year'] );
		$args['month']         = min( 12, max( 1, (int) $args['month'] ) );
		$args['selected_date'] = self::sanitize_date( $args['selected_date'], $args['year'], $args['month'] );

		$event_dates = self::get_event_dates_for_month( $args['year'], $args['month'] );
		$day_events  = self::get_events_for_date( $args['selected_date'] );

		$year          = $args['year'];
		$month         = $args['month'];
		$selected_date = $args['selected_date'];

		ob_start();
		include PMI_EVENTS_DIR . 'templates/calendar-widget.php';
		return ob_get_clean();
	}

	/**
	 * AJAX: render full calendar widget.
	 */
	public static function ajax_render() {
		check_ajax_referer( 'pmi_events_calendar', 'nonce' );

		$year  = isset( $_POST['year'] ) ? (int) $_POST['year'] : (int) gmdate( 'Y' );
		$month = isset( $_POST['month'] ) ? (int) $_POST['month'] : (int) gmdate( 'n' );

		$selected = isset( $_POST['selected_date'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_date'] ) ) : gmdate( 'Y-m-d' );

		$calendar_url = isset( $_POST['calendar_url'] ) ? esc_url_raw( wp_unslash( $_POST['calendar_url'] ) ) : '';
		if ( empty( $calendar_url ) ) {
			$calendar_url = get_post_type_archive_link( PMI_Events_Post_Type::POST_TYPE );
		}

		$html = self::render(
			array(
				'title'         => isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : __( 'Calendario Eventi', 'pmi-events' ),
				'calendar_url'  => $calendar_url,
				'calendar_link' => isset( $_POST['calendar_link'] ) ? sanitize_text_field( wp_unslash( $_POST['calendar_link'] ) ) : __( 'Vedi calendario', 'pmi-events' ),
				'year'          => $year,
				'month'         => $month,
				'selected_date' => $selected,
			)
		);

		wp_send_json_success( array( 'html' => $html ) );
	}

	/**
	 * AJAX: events list for a single day.
	 */
	public static function ajax_day_events() {
		check_ajax_referer( 'pmi_events_calendar', 'nonce' );

		$date = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';

		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
			wp_send_json_error( array( 'message' => __( 'Data non valida.', 'pmi-events' ) ) );
		}

		$events = self::get_events_for_date( $date );

		ob_start();
		self::render_day_events( $events, $date );
		$html = ob_get_clean();

		wp_send_json_success(
			array(
				'html'  => $html,
				'date'  => $date,
				'label' => self::format_day_heading( $date ),
			)
		);
	}

	/**
	 * Get Y-m-d dates in a month that have at least one event.
	 *
	 * @param int $year  Year.
	 * @param int $month Month.
	 * @return string[]
	 */
	public static function get_event_dates_for_month( $year, $month ) {
		$start = sprintf( '%04d-%02d-01', $year, $month );
		$end   = gmdate( 'Y-m-t', strtotime( $start . ' UTC' ) );

		$query = new WP_Query(
			array(
				'post_type'      => PMI_Events_Post_Type::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => PMI_Events_Meta_Boxes::META_START_DATE,
						'value'   => $end,
						'compare' => '<=',
						'type'    => 'DATE',
					),
					array(
						'key'     => PMI_Events_Meta_Boxes::META_END_DATE,
						'value'   => $start,
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
			)
		);

		$dates = array();

		foreach ( $query->posts as $post_id ) {
			$event_start = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_DATE, true );
			$event_end   = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_END_DATE, true );

			if ( empty( $event_end ) ) {
				$event_end = $event_start;
			}

			$range_start = max( $start, $event_start );
			$range_end   = min( $end, $event_end );

			if ( $range_start > $range_end ) {
				continue;
			}

			$current = $range_start;
			while ( $current <= $range_end ) {
				$dates[ $current ] = true;
				$current           = gmdate( 'Y-m-d', strtotime( $current . ' +1 day UTC' ) );
			}
		}

		return array_keys( $dates );
	}

	/**
	 * Render full month grid HTML.
	 *
	 * @param array $args Widget arguments.
	 * @return string
	 */
	public static function render_full( $args = array() ) {
		$defaults = array(
			'title'        => __( 'Eventi', 'pmi-events' ),
			'year'         => (int) gmdate( 'Y' ),
			'month'        => (int) gmdate( 'n' ),
			'event_limit'  => 2,
			'wrapper_id'   => 'pmi-events-full-calendar-' . wp_unique_id(),
		);

		$args = wp_parse_args( $args, $defaults );

		$args['year']  = max( 1970, (int) $args['year'] );
		$args['month'] = min( 12, max( 1, (int) $args['month'] ) );

		$year        = $args['year'];
		$month       = $args['month'];
		$title       = $args['title'];
		$event_limit = max( 1, (int) $args['event_limit'] );
		$wrapper_id  = $args['wrapper_id'];
		$events_map  = self::get_events_by_date_for_month( $year, $month );

		ob_start();
		include PMI_EVENTS_DIR . 'templates/calendar-full.php';
		return ob_get_clean();
	}

	/**
	 * AJAX: render full month grid (navigation).
	 */
	public static function ajax_render_full() {
		check_ajax_referer( 'pmi_events_calendar', 'nonce' );

		$year  = isset( $_POST['year'] ) ? (int) $_POST['year'] : (int) gmdate( 'Y' );
		$month = isset( $_POST['month'] ) ? (int) $_POST['month'] : (int) gmdate( 'n' );

		$html = self::render_full(
			array(
				'title'       => isset( $_POST['title'] ) ? sanitize_text_field( wp_unslash( $_POST['title'] ) ) : __( 'Eventi', 'pmi-events' ),
				'year'        => $year,
				'month'       => $month,
				'event_limit' => isset( $_POST['event_limit'] ) ? (int) $_POST['event_limit'] : 2,
			)
		);

		wp_send_json_success( array( 'html' => $html ) );
	}

	/**
	 * Get events grouped by date for an entire month.
	 *
	 * @param int $year  Year.
	 * @param int $month Month.
	 * @return array Map of Y-m-d => event data[].
	 */
	public static function get_events_by_date_for_month( $year, $month ) {
		$start = sprintf( '%04d-%02d-01', $year, $month );
		$end   = gmdate( 'Y-m-t', strtotime( $start . ' UTC' ) );

		$query = new WP_Query(
			array(
				'post_type'      => PMI_Events_Post_Type::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_key'       => PMI_Events_Meta_Boxes::META_START_TIME,
				'orderby'        => array(
					'meta_value' => 'ASC',
					'title'      => 'ASC',
				),
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => PMI_Events_Meta_Boxes::META_START_DATE,
						'value'   => $end,
						'compare' => '<=',
						'type'    => 'DATE',
					),
					array(
						'key'     => PMI_Events_Meta_Boxes::META_END_DATE,
						'value'   => $start,
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
			)
		);

		$map = array();

		foreach ( $query->posts as $post ) {
			$event_data  = PMI_Events_Meta_Boxes::get_event_data( $post->ID );
			$event_start = ! empty( $event_data['start_date'] ) ? $event_data['start_date'] : '';
			$event_end   = ! empty( $event_data['end_date'] ) ? $event_data['end_date'] : $event_start;

			if ( empty( $event_start ) ) {
				continue;
			}

			$range_start = max( $start, $event_start );
			$range_end   = min( $end, $event_end );

			if ( $range_start > $range_end ) {
				continue;
			}

			$current = $range_start;
			while ( $current <= $range_end ) {
				if ( ! isset( $map[ $current ] ) ) {
					$map[ $current ] = array();
				}
				$map[ $current ][] = $event_data;
				$current           = gmdate( 'Y-m-d', strtotime( $current . ' +1 day UTC' ) );
			}
		}

		return $map;
	}

	/**
	 * Short (3-letter) weekday labels, Monday first.
	 *
	 * @return string[]
	 */
	public static function get_weekday_labels_short() {
		$labels = array();
		$base   = strtotime( '2024-01-01 UTC' ); // Monday.

		for ( $i = 0; $i < 7; $i++ ) {
			$timestamp = strtotime( '+' . $i . ' days', $base );
			$labels[]  = mb_strtoupper( date_i18n( 'D', $timestamp ) );
		}

		/**
		 * Filter short weekday labels shown in the full calendar header.
		 *
		 * @param string[] $labels Three-letter weekday labels.
		 */
		return apply_filters( 'pmi_events_weekday_labels_short', $labels );
	}

	/**
	 * Format a short time range for grid previews (e.g. 18:30 - 20:00).
	 *
	 * @param array $event Event data.
	 * @return string
	 */
	public static function format_event_time_short( $event ) {
		$start = self::format_time_24h( $event['start_time'] );
		$end   = self::format_time_24h( $event['end_time'] );

		if ( $start && $end ) {
			return $start . ' - ' . $end;
		}

		return $start;
	}

	/**
	 * 24h time formatting helper.
	 *
	 * @param string $time H:i.
	 * @return string
	 */
	private static function format_time_24h( $time ) {
		if ( empty( $time ) ) {
			return '';
		}

		return date_i18n( 'H:i', strtotime( '1970-01-01 ' . $time . ' UTC' ) );
	}

	/**
	 * Get published events overlapping a date.
	 *
	 * @param string $date Y-m-d.
	 * @return array[]
	 */
	public static function get_events_for_date( $date ) {
		$query = new WP_Query(
			array(
				'post_type'      => PMI_Events_Post_Type::POST_TYPE,
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'     => PMI_Events_Meta_Boxes::META_START_DATE,
						'value'   => $date,
						'compare' => '<=',
						'type'    => 'DATE',
					),
					array(
						'key'     => PMI_Events_Meta_Boxes::META_END_DATE,
						'value'   => $date,
						'compare' => '>=',
						'type'    => 'DATE',
					),
				),
				'meta_key'       => PMI_Events_Meta_Boxes::META_START_TIME,
				'orderby'        => array(
					'meta_value' => 'ASC',
					'title'      => 'ASC',
				),
			)
		);

		$events = array();

		foreach ( $query->posts as $post ) {
			$events[] = PMI_Events_Meta_Boxes::get_event_data( $post->ID );
		}

		return $events;
	}

	/**
	 * Render events list for selected day.
	 *
	 * @param array  $events Events data.
	 * @param string $date   Selected date.
	 */
	public static function render_day_events( $events, $date ) {
		if ( empty( $events ) ) {
			echo '<p class="pmi-events-calendar__empty">' . esc_html__( 'Nessun evento in programma.', 'pmi-events' ) . '</p>';
			return;
		}

		echo '<ul class="pmi-events-calendar__list">';
		foreach ( $events as $event ) {
			$time_label = self::format_event_time( $event, $date );
			?>
			<li class="pmi-events-calendar__item">
				<?php if ( $time_label ) : ?>
					<span class="pmi-events-calendar__item-time"><?php echo esc_html( $time_label ); ?></span>
				<?php endif; ?>
				<a class="pmi-events-calendar__item-title" href="<?php echo esc_url( $event['permalink'] ); ?>">
					<?php echo esc_html( $event['title'] ); ?>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
	}

	/**
	 * Weekday labels (Monday first).
	 *
	 * @return string[]
	 */
	public static function get_weekday_labels() {
		$labels = array();
		$base   = strtotime( '2024-01-01 UTC' ); // Monday.

		for ( $i = 0; $i < 7; $i++ ) {
			$timestamp = strtotime( '+' . $i . ' days', $base );
			$labels[]  = mb_strtoupper( mb_substr( date_i18n( 'D', $timestamp ), 0, 1 ) );
		}

		/**
		 * Filter weekday labels shown in the calendar header.
		 *
		 * @param string[] $labels Single-letter weekday labels.
		 */
		return apply_filters( 'pmi_events_weekday_labels', $labels );
	}

	/**
	 * Format month heading.
	 *
	 * @param int $year  Year.
	 * @param int $month Month.
	 * @return string
	 */
	public static function format_month_heading( $year, $month ) {
		$timestamp = gmmktime( 0, 0, 0, $month, 1, $year );
		return date_i18n( 'F Y', $timestamp );
	}

	/**
	 * Format selected day heading.
	 *
	 * @param string $date Y-m-d.
	 * @return string
	 */
	public static function format_day_heading( $date ) {
		$timestamp = strtotime( $date . ' UTC' );
		return date_i18n( 'j F', $timestamp );
	}

	/**
	 * Format event time line for calendar list.
	 *
	 * @param array  $event Event data.
	 * @param string $date  Selected date.
	 * @return string
	 */
	public static function format_event_time( $event, $date ) {
		$day_label = self::format_day_heading( $date );
		$parts     = array( $day_label );

		if ( ! empty( $event['start_time'] ) ) {
			$start = date_i18n( get_option( 'time_format' ), strtotime( '1970-01-01 ' . $event['start_time'] . ' UTC' ) );
			$line  = '@ ' . $start;

			if ( ! empty( $event['end_time'] ) ) {
				$end  = date_i18n( get_option( 'time_format' ), strtotime( '1970-01-01 ' . $event['end_time'] . ' UTC' ) );
				$line = '@ ' . $start . ' - ' . $end;
			}

			$parts[] = $line;
		}

		return implode( ' ', $parts );
	}

	/**
	 * Ensure selected date belongs to month or fallback to today/first of month.
	 *
	 * @param string $date  Y-m-d.
	 * @param int    $year  Year.
	 * @param int    $month Month.
	 * @return string
	 */
	private static function sanitize_date( $date, $year, $month ) {
		if ( preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches ) ) {
			if ( (int) $matches[1] === $year && (int) $matches[2] === $month ) {
				return $date;
			}
		}

		$today_year  = (int) gmdate( 'Y' );
		$today_month = (int) gmdate( 'n' );

		if ( $year === $today_year && $month === $today_month ) {
			return gmdate( 'Y-m-d' );
		}

		return sprintf( '%04d-%02d-01', $year, $month );
	}
}
