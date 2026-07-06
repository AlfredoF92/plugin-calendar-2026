<?php
/**
 * Plugin Name:       PMI Events
 * Plugin URI:        https://github.com/pmi/pmi-events
 * Description:       Calendario eventi con shortcode, CPT dedicato e widget interattivo per la homepage.
 * Version:           1.3.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            PMI
 * Text Domain:       pmi-events
 * Domain Path:       /languages
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

define( 'PMI_EVENTS_VERSION', '1.4.0' );
define( 'PMI_EVENTS_FILE', __FILE__ );
define( 'PMI_EVENTS_DIR', plugin_dir_path( __FILE__ ) );
define( 'PMI_EVENTS_URL', plugin_dir_url( __FILE__ ) );

require_once PMI_EVENTS_DIR . 'includes/class-post-type.php';
require_once PMI_EVENTS_DIR . 'includes/class-taxonomy.php';
require_once PMI_EVENTS_DIR . 'includes/class-meta-boxes.php';
require_once PMI_EVENTS_DIR . 'includes/class-admin.php';
require_once PMI_EVENTS_DIR . 'includes/class-demo-import.php';
require_once PMI_EVENTS_DIR . 'includes/class-calendar.php';
require_once PMI_EVENTS_DIR . 'includes/class-shortcodes.php';
require_once PMI_EVENTS_DIR . 'includes/class-assets.php';
require_once PMI_EVENTS_DIR . 'includes/class-elementor.php';

/**
 * Bootstrap plugin components.
 */
function pmi_events_init() {
	PMI_Events_Post_Type::register();
	PMI_Events_Taxonomy::register();
	PMI_Events_Meta_Boxes::register();
	PMI_Events_Admin::register();
	PMI_Events_Calendar::register();
	PMI_Events_Shortcodes::register();
	PMI_Events_Assets::register();
	PMI_Events_Elementor::register();
}
add_action( 'plugins_loaded', 'pmi_events_init' );

/**
 * Flush rewrite rules on activation.
 */
function pmi_events_activate() {
	PMI_Events_Post_Type::register_post_type();
	PMI_Events_Taxonomy::register_taxonomy();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pmi_events_activate' );

/**
 * Flush rewrite rules on deactivation.
 */
function pmi_events_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pmi_events_deactivate' );
