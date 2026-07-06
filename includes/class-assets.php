<?php
/**
 * Scripts and styles.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset loader.
 */
class PMI_Events_Assets {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_assets' ) );
	}

	/**
	 * Register front-end assets (loaded on demand).
	 */
	public static function register_assets() {
		$tokens_path = PMI_EVENTS_DIR . 'assets/css/pmi-events-tokens.css';
		$css_path    = PMI_EVENTS_DIR . 'assets/css/pmi-events.css';
		$js_path     = PMI_EVENTS_DIR . 'assets/js/pmi-events-calendar.js';
		$full_css    = PMI_EVENTS_DIR . 'assets/css/pmi-events-calendar-full.css';
		$full_js     = PMI_EVENTS_DIR . 'assets/js/pmi-events-calendar-full.js';

		wp_register_style(
			'pmi-events-tokens',
			PMI_EVENTS_URL . 'assets/css/pmi-events-tokens.css',
			array(),
			file_exists( $tokens_path ) ? (string) filemtime( $tokens_path ) : PMI_EVENTS_VERSION
		);

		wp_register_style(
			'pmi-events',
			PMI_EVENTS_URL . 'assets/css/pmi-events.css',
			array( 'pmi-events-tokens' ),
			file_exists( $css_path ) ? (string) filemtime( $css_path ) : PMI_EVENTS_VERSION
		);

		wp_register_script(
			'pmi-events-calendar',
			PMI_EVENTS_URL . 'assets/js/pmi-events-calendar.js',
			array(),
			file_exists( $js_path ) ? (string) filemtime( $js_path ) : PMI_EVENTS_VERSION,
			true
		);

		wp_register_style(
			'pmi-events-calendar-full',
			PMI_EVENTS_URL . 'assets/css/pmi-events-calendar-full.css',
			array( 'pmi-events-tokens', 'pmi-events' ),
			file_exists( $full_css ) ? (string) filemtime( $full_css ) : PMI_EVENTS_VERSION
		);

		wp_register_script(
			'pmi-events-calendar-full',
			PMI_EVENTS_URL . 'assets/js/pmi-events-calendar-full.js',
			array(),
			file_exists( $full_js ) ? (string) filemtime( $full_js ) : PMI_EVENTS_VERSION,
			true
		);
	}

	/**
	 * Enqueue calendar assets when shortcode is used.
	 */
	public static function enqueue_calendar() {
		wp_enqueue_style( 'pmi-events' );
		wp_enqueue_script( 'pmi-events-calendar' );

		wp_localize_script(
			'pmi-events-calendar',
			'pmiEventsCalendar',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'pmi_events_calendar' ),
				'i18n'    => array(
					'loading' => __( 'Caricamento...', 'pmi-events' ),
					'error'   => __( 'Impossibile caricare il calendario.', 'pmi-events' ),
				),
			)
		);
	}

	/**
	 * Enqueue full month calendar assets.
	 */
	public static function enqueue_calendar_full() {
		wp_enqueue_style( 'pmi-events-calendar-full' );
		wp_enqueue_script( 'pmi-events-calendar-full' );

		wp_localize_script(
			'pmi-events-calendar-full',
			'pmiEventsCalendarFull',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'pmi_events_calendar' ),
				'i18n'    => array(
					'loading' => __( 'Caricamento...', 'pmi-events' ),
					'error'   => __( 'Impossibile caricare il calendario.', 'pmi-events' ),
					'close'   => __( 'Chiudi', 'pmi-events' ),
				),
			)
		);
	}
}
