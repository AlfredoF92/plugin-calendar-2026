<?php
/**
 * Event meta boxes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin meta fields for events.
 */
class PMI_Events_Meta_Boxes {

	const META_START_DATE        = '_pmi_event_start_date';
	const META_END_DATE          = '_pmi_event_end_date';
	const META_START_TIME        = '_pmi_event_start_time';
	const META_END_TIME          = '_pmi_event_end_time';
	const META_LOCATION          = '_pmi_event_location';
	const META_ORGANIZER         = '_pmi_event_organizer';
	const META_LANGUAGE          = '_pmi_event_language';
	const META_PDU               = '_pmi_event_pdu';
	const META_PRICE_MEMBERS     = '_pmi_event_price_members';
	const META_PRICE_GUESTS      = '_pmi_event_price_guests';
	const META_REGISTRATION_URL  = '_pmi_event_registration_url';

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . PMI_Events_Post_Type::POST_TYPE, array( __CLASS__, 'save' ), 10, 2 );
	}

	/**
	 * Add meta boxes.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'pmi_event_information',
			__( 'Informazioni evento', 'pmi-events' ),
			array( __CLASS__, 'render_information' ),
			PMI_Events_Post_Type::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'pmi_event_pricing',
			__( 'Prezzi', 'pmi-events' ),
			array( __CLASS__, 'render_pricing' ),
			PMI_Events_Post_Type::POST_TYPE,
			'normal',
			'default'
		);

		add_meta_box(
			'pmi_event_location_registration',
			__( 'Luogo e registrazione', 'pmi-events' ),
			array( __CLASS__, 'render_location_registration' ),
			PMI_Events_Post_Type::POST_TYPE,
			'normal',
			'default'
		);
	}

	/**
	 * Information meta box.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_information( $post ) {
		self::nonce_field();

		$fields = self::get_fields( $post->ID );
		?>
		<div class="pmi-events-meta-grid">
			<p class="pmi-events-meta-row">
				<label for="pmi_event_start_date"><strong><?php esc_html_e( 'Data inizio', 'pmi-events' ); ?></strong></label>
				<input type="date" id="pmi_event_start_date" name="pmi_event_start_date" value="<?php echo esc_attr( $fields['start_date'] ); ?>" required>
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_end_date"><strong><?php esc_html_e( 'Data fine', 'pmi-events' ); ?></strong></label>
				<input type="date" id="pmi_event_end_date" name="pmi_event_end_date" value="<?php echo esc_attr( $fields['end_date'] ); ?>">
				<span class="description"><?php esc_html_e( 'Lascia vuoto se l\'evento dura un solo giorno.', 'pmi-events' ); ?></span>
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_start_time"><strong><?php esc_html_e( 'Ora inizio', 'pmi-events' ); ?></strong></label>
				<input type="time" id="pmi_event_start_time" name="pmi_event_start_time" value="<?php echo esc_attr( $fields['start_time'] ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_end_time"><strong><?php esc_html_e( 'Ora fine', 'pmi-events' ); ?></strong></label>
				<input type="time" id="pmi_event_end_time" name="pmi_event_end_time" value="<?php echo esc_attr( $fields['end_time'] ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_language"><strong><?php esc_html_e( 'Lingua', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_language" name="pmi_event_language" class="regular-text" value="<?php echo esc_attr( $fields['language'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Italiano', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_pdu"><strong><?php esc_html_e( 'N. PDU', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_pdu" name="pmi_event_pdu" class="small-text" value="<?php echo esc_attr( $fields['pdu'] ); ?>" placeholder="<?php esc_attr_e( 'Es. 1.0', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_organizer"><strong><?php esc_html_e( 'Organizzatore', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_organizer" name="pmi_event_organizer" class="regular-text" value="<?php echo esc_attr( $fields['organizer'] ); ?>">
			</p>
		</div>
		<?php
	}

	/**
	 * Pricing meta box.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_pricing( $post ) {
		self::nonce_field();

		$fields = self::get_fields( $post->ID );
		?>
		<div class="pmi-events-meta-grid">
			<p class="pmi-events-meta-row">
				<label for="pmi_event_price_members"><strong><?php esc_html_e( 'Soci', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_price_members" name="pmi_event_price_members" class="regular-text" value="<?php echo esc_attr( $fields['price_members'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Gratuito', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_price_guests"><strong><?php esc_html_e( 'Non soci e ospiti', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_price_guests" name="pmi_event_price_guests" class="regular-text" value="<?php echo esc_attr( $fields['price_guests'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Gratuito', 'pmi-events' ); ?>">
			</p>
		</div>
		<?php
	}

	/**
	 * Location and registration meta box.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_location_registration( $post ) {
		self::nonce_field();

		$fields = self::get_fields( $post->ID );
		?>
		<div class="pmi-events-meta-grid">
			<p class="pmi-events-meta-row">
				<label for="pmi_event_location"><strong><?php esc_html_e( 'Luogo', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_event_location" name="pmi_event_location" class="large-text" value="<?php echo esc_attr( $fields['location'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Porto Business School', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_event_registration_url"><strong><?php esc_html_e( 'URL registrazione', 'pmi-events' ); ?></strong></label>
				<input type="url" id="pmi_event_registration_url" name="pmi_event_registration_url" class="large-text code" value="<?php echo esc_attr( $fields['registration_url'] ); ?>" placeholder="https://">
				<span class="description"><?php esc_html_e( 'Link per il pulsante di iscrizione nella pagina evento.', 'pmi-events' ); ?></span>
			</p>
		</div>
		<?php
	}

	/**
	 * Output nonce once per request.
	 */
	private static function nonce_field() {
		static $printed = false;

		if ( $printed ) {
			return;
		}

		wp_nonce_field( 'pmi_event_save_meta', 'pmi_event_meta_nonce' );
		$printed = true;
	}

	/**
	 * Get stored field values.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	private static function get_fields( $post_id ) {
		return array(
			'start_date'       => get_post_meta( $post_id, self::META_START_DATE, true ),
			'end_date'         => get_post_meta( $post_id, self::META_END_DATE, true ),
			'start_time'       => get_post_meta( $post_id, self::META_START_TIME, true ),
			'end_time'         => get_post_meta( $post_id, self::META_END_TIME, true ),
			'location'         => get_post_meta( $post_id, self::META_LOCATION, true ),
			'organizer'        => get_post_meta( $post_id, self::META_ORGANIZER, true ),
			'language'         => get_post_meta( $post_id, self::META_LANGUAGE, true ),
			'pdu'              => get_post_meta( $post_id, self::META_PDU, true ),
			'price_members'    => get_post_meta( $post_id, self::META_PRICE_MEMBERS, true ),
			'price_guests'     => get_post_meta( $post_id, self::META_PRICE_GUESTS, true ),
			'registration_url' => get_post_meta( $post_id, self::META_REGISTRATION_URL, true ),
		);
	}

	/**
	 * Save meta fields.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function save( $post_id, $post ) {
		if ( ! isset( $_POST['pmi_event_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pmi_event_meta_nonce'] ) ), 'pmi_event_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$start_date = isset( $_POST['pmi_event_start_date'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_start_date'] ) ) : '';
		$end_date   = isset( $_POST['pmi_event_end_date'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_end_date'] ) ) : '';
		$start_time = isset( $_POST['pmi_event_start_time'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_start_time'] ) ) : '';
		$end_time   = isset( $_POST['pmi_event_end_time'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_end_time'] ) ) : '';
		$location   = isset( $_POST['pmi_event_location'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_location'] ) ) : '';
		$organizer  = isset( $_POST['pmi_event_organizer'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_organizer'] ) ) : '';
		$language   = isset( $_POST['pmi_event_language'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_language'] ) ) : '';
		$pdu        = isset( $_POST['pmi_event_pdu'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_pdu'] ) ) : '';
		$price_members = isset( $_POST['pmi_event_price_members'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_price_members'] ) ) : '';
		$price_guests  = isset( $_POST['pmi_event_price_guests'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_event_price_guests'] ) ) : '';
		$registration_url = isset( $_POST['pmi_event_registration_url'] ) ? esc_url_raw( wp_unslash( $_POST['pmi_event_registration_url'] ) ) : '';

		if ( empty( $end_date ) ) {
			$end_date = $start_date;
		}

		update_post_meta( $post_id, self::META_START_DATE, $start_date );
		update_post_meta( $post_id, self::META_END_DATE, $end_date );
		update_post_meta( $post_id, self::META_START_TIME, $start_time );
		update_post_meta( $post_id, self::META_END_TIME, $end_time );
		update_post_meta( $post_id, self::META_LOCATION, $location );
		update_post_meta( $post_id, self::META_ORGANIZER, $organizer );
		update_post_meta( $post_id, self::META_LANGUAGE, $language );
		update_post_meta( $post_id, self::META_PDU, $pdu );
		update_post_meta( $post_id, self::META_PRICE_MEMBERS, $price_members );
		update_post_meta( $post_id, self::META_PRICE_GUESTS, $price_guests );
		update_post_meta( $post_id, self::META_REGISTRATION_URL, $registration_url );
	}

	/**
	 * Get all meta for an event post.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	public static function get_event_data( $post_id ) {
		$categories = wp_get_post_terms( $post_id, PMI_Events_Taxonomy::TAXONOMY, array( 'fields' => 'names' ) );
		$thumbnail  = get_the_post_thumbnail_url( $post_id, 'large' );

		return array(
			'id'               => $post_id,
			'title'            => get_the_title( $post_id ),
			'permalink'        => get_permalink( $post_id ),
			'excerpt'          => get_the_excerpt( $post_id ),
			'thumbnail'        => $thumbnail ? $thumbnail : '',
			'categories'       => is_array( $categories ) ? $categories : array(),
			'category'         => ( is_array( $categories ) && ! empty( $categories ) ) ? $categories[0] : '',
			'start_date'       => get_post_meta( $post_id, self::META_START_DATE, true ),
			'end_date'         => get_post_meta( $post_id, self::META_END_DATE, true ),
			'start_time'       => get_post_meta( $post_id, self::META_START_TIME, true ),
			'end_time'         => get_post_meta( $post_id, self::META_END_TIME, true ),
			'location'         => get_post_meta( $post_id, self::META_LOCATION, true ),
			'organizer'        => get_post_meta( $post_id, self::META_ORGANIZER, true ),
			'language'         => get_post_meta( $post_id, self::META_LANGUAGE, true ),
			'pdu'              => get_post_meta( $post_id, self::META_PDU, true ),
			'price_members'    => get_post_meta( $post_id, self::META_PRICE_MEMBERS, true ),
			'price_guests'     => get_post_meta( $post_id, self::META_PRICE_GUESTS, true ),
			'registration_url' => get_post_meta( $post_id, self::META_REGISTRATION_URL, true ),
		);
	}
}
