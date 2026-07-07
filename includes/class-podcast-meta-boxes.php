<?php
/**
 * Podcast meta boxes.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Admin meta fields for podcast episodes.
 */
class PMI_Podcast_Meta_Boxes {

	const META_EPISODE_NUMBER   = '_pmi_podcast_episode_number';
	const META_GUESTS           = '_pmi_podcast_guests';
	const META_INTERVIEWERS     = '_pmi_podcast_interviewers';
	const META_PDU              = '_pmi_podcast_pdu';
	const META_YOUTUBE_VIDEO    = '_pmi_podcast_youtube_video';
	const META_LINK_APPLE       = '_pmi_podcast_link_apple';
	const META_LINK_SPOTIFY     = '_pmi_podcast_link_spotify';
	const META_LINK_YOUTUBE     = '_pmi_podcast_link_youtube';
	const META_LINK_YOUTUBE_MUSIC = '_pmi_podcast_link_youtube_music';
	const META_LINK_AMAZON_MUSIC  = '_pmi_podcast_link_amazon_music';
	const META_LINK_SPREAKER    = '_pmi_podcast_link_spreaker';
	const META_EXTRA_LINKS      = '_pmi_podcast_extra_links';

	/**
	 * Map of platform key => meta constant, matching PMI_Podcast_Icons::get_platforms().
	 *
	 * @return array<string,string>
	 */
	public static function get_platform_meta_map() {
		return array(
			'apple'         => self::META_LINK_APPLE,
			'spotify'       => self::META_LINK_SPOTIFY,
			'youtube'       => self::META_LINK_YOUTUBE,
			'youtube_music' => self::META_LINK_YOUTUBE_MUSIC,
			'amazon_music'  => self::META_LINK_AMAZON_MUSIC,
			'spreaker'      => self::META_LINK_SPREAKER,
		);
	}

	/**
	 * Register hooks.
	 */
	public static function register() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );
		add_action( 'save_post_' . PMI_Podcast_Post_Type::POST_TYPE, array( __CLASS__, 'save' ), 10, 2 );
	}

	/**
	 * Add meta boxes.
	 */
	public static function add_meta_boxes() {
		add_meta_box(
			'pmi_podcast_information',
			__( 'Informazioni episodio', 'pmi-events' ),
			array( __CLASS__, 'render_information' ),
			PMI_Podcast_Post_Type::POST_TYPE,
			'normal',
			'high'
		);

		add_meta_box(
			'pmi_podcast_links',
			__( 'Link piattaforme di ascolto', 'pmi-events' ),
			array( __CLASS__, 'render_links' ),
			PMI_Podcast_Post_Type::POST_TYPE,
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
				<label for="pmi_podcast_episode_number"><strong><?php esc_html_e( 'Numero episodio', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_podcast_episode_number" name="pmi_podcast_episode_number" class="small-text" value="<?php echo esc_attr( $fields['episode_number'] ); ?>" placeholder="<?php esc_attr_e( 'Es. 99', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_podcast_pdu"><strong><?php esc_html_e( 'PDU maturabili', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_podcast_pdu" name="pmi_podcast_pdu" class="regular-text" value="<?php echo esc_attr( $fields['pdu'] ); ?>" placeholder="<?php esc_attr_e( 'Es. 0,5 PDU – Power Skills', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_podcast_guests"><strong><?php esc_html_e( 'Ospiti', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_podcast_guests" name="pmi_podcast_guests" class="large-text" value="<?php echo esc_attr( $fields['guests'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Elena Venuti, Gianmaria Borgonovo', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_podcast_interviewers"><strong><?php esc_html_e( 'Intervista a cura di', 'pmi-events' ); ?></strong></label>
				<input type="text" id="pmi_podcast_interviewers" name="pmi_podcast_interviewers" class="large-text" value="<?php echo esc_attr( $fields['interviewers'] ); ?>" placeholder="<?php esc_attr_e( 'Es. Valerio Casalini, Michela Lorenzi', 'pmi-events' ); ?>">
			</p>
			<p class="pmi-events-meta-row">
				<label for="pmi_podcast_youtube_video"><strong><?php esc_html_e( 'Video YouTube', 'pmi-events' ); ?></strong></label>
				<input type="url" id="pmi_podcast_youtube_video" name="pmi_podcast_youtube_video" class="large-text code" value="<?php echo esc_attr( $fields['youtube_video'] ); ?>" placeholder="https://www.youtube.com/watch?v=...">
				<span class="description"><?php esc_html_e( 'Link del video da mostrare nella pagina episodio (embed). Diverso dal link piattaforma YouTube per l\'ascolto.', 'pmi-events' ); ?></span>
			</p>
		</div>
		<?php
	}

	/**
	 * Links meta box: fixed platforms + repeatable extra links.
	 *
	 * @param WP_Post $post Current post.
	 */
	public static function render_links( $post ) {
		self::nonce_field();

		$fields    = self::get_fields( $post->ID );
		$platforms = PMI_Podcast_Icons::get_platforms();
		$meta_map  = self::get_platform_meta_map();
		?>
		<div class="pmi-events-meta-grid">
			<?php foreach ( $platforms as $key => $platform ) : ?>
				<p class="pmi-events-meta-row">
					<label for="pmi_podcast_link_<?php echo esc_attr( $key ); ?>">
						<span class="pmi-podcast-admin-icon" style="--pmi-podcast-icon-color:<?php echo esc_attr( $platform['color'] ); ?>"><?php echo PMI_Podcast_Icons::get_icon_svg( $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<strong><?php echo esc_html( $platform['label'] ); ?></strong>
					</label>
					<input type="url" id="pmi_podcast_link_<?php echo esc_attr( $key ); ?>" name="pmi_podcast_link_<?php echo esc_attr( $key ); ?>" class="large-text code" value="<?php echo esc_attr( $fields['links'][ $key ] ); ?>" placeholder="https://">
				</p>
			<?php endforeach; ?>
		</div>

		<hr>

		<h4><?php esc_html_e( 'Altri link', 'pmi-events' ); ?></h4>
		<p class="description"><?php esc_html_e( 'Aggiungi eventuali altre piattaforme o link non presenti sopra.', 'pmi-events' ); ?></p>

		<div id="pmi-podcast-extra-links" class="pmi-podcast-extra-links">
			<?php foreach ( $fields['extra_links'] as $index => $link ) : ?>
				<?php self::render_extra_link_row( $index, $link['label'], $link['url'] ); ?>
			<?php endforeach; ?>
		</div>

		<p>
			<button type="button" class="button" id="pmi-podcast-add-link">
				<?php esc_html_e( '+ Aggiungi link', 'pmi-events' ); ?>
			</button>
		</p>

		<script type="text/template" id="pmi-podcast-extra-link-template">
			<?php self::render_extra_link_row( '__INDEX__', '', '' ); ?>
		</script>
		<?php
	}

	/**
	 * Render a single "extra link" repeater row.
	 *
	 * @param int|string $index Row index (or placeholder token).
	 * @param string     $label Link label.
	 * @param string     $url   Link URL.
	 */
	private static function render_extra_link_row( $index, $label, $url ) {
		?>
		<div class="pmi-podcast-extra-link-row">
			<input type="text" name="pmi_podcast_extra_links[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $label ); ?>" placeholder="<?php esc_attr_e( 'Nome piattaforma', 'pmi-events' ); ?>" class="regular-text">
			<input type="url" name="pmi_podcast_extra_links[<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $url ); ?>" placeholder="https://" class="large-text code">
			<button type="button" class="button-link pmi-podcast-remove-link" aria-label="<?php esc_attr_e( 'Rimuovi link', 'pmi-events' ); ?>">&times;</button>
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

		wp_nonce_field( 'pmi_podcast_save_meta', 'pmi_podcast_meta_nonce' );
		$printed = true;
	}

	/**
	 * Get stored field values.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	private static function get_fields( $post_id ) {
		$links = array();

		foreach ( self::get_platform_meta_map() as $key => $meta_key ) {
			$links[ $key ] = get_post_meta( $post_id, $meta_key, true );
		}

		$extra_links = get_post_meta( $post_id, self::META_EXTRA_LINKS, true );

		if ( ! is_array( $extra_links ) ) {
			$extra_links = array();
		}

		return array(
			'episode_number' => get_post_meta( $post_id, self::META_EPISODE_NUMBER, true ),
			'guests'         => get_post_meta( $post_id, self::META_GUESTS, true ),
			'interviewers'   => get_post_meta( $post_id, self::META_INTERVIEWERS, true ),
			'pdu'            => get_post_meta( $post_id, self::META_PDU, true ),
			'youtube_video'  => get_post_meta( $post_id, self::META_YOUTUBE_VIDEO, true ),
			'links'          => $links,
			'extra_links'    => $extra_links,
		);
	}

	/**
	 * Save meta fields.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public static function save( $post_id, $post ) {
		if ( ! isset( $_POST['pmi_podcast_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pmi_podcast_meta_nonce'] ) ), 'pmi_podcast_save_meta' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$episode_number = isset( $_POST['pmi_podcast_episode_number'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_podcast_episode_number'] ) ) : '';
		$guests         = isset( $_POST['pmi_podcast_guests'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_podcast_guests'] ) ) : '';
		$interviewers   = isset( $_POST['pmi_podcast_interviewers'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_podcast_interviewers'] ) ) : '';
		$pdu            = isset( $_POST['pmi_podcast_pdu'] ) ? sanitize_text_field( wp_unslash( $_POST['pmi_podcast_pdu'] ) ) : '';
		$youtube_video  = isset( $_POST['pmi_podcast_youtube_video'] ) ? esc_url_raw( wp_unslash( $_POST['pmi_podcast_youtube_video'] ) ) : '';

		update_post_meta( $post_id, self::META_EPISODE_NUMBER, $episode_number );
		update_post_meta( $post_id, self::META_GUESTS, $guests );
		update_post_meta( $post_id, self::META_INTERVIEWERS, $interviewers );
		update_post_meta( $post_id, self::META_PDU, $pdu );
		update_post_meta( $post_id, self::META_YOUTUBE_VIDEO, $youtube_video );

		foreach ( self::get_platform_meta_map() as $key => $meta_key ) {
			$field = 'pmi_podcast_link_' . $key;
			$value = isset( $_POST[ $field ] ) ? esc_url_raw( wp_unslash( $_POST[ $field ] ) ) : '';
			update_post_meta( $post_id, $meta_key, $value );
		}

		$extra_links = array();

		if ( isset( $_POST['pmi_podcast_extra_links'] ) && is_array( $_POST['pmi_podcast_extra_links'] ) ) {
			foreach ( wp_unslash( $_POST['pmi_podcast_extra_links'] ) as $row ) {
				$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
				$url   = isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '';

				if ( '' === $label && '' === $url ) {
					continue;
				}

				$extra_links[] = array(
					'label' => $label,
					'url'   => $url,
				);
			}
		}

		update_post_meta( $post_id, self::META_EXTRA_LINKS, $extra_links );
	}

	/**
	 * Get all meta for a podcast episode, ready for display/dynamic tags.
	 *
	 * @param int $post_id Post ID.
	 * @return array
	 */
	public static function get_podcast_data( $post_id ) {
		$categories = wp_get_post_terms( $post_id, PMI_Podcast_Taxonomy::TAXONOMY, array( 'fields' => 'names' ) );
		$thumbnail  = get_the_post_thumbnail_url( $post_id, 'large' );
		$fields     = self::get_fields( $post_id );

		return array_merge(
			$fields,
			array(
				'id'         => $post_id,
				'title'      => get_the_title( $post_id ),
				'permalink'  => get_permalink( $post_id ),
				'excerpt'    => get_the_excerpt( $post_id ),
				'content'    => get_post_field( 'post_content', $post_id ),
				'thumbnail'  => $thumbnail ? $thumbnail : '',
				'categories' => is_array( $categories ) ? $categories : array(),
				'category'   => ( is_array( $categories ) && ! empty( $categories ) ) ? $categories[0] : '',
				'date'       => get_the_date( '', $post_id ),
				'date_iso'   => get_the_date( 'c', $post_id ),
			)
		);
	}
}
