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
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-base.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-datetime.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-fields.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-image.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-registration-url.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-podcast-base.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-podcast-fields.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-podcast-image.php';
		require_once PMI_EVENTS_DIR . 'includes/elementor/class-dynamic-tag-podcast-links.php';

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
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Title() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Excerpt() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Category() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Location() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Organizer() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Language() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Pdu() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Price_Members() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Price_Guests() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Image() );
		$dynamic_tags_manager->register( new PMI_Events_Elementor_Tag_Registration_Url() );

		$dynamic_tags_manager->register_group(
			'pmi-podcast',
			array(
				'title' => __( 'PMI Podcast', 'pmi-events' ),
			)
		);

		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Title() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Excerpt() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Episode_Number() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Guests() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Interviewers() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Pdu() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Category() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Image() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Apple() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Spotify() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Youtube() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Youtube_Music() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Amazon_Music() );
		$dynamic_tags_manager->register( new PMI_Podcast_Elementor_Tag_Link_Spreaker() );
	}
}
