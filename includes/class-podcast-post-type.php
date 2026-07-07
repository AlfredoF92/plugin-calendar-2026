<?php
/**
 * Podcast custom post type.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the pmi_podcast post type.
 */
class PMI_Podcast_Post_Type {

	const POST_TYPE = 'pmi_podcast';

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
			'name'               => __( 'Podcast', 'pmi-events' ),
			'singular_name'      => __( 'Episodio', 'pmi-events' ),
			'add_new'            => __( 'Aggiungi episodio', 'pmi-events' ),
			'add_new_item'       => __( 'Aggiungi nuovo episodio', 'pmi-events' ),
			'edit_item'          => __( 'Modifica episodio', 'pmi-events' ),
			'new_item'           => __( 'Nuovo episodio', 'pmi-events' ),
			'view_item'          => __( 'Visualizza episodio', 'pmi-events' ),
			'search_items'       => __( 'Cerca episodi', 'pmi-events' ),
			'not_found'          => __( 'Nessun episodio trovato', 'pmi-events' ),
			'not_found_in_trash' => __( 'Nessun episodio nel cestino', 'pmi-events' ),
			'menu_name'          => __( 'Podcast', 'pmi-events' ),
		);

		register_post_type(
			self::POST_TYPE,
			array(
				'labels'              => $labels,
				'public'              => true,
				'has_archive'         => true,
				'rewrite'             => array( 'slug' => 'podcast' ),
				'menu_icon'           => 'dashicons-microphone',
				'menu_position'       => 27,
				'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
				'show_in_rest'        => true,
				'exclude_from_search' => false,
			)
		);
	}
}
