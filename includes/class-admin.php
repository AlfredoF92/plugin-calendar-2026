<?php
/**
 * Admin list table, columns and styles.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin UX for events.
 */
class PMI_Events_Admin {

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_filter( 'manage_' . PMI_Events_Post_Type::POST_TYPE . '_posts_columns', array( __CLASS__, 'columns' ) );
		add_action( 'manage_' . PMI_Events_Post_Type::POST_TYPE . '_posts_custom_column', array( __CLASS__, 'column_content' ), 10, 2 );
		add_filter( 'manage_edit-' . PMI_Events_Post_Type::POST_TYPE . '_sortable_columns', array( __CLASS__, 'sortable_columns' ) );
		add_action( 'pre_get_posts', array( __CLASS__, 'admin_query' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'admin_notices', array( __CLASS__, 'missing_date_notice' ) );
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

		$new['title']       = __( 'Evento', 'pmi-events' );
		$new['event_date']  = __( 'Data', 'pmi-events' );
		$new['event_time']  = __( 'Orario', 'pmi-events' );
		$new['taxonomy-' . PMI_Events_Taxonomy::TAXONOMY] = __( 'Categoria', 'pmi-events' );
		$new['location']     = __( 'Luogo', 'pmi-events' );
		$new['registration'] = __( 'Iscrizione', 'pmi-events' );

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
			case 'event_date':
				$date = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_DATE, true );
				if ( $date ) {
					echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date . ' UTC' ) ) );
				} else {
					echo '<span class="pmi-events-admin-missing">' . esc_html__( '—', 'pmi-events' ) . '</span>';
				}
				break;

			case 'event_time':
				echo esc_html( self::format_time_range( $post_id ) );
				break;

			case 'location':
				$location = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_LOCATION, true );
				echo $location ? esc_html( $location ) : '<span class="pmi-events-admin-missing">—</span>';
				break;

			case 'registration':
				$url = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_REGISTRATION_URL, true );
				if ( $url ) {
					printf(
						'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
						esc_url( $url ),
						esc_html__( 'Link attivo', 'pmi-events' )
					);
				} else {
					echo '<span class="pmi-events-admin-missing">' . esc_html__( 'Nessun link', 'pmi-events' ) . '</span>';
				}
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
		$columns['event_date'] = 'event_date';
		return $columns;
	}

	/**
	 * Default admin ordering by event date.
	 *
	 * @param WP_Query $query Query instance.
	 */
	public static function admin_query( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( PMI_Events_Post_Type::POST_TYPE !== $query->get( 'post_type' ) ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( 'event_date' === $orderby || '' === $orderby || null === $orderby ) {
			$query->set( 'meta_key', PMI_Events_Meta_Boxes::META_START_DATE );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Admin styles.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || PMI_Events_Post_Type::POST_TYPE !== $screen->post_type ) {
			return;
		}

		$css_path = PMI_EVENTS_DIR . 'assets/css/pmi-events-admin.css';

		wp_enqueue_style(
			'pmi-events-admin',
			PMI_EVENTS_URL . 'assets/css/pmi-events-admin.css',
			array(),
			file_exists( $css_path ) ? (string) filemtime( $css_path ) : PMI_EVENTS_VERSION
		);
	}

	/**
	 * Warn when publishing without a start date.
	 */
	public static function missing_date_notice() {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( ! $screen || 'post' !== $screen->base || PMI_Events_Post_Type::POST_TYPE !== $screen->post_type ) {
			return;
		}

		if ( ! isset( $_GET['post'] ) ) {
			return;
		}

		$post_id = (int) $_GET['post'];
		$date    = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_DATE, true );

		if ( empty( $date ) && 'publish' === get_post_status( $post_id ) ) {
			echo '<div class="notice notice-warning"><p>' . esc_html__( 'Questo evento non ha una data di inizio. Il calendario non lo mostrerà correttamente.', 'pmi-events' ) . '</p></div>';
		}
	}

	/**
	 * Format time range for list table.
	 *
	 * @param int $post_id Post ID.
	 * @return string
	 */
	private static function format_time_range( $post_id ) {
		$start = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_TIME, true );
		$end   = get_post_meta( $post_id, PMI_Events_Meta_Boxes::META_END_TIME, true );

		if ( empty( $start ) && empty( $end ) ) {
			return '—';
		}

		$format = get_option( 'time_format' );

		if ( $start && $end ) {
			return sprintf(
				'%s – %s',
				date_i18n( $format, strtotime( '1970-01-01 ' . $start . ' UTC' ) ),
				date_i18n( $format, strtotime( '1970-01-01 ' . $end . ' UTC' ) )
			);
		}

		if ( $start ) {
			return date_i18n( $format, strtotime( '1970-01-01 ' . $start . ' UTC' ) );
		}

		return date_i18n( $format, strtotime( '1970-01-01 ' . $end . ' UTC' ) );
	}
}
