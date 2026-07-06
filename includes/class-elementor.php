<?php
/**
 * Elementor integration.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers PMI Events dynamic tags in Elementor.
 */
class PMI_Events_Elementor {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'plugins_loaded', array( __CLASS__, 'boot' ), 20 );
	}

	/**
	 * Boot Elementor integration when Elementor is available.
	 */
	public static function boot() {
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		require_once PMI_EVENTS_DIR . 'includes/class-formatter.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-datetime.php';

		add_action( 'elementor/dynamic_tags/register', array( __CLASS__, 'register_dynamic_tags' ) );
	}

	/**
	 * Register dynamic tag group and tags.
	 *
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Dynamic tags manager.
	 */
	public static function register_dynamic_tags( $dynamic_tags_manager ) {
		$dynamic_tags_manager->register_group(
			'pmi-events',
			array(
				'title' => __( 'PMI Events', 'pmi-events' ),
			)
		);

		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Datetime() );
	}
}
