<?php
/**
 * Calendar widget template.
 *
 * @package PMI_Events
 *
 * @var array   $args        Widget arguments.
 * @var int     $year
 * @var int     $month
 * @var string  $selected_date
 * @var string[] $event_dates
 * @var array[] $day_events
 */

defined( 'ABSPATH' ) || exit;

$wrapper_id    = isset( $args['wrapper_id'] ) ? $args['wrapper_id'] : 'pmi-events-calendar-' . wp_unique_id();
$title         = $args['title'];
$calendar_url  = $args['calendar_url'];
$calendar_link = $args['calendar_link'];
$weekdays      = PMI_Events_Calendar::get_weekday_labels();
$today         = gmdate( 'Y-m-d' );

$prev_month = $month - 1;
$prev_year  = $year;
if ( $prev_month < 1 ) {
	$prev_month = 12;
	--$prev_year;
}

$next_month = $month + 1;
$next_year  = $year;
if ( $next_month > 12 ) {
	$next_month = 1;
	++$next_year;
}

$first_day          = (int) gmdate( 'N', gmmktime( 0, 0, 0, $month, 1, $year ) );
$days_in_month      = (int) gmdate( 't', gmmktime( 0, 0, 0, $month, 1, $year ) );
$prev_month_days    = (int) gmdate( 't', gmmktime( 0, 0, 0, $prev_month, 1, $prev_year ) );
$leading_days       = $first_day - 1;
$event_lookup       = array_fill_keys( $event_dates, true );
$trailing_days      = ( 7 - ( ( $leading_days + $days_in_month ) % 7 ) ) % 7;
?>
<div
	id="<?php echo esc_attr( $wrapper_id ); ?>"
	class="pmi-events-calendar"
	data-year="<?php echo esc_attr( $year ); ?>"
	data-month="<?php echo esc_attr( $month ); ?>"
	data-selected="<?php echo esc_attr( $selected_date ); ?>"
	data-title="<?php echo esc_attr( $title ); ?>"
	data-calendar-url="<?php echo esc_url( $calendar_url ); ?>"
	data-calendar-link="<?php echo esc_attr( $calendar_link ); ?>"
>
	<div class="pmi-events-calendar__header">
		<?php echo esc_html( $title ); ?>
	</div>

	<div class="pmi-events-calendar__body">
		<div class="pmi-events-calendar__nav">
			<button type="button" class="pmi-events-calendar__nav-btn" data-action="prev" data-year="<?php echo esc_attr( $prev_year ); ?>" data-month="<?php echo esc_attr( $prev_month ); ?>" aria-label="<?php esc_attr_e( 'Mese precedente', 'pmi-events' ); ?>">
				<span aria-hidden="true">&lsaquo;</span>
			</button>
			<div class="pmi-events-calendar__month">
				<?php echo esc_html( PMI_Events_Calendar::format_month_heading( $year, $month ) ); ?>
			</div>
			<button type="button" class="pmi-events-calendar__nav-btn" data-action="next" data-year="<?php echo esc_attr( $next_year ); ?>" data-month="<?php echo esc_attr( $next_month ); ?>" aria-label="<?php esc_attr_e( 'Mese successivo', 'pmi-events' ); ?>">
				<span aria-hidden="true">&rsaquo;</span>
			</button>
		</div>

		<div class="pmi-events-calendar__weekdays" aria-hidden="true">
			<?php foreach ( $weekdays as $label ) : ?>
				<span><?php echo esc_html( $label ); ?></span>
			<?php endforeach; ?>
		</div>

		<div class="pmi-events-calendar__grid" role="grid" aria-label="<?php echo esc_attr( PMI_Events_Calendar::format_month_heading( $year, $month ) ); ?>">
			<?php
			for ( $i = 0; $i < $leading_days; $i++ ) {
				$adj_day = $prev_month_days - $leading_days + $i + 1;
				?>
				<span class="pmi-events-calendar__day pmi-events-calendar__day--adjacent" aria-hidden="true">
					<span class="pmi-events-calendar__day-number"><?php echo esc_html( (string) $adj_day ); ?></span>
				</span>
				<?php
			}

			for ( $day = 1; $day <= $days_in_month; $day++ ) {
				$date       = sprintf( '%04d-%02d-%02d', $year, $month, $day );
				$classes    = array( 'pmi-events-calendar__day' );
				$is_today   = ( $date === $today );
				$is_selected = ( $date === $selected_date );
				$has_events = isset( $event_lookup[ $date ] );

				if ( $is_today ) {
					$classes[] = 'pmi-events-calendar__day--today';
				}
				if ( $is_selected ) {
					$classes[] = 'pmi-events-calendar__day--selected';
				}
				if ( $has_events ) {
					$classes[] = 'pmi-events-calendar__day--has-events';
				}
				?>
				<button
					type="button"
					class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
					data-date="<?php echo esc_attr( $date ); ?>"
					role="gridcell"
					aria-current="<?php echo $is_selected ? 'date' : 'false'; ?>"
					aria-label="<?php echo esc_attr( date_i18n( 'j F Y', strtotime( $date . ' UTC' ) ) ); ?>"
				>
					<span class="pmi-events-calendar__day-number"><?php echo esc_html( (string) $day ); ?></span>
					<?php if ( $has_events ) : ?>
						<span class="pmi-events-calendar__day-dot" aria-hidden="true"></span>
					<?php endif; ?>
				</button>
				<?php
			}

			for ( $day = 1; $day <= $trailing_days; $day++ ) {
				?>
				<span class="pmi-events-calendar__day pmi-events-calendar__day--adjacent" aria-hidden="true">
					<span class="pmi-events-calendar__day-number"><?php echo esc_html( (string) $day ); ?></span>
				</span>
				<?php
			}
			?>
		</div>

		<div class="pmi-events-calendar__events">
			<h3 class="pmi-events-calendar__events-date">
				<span class="pmi-events-calendar__events-date-text">
					<?php echo esc_html( PMI_Events_Calendar::format_day_heading( $selected_date ) ); ?>
				</span>
				<span class="pmi-events-calendar__events-date-line" aria-hidden="true"></span>
			</h3>
			<div class="pmi-events-calendar__events-list" data-events-list>
				<?php PMI_Events_Calendar::render_day_events( $day_events, $selected_date ); ?>
			</div>
		</div>

		<?php if ( ! empty( $calendar_url ) ) : ?>
			<div class="pmi-events-calendar__footer">
				<a class="pmi-events-calendar__footer-link" href="<?php echo esc_url( $calendar_url ); ?>">
					<?php echo esc_html( $calendar_link ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
