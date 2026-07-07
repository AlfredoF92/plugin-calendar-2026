<?php
/**
 * Admin list table, columns and styles for podcast episodes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin UX for podcast episodes.
 */
class PMI_Podcast_Admin {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_filter( 'manage_' . PMI_Podcast_Post_Type::POST_TYPE . '_posts_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_' . PMI_Podcast_Post_Type::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'column_content' ), 10, 2 );
		add_filter( 'manage_edit-' . PMI_Podcast_Post_Type::POST_TYPE . '_sortable_columns', array( __CLASS__, 'sortable_columns' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'admin_query' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'restrict_manage_posts', array( __CLASS__, 'render_import_button' ) );
		add_action( 'admin_post_pmi_podcast_import_demo', array( __CLASS__, 'handle_import_demo' ) );
		add_action( 'admin_notices', array( __CLASS__, 'import_notice' ) );
	}

	/**
	 * Button on podcast list screen to import demo episodes.
	 */
	public static function render_import_button() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || 'edit-pmi_podcast' !== $screen->id || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$url = wp_nonce_url(
			admin_url( 'admin-post.php?action=pmi_podcast_import_demo' ),
			'pmi_podcast_import_demo'
		);
		?>
		<a href="<?php echo esc_url( $url ); ?>" class="button">
			<?php esc_html_e( 'Importa 3 episodi demo', 'pmi-events' ); ?>
		</a>
		<?php
	}

	/**
	 * Handle demo import from wp-admin.
	 */
	public static function handle_import_demo() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'Permessi insufficienti.', 'pmi-events' ) );
		}

		check_admin_referer( 'pmi_podcast_import_demo' );

		require_once PMI_EVENTS_DIR . 'includes/class-podcast-demo-import.php';

		$result = PMI_Podcast_Demo_Import::import_all();

		$redirect = add_query_arg(
			array(
				'post_type'            => PMI_Podcast_Post_Type::POST_TYPE,
				'pmi_podcast_imported' => is_wp_error( $result ) ? 'error' : '1',
			),
			admin_url( 'edit.php' )
		);

		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Show result notice after admin import.
	 */
	public static function import_notice() {
		if ( ! isset( $_GET['pmi_podcast_imported'] ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || PMI_Podcast_Post_Type::POST_TYPE !== $screen->post_type ) {
			return;
		}

		if ( 'error' === $_GET['pmi_podcast_imported'] ) {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'Import episodi demo non riuscito.', 'pmi-events' ) . '</p></div>';
			return;
		}

		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( '3 episodi demo podcast importati con successo.', 'pmi-events' ) . '</p></div>';
	}

	/**
	 * Admin columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public static function columns( $columns ) {
		$new = array();

		if ( isset( $columns['cb'] ) ) {
			$new['cb'] = $columns['cb'];
		}

		$new['title']    = __( 'Episodio', 'pmi-events' );
		$new['episode']  = __( 'N.', 'pmi-events' );
		$new['guests']   = __( 'Ospiti', 'pmi-events' );
		$new['pdu']      = __( 'PDU', 'pmi-events' );
		$new['platforms'] = __( 'Piattaforme', 'pmi-events' );

		if ( isset( $columns['taxonomy-' . PMI_Podcast_Taxonomy::TAXONOMY] ) ) {
			$new['taxonomy-' . PMI_Podcast_Taxonomy::TAXONOMY] = $columns['taxonomy-' . PMI_Podcast_Taxonomy::TAXONOMY];
		}

		if ( isset( $columns['date'] ) ) {
			$new['date'] = $columns['date'];
		}

		return $new;
	}

	/**
	 * Render custom column content.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public static function column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'episode':
				$number = get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_EPISODE_NUMBER, true );
				echo $number ? esc_html( $number ) : '<span class="pmi-events-admin-missing">—</span>';
				break;

			case 'guests':
				$guests = get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_GUESTS, true );
				echo $guests ? esc_html( $guests ) : '<span class="pmi-events-admin-missing">—</span>';
				break;

			case 'pdu':
				$pdu = get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_PDU, true );
				echo $pdu ? esc_html( $pdu ) : '<span class="pmi-events-admin-missing">—</span>';
				break;

			case 'platforms':
				$count = 0;

				foreach ( PMI_Podcast_Meta_Boxes::get_platform_meta_map() as $meta_key ) {
					if ( get_post_meta( $post_id, $meta_key, true ) ) {
						++$count;
					}
				}

				$extra = get_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_EXTRA_LINKS, true );
				if ( is_array( $extra ) ) {
					$count += count( $extra );
				}

				echo $count ? esc_html( (string) $count ) : '<span class="pmi-events-admin-missing">—</span>';
				break;
		}
	}

	/**
	 * Sortable columns.
	 *
	 * @param array $columns Sortable columns.
	 * @return array
	 */
	public static function sortable_columns( $columns ) {
		$columns['episode'] = 'episode';
		return $columns;
	}

	/**
	 * Default admin ordering by episode number (descending, latest first).
	 *
	 * @param WP_Query $query Query instance.
	 */
	public static function admin_query( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( PMI_Podcast_Post_Type::POST_TYPE !== $query->get( 'post_type' ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'episode' === $orderby ) {
			$query->set( 'meta_key', PMI_Podcast_Meta_Boxes::META_EPISODE_NUMBER );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
	 * Admin styles/scripts.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || PMI_Podcast_Post_Type::POST_TYPE !== $screen->post_type ) {
			return;
		}

		$css_path = PMI_EVENTS_DIR . 'assets/css/pmi-podcast-admin.css';

		wp_enqueue_style(
			'pmi-podcast-admin',
			PMI_EVENTS_URL . 'assets/css/pmi-podcast-admin.css',
			array(),
			file_exists( $css_path ) ? (string) filemtime( $css_path ) : PMI_EVENTS_VERSION
		);

		if ( 'post' === $screen->base ) {
			$js_path = PMI_EVENTS_DIR . 'assets/js/pmi-podcast-admin.js';

			wp_enqueue_script(
				'pmi-podcast-admin',
				PMI_EVENTS_URL . 'assets/js/pmi-podcast-admin.js',
				array(),
				file_exists( $js_path ) ? (string) filemtime( $js_path ) : PMI_EVENTS_VERSION,
				true
			);
		}
	}
}
