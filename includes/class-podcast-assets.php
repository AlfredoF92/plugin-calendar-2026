<?php
/**
 * Scripts and styles for the podcast feature.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset loader for podcast frontend.
 */
class PMI_Podcast_Assets {

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
		$css_path    = PMI_EVENTS_DIR . 'assets/css/pmi-podcast.css';

		wp_register_style(
			'pmi-events-tokens',
			PMI_EVENTS_URL . 'assets/css/pmi-events-tokens.css',
			array(),
			file_exists( $tokens_path ) ? (string) filemtime( $tokens_path ) : PMI_EVENTS_VERSION
		);

		wp_register_style(
			'pmi-podcast',
			PMI_EVENTS_URL . 'assets/css/pmi-podcast.css',
			array( 'pmi-events-tokens' ),
			file_exists( $css_path ) ? (string) filemtime( $css_path ) : PMI_EVENTS_VERSION
		);
	}

	/**
	 * Enqueue grid assets when the shortcode is used.
	 */
	public static function enqueue_grid() {
		wp_enqueue_style( 'pmi-podcast' );
	}
}
