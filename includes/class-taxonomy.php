<?php
/**
 * Event category taxonomy.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers pmi_event_category taxonomy.
 */
class PMI_Events_Taxonomy {

	const TAXONOMY = 'pmi_event_category';

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'init', array( __CLASS__, 'register_taxonomy' ) );
	}

	/**
	 * Register taxonomy.
	 */
	public static function register_taxonomy() {
		$labels = array(
			'name'              => __( 'Categorie evento', 'pmi-events' ),
			'singular_name'     => __( 'Categoria evento', 'pmi-events' ),
			'search_items'      => __( 'Cerca categorie', 'pmi-events' ),
			'all_items'         => __( 'Tutte le categorie', 'pmi-events' ),
			'parent_item'       => __( 'Categoria padre', 'pmi-events' ),
			'parent_item_colon' => __( 'Categoria padre:', 'pmi-events' ),
			'edit_item'         => __( 'Modifica categoria', 'pmi-events' ),
			'update_item'       => __( 'Aggiorna categoria', 'pmi-events' ),
			'add_new_item'      => __( 'Aggiungi categoria', 'pmi-events' ),
			'new_item_name'     => __( 'Nuova categoria', 'pmi-events' ),
			'menu_name'         => __( 'Categorie', 'pmi-events' ),
		);

		register_taxonomy(
			self::TAXONOMY,
			PMI_Events_Post_Type::POST_TYPE,
			array(
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => false,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'categoria-evento' ),
			)
		);
	}
}
