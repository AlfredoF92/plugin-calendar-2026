<?php
/**
 * Event custom post type.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the pmi_event post type.
 */
class PMI_Events_Post_Type {

	const POST_TYPE = 'pmi_event';

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'init', array( __CLASS__, 'register_post_type' ) );
	}

	/**
	 * Register CPT.
	 */
	public static function register_post_type() {
		$labels = array(
			'name'               => __( 'Eventi', 'pmi-events' ),
			'singular_name'      => __( 'Evento', 'pmi-events' ),
			'add_new'            => __( 'Aggiungi evento', 'pmi-events' ),
			'add_new_item'       => __( 'Aggiungi nuovo evento', 'pmi-events' ),
			'edit_item'          => __( 'Modifica evento', 'pmi-events' ),
			'new_item'           => __( 'Nuovo evento', 'pmi-events' ),
			'view_item'          => __( 'Visualizza evento', 'pmi-events' ),
			'search_items'       => __( 'Cerca eventi', 'pmi-events' ),
			'not_found'          => __( 'Nessun evento trovato', 'pmi-events' ),
			'not_found_in_trash' => __( 'Nessun evento nel cestino', 'pmi-events' ),
			'menu_name'          => __( 'Eventi PMI', 'pmi-events' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => $labels,
				'public'              => true,
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => 'eventi' ),
				'menu_icon'           => 'dashicons-calendar-alt',
				'menu_position'       => 26,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'show_in_rest'        => true,
				'exclude_from_search' => false,
			)
		);
	}
}
