<?php
/**
 * Full month calendar grid template.
 *
 * @package PMI_Events
 *
 * @var string $wrapper_id
 * @var string $title
 * @var int    $year
 * @var int    $month
 * @var int    $event_limit
 * @var array  $events_map
 */

defined( 'ABSPATH' ) || exit;

$today = gmdate( 'Y-m-d' );

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

$first_day       = (int) gmdate( 'N', gmmktime( 0, 0, 0, $month, 1, $year ) );
$days_in_month   = (int) gmdate( 't', gmmktime( 0, 0, 0, $month, 1, $year ) );
$prev_month_days = (int) gmdate( 't', gmmktime( 0, 0, 0, $prev_month, 1, $prev_year ) );
$leading_days    = $first_day - 1;
$trailing_days   = ( 7 - ( ( $leading_days + $days_in_month ) % 7 ) ) % 7;
$weekdays        = PMI_Events_Calendar::get_weekday_labels_short();
$is_current_month = ( $year === (int) gmdate( 'Y' ) && $month === (int) gmdate( 'n' ) );
?>
<div
	id="<?php echo esc_attr( $wrapper_id ); ?>"
	class="pmi-events-full-calendar"
	data-year="<?php echo esc_attr( $year ); ?>"
	data-month="<?php echo esc_attr( $month ); ?>"
	data-title="<?php echo esc_attr( $title ); ?>"
	data-event-limit="<?php echo esc_attr( $event_limit ); ?>"
>
	<div class="pmi-events-full-calendar__toolbar">
		<div class="pmi-events-full-calendar__nav">
			<button type="button" class="pmi-events-full-calendar__nav-btn" data-action="prev" data-year="<?php echo esc_attr( $prev_year ); ?>" data-month="<?php echo esc_attr( $prev_month ); ?>" aria-label="<?php esc_attr_e( 'Mese precedente', 'pmi-events' ); ?>">
				<span aria-hidden="true">&lsaquo;</span>
			</button>
			<button type="button" class="pmi-events-full-calendar__nav-btn" data-action="next" data-year="<?php echo esc_attr( $next_year ); ?>" data-month="<?php echo esc_attr( $next_month ); ?>" aria-label="<?php esc_attr_e( 'Mese successivo', 'pmi-events' ); ?>">
				<span aria-hidden="true">&rsaquo;</span>
			</button>
			<button type="button" class="pmi-events-full-calendar__today-btn" data-action="today" data-year="<?php echo esc_attr( (int) gmdate( 'Y' ) ); ?>" data-month="<?php echo esc_attr( (int) gmdate( 'n' ) ); ?>" <?php disabled( $is_current_month ); ?>>
				<?php esc_html_e( 'Questo mese', 'pmi-events' ); ?>
			</button>
		</div>
		<h2 class="pmi-events-full-calendar__title">
			<?php echo esc_html( PMI_Events_Calendar::format_month_heading( $year, $month ) ); ?>
		</h2>
	</div>

	<div class="pmi-events-full-calendar__weekdays" aria-hidden="true">
		<?php foreach ( $weekdays as $label ) : ?>
			<span><?php echo esc_html( $label ); ?></span>
		<?php endforeach; ?>
	</div>

	<div class="pmi-events-full-calendar__grid" role="grid" aria-label="<?php echo esc_attr( PMI_Events_Calendar::format_month_heading( $year, $month ) ); ?>">
		<?php
		for ( $i = 0; $i < $leading_days; $i++ ) :
			$adj_day = $prev_month_days - $leading_days + $i + 1;
			?>
			<div class="pmi-events-full-calendar__cell pmi-events-full-calendar__cell--adjacent" aria-hidden="true">
				<span class="pmi-events-full-calendar__day-number"><?php echo esc_html( (string) $adj_day ); ?></span>
			</div>
			<?php
		endfor;

		for ( $day = 1; $day <= $days_in_month; $day++ ) :
			$date       = sprintf( '%04d-%02d-%02d', $year, $month, $day );
			$is_today   = ( $date === $today );
			$day_events = isset( $events_map[ $date ] ) ? $events_map[ $date ] : array();
			$visible    = array_slice( $day_events, 0, $event_limit );
			$remaining  = count( $day_events ) - count( $visible );
			$cell_class = 'pmi-events-full-calendar__cell';
			if ( $is_today ) {
				$cell_class .= ' pmi-events-full-calendar__cell--today';
			}
			if ( ! empty( $day_events ) ) {
				$cell_class .= ' pmi-events-full-calendar__cell--has-events';
			}
			?>
			<div
				class="<?php echo esc_attr( $cell_class ); ?>"
				data-date="<?php echo esc_attr( $date ); ?>"
				role="gridcell"
			>
				<span class="pmi-events-full-calendar__day-number">
					<?php if ( $is_today ) : ?>
						<span class="pmi-events-full-calendar__day-badge"><?php echo esc_html( (string) $day ); ?></span>
					<?php else : ?>
						<?php echo esc_html( (string) $day ); ?>
					<?php endif; ?>
				</span>

				<?php if ( ! empty( $visible ) ) : ?>
					<ul class="pmi-events-full-calendar__events">
						<?php foreach ( $visible as $event ) : ?>
							<li class="pmi-events-full-calendar__event" data-date="<?php echo esc_attr( $date ); ?>">
								<span class="pmi-events-full-calendar__event-time"><?php echo esc_html( PMI_Events_Calendar::format_event_time_short( $event ) ); ?></span>
								<span class="pmi-events-full-calendar__event-title">
									<?php echo esc_html( $event['title'] ); ?><?php if ( ! empty( $event['location'] ) ) : ?> <span class="pmi-events-full-calendar__event-location">(<?php echo esc_html( $event['location'] ); ?>)</span><?php endif; ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>

					<?php if ( $remaining > 0 ) : ?>
						<button type="button" class="pmi-events-full-calendar__more" data-date="<?php echo esc_attr( $date ); ?>">
							<?php
							echo esc_html(
								sprintf(
									/* translators: %d: number of additional events. */
									_n( '+%d altro', '+%d altri', $remaining, 'pmi-events' ),
									$remaining
								)
							);
							?>
						</button>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<?php
		endfor;

		for ( $day = 1; $day <= $trailing_days; $day++ ) :
			?>
			<div class="pmi-events-full-calendar__cell pmi-events-full-calendar__cell--adjacent" aria-hidden="true">
				<span class="pmi-events-full-calendar__day-number"><?php echo esc_html( (string) $day ); ?></span>
			</div>
			<?php
		endfor;
		?>
	</div>
</div>

<div class="pmi-events-full-calendar-popup" data-pmi-events-popup hidden>
	<div class="pmi-events-full-calendar-popup__overlay" data-popup-close></div>
	<div class="pmi-events-full-calendar-popup__panel" role="dialog" aria-modal="true">
		<button type="button" class="pmi-events-full-calendar-popup__close" data-popup-close aria-label="<?php esc_attr_e( 'Chiudi', 'pmi-events' ); ?>">&times;</button>
		<h3 class="pmi-events-full-calendar-popup__date" data-popup-date></h3>
		<div class="pmi-events-full-calendar-popup__body" data-popup-body></div>
	</div>
</div>
