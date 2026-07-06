<?php
/**
 * Demo events import logic.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Imports bundled demo events.
 */
class PMI_Events_Demo_Import {

	const DEMO_META_KEY = '_pmi_event_demo_slug';

	/**
	 * Import or update all demo events.
	 *
	 * @return array|WP_Error
	 */
	public static function import_all() {
		$events = self::get_events_data();
		$report = array(
			'created' => 0,
			'updated' => 0,
			'errors'  => 0,
			'events'  => array(),
		);

		foreach ( $events as $event_data ) {
			$result = self::import_event( $event_data );

			if ( is_wp_error( $result ) ) {
				++$report['errors'];
				continue;
			}

			if ( $result['created'] ) {
				++$report['created'];
			} else {
				++$report['updated'];
			}

			$report['events'][] = $result;
		}

		return $report;
	}

	/**
	 * Demo events definition.
	 *
	 * @return array[]
	 */
	private static function get_events_data() {
		$demo_dir = PMI_EVENTS_DIR . 'assets/demo/';

		return array(
			array(
				'slug'             => 'podcast-episodio-98-pmbok8',
				'title'            => 'Project Management On The Go - Episodio 98: PMBoK8',
				'category'         => 'Podcast',
				'excerpt'          => 'Intervista con Laura Lazzerini e Edoardo Favari, a cura di Valerio Casalini, dedicata al PMBoK8 e alle novità per i project manager.',
				'content'          => '<p>Nuovo episodio di <strong>Project Management On The Go</strong>, il podcast di PMI Northern Italy.</p><p>In questo episodio, <strong>Valerio Casalini</strong> intervista <strong>Laura Lazzerini</strong> e <strong>Edoardo Favari</strong> sul tema del <strong>PMBoK8</strong>, con spunti pratici su come interpretare le novità e applicarle nei contesti professionali.</p><p>Un appuntamento utile per chi vuole restare aggiornato sulle evoluzioni del project management.</p>',
				'start_date'       => '2026-07-08',
				'end_date'         => '2026-07-08',
				'start_time'       => '17:00',
				'end_time'         => '18:00',
				'location'         => 'Online',
				'organizer'        => 'PMI Northern Italy',
				'language'         => 'Italiano',
				'pdu'              => '0.5',
				'price_members'    => 'Gratuito',
				'price_guests'     => 'Gratuito',
				'registration_url' => 'https://www.pmi-nic.org/',
				'image'            => $demo_dir . 'evento-podcast-pmbok8.png',
			),
			array(
				'slug'             => 'webinar-cultura-pm-ambienti-non-tradizionali',
				'title'            => 'Introdurre una cultura di PM in ambienti non tradizionali',
				'category'         => 'Webinar',
				'excerpt'          => 'Webinar con Matteo Mosca, moderato da Davide La Valle, su come diffondere una cultura del project management in contesti non tradizionali.',
				'content'          => '<p>PMI Northern Italy organizza un webinar dedicato a uno dei temi più attuali per le organizzazioni: <strong>come introdurre una cultura di project management in ambienti non tradizionali</strong>.</p><p><strong>Relatore:</strong> Matteo Mosca<br><strong>Moderatore:</strong> Davide La Valle</p><p>Durante la sessione verranno condivisi esempi concreti, ostacoli comuni e strategie per favorire l\'adozione di pratiche PM in contesti ibridi, innovativi o poco strutturati.</p>',
				'start_date'       => '2026-07-09',
				'end_date'         => '2026-07-09',
				'start_time'       => '18:00',
				'end_time'         => '19:00',
				'location'         => 'Online',
				'organizer'        => 'PMI Northern Italy',
				'language'         => 'Italiano',
				'pdu'              => '1.0',
				'price_members'    => 'Gratuito',
				'price_guests'     => 'Gratuito',
				'registration_url' => 'https://www.pmi-nic.org/',
				'image'            => $demo_dir . 'evento-webinar-pm-cultura.png',
			),
			array(
				'slug'             => 'studio-ai-maturity-it-project-success',
				'title'            => 'AI Maturity and IT Project Success',
				'category'         => 'Studio',
				'excerpt'          => 'Come la maturità AI influenza il successo dei progetti IT in Europa. Partecipa allo studio e condividi la tua prospettiva.',
				'content'          => '<p><strong>EU Project Management Study</strong></p><p>PMI Northern Italy e la <strong>AI Community of Practice</strong> promuovono uno studio europeo sul rapporto tra <strong>maturità AI</strong> e <strong>successo dei progetti IT</strong>.</p><p>Lo studio analizza come le organizzazioni adottano l\'intelligenza artificiale e quali impatti questo ha su tempi, qualità e risultati dei progetti.</p><p>Compila il sondaggio e contribuisci con la tua esperienza.</p>',
				'start_date'       => '2026-07-10',
				'end_date'         => '2026-07-10',
				'start_time'       => '09:00',
				'end_time'         => '18:00',
				'location'         => 'Online',
				'organizer'        => 'PMI Northern Italy - AI Community of Practice',
				'language'         => 'Italiano',
				'pdu'              => '',
				'price_members'    => 'Gratuito',
				'price_guests'     => 'Gratuito',
				'registration_url' => 'https://www.pmi-nic.org/',
				'image'            => $demo_dir . 'evento-ai-maturity-survey.png',
			),
		);
	}

	/**
	 * Import a single event.
	 *
	 * @param array $data Event data.
	 * @return array|WP_Error
	 */
	private static function import_event( $data ) {
		$existing = self::find_existing_event( $data['slug'] );
		$created  = false;

		$postarr = array(
			'post_type'    => PMI_Events_Post_Type::POST_TYPE,
			'post_title'   => $data['title'],
			'post_excerpt' => $data['excerpt'],
			'post_content' => $data['content'],
			'post_status'  => 'publish',
		);

		if ( $existing ) {
			$postarr['ID'] = $existing;
			$post_id       = wp_update_post( $postarr, true );
		} else {
			$post_id = wp_insert_post( $postarr, true );
			$created = true;
		}

		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, self::DEMO_META_KEY, $data['slug'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_DATE, $data['start_date'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_END_DATE, $data['end_date'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_START_TIME, $data['start_time'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_END_TIME, $data['end_time'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_LOCATION, $data['location'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_ORGANIZER, $data['organizer'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_LANGUAGE, $data['language'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_PDU, $data['pdu'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_PRICE_MEMBERS, $data['price_members'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_PRICE_GUESTS, $data['price_guests'] );
		update_post_meta( $post_id, PMI_Events_Meta_Boxes::META_REGISTRATION_URL, $data['registration_url'] );

		self::assign_category( $post_id, $data['category'] );
		$has_thumbnail = self::assign_featured_image( $post_id, $data['image'], $data['title'] );

		$event_data = PMI_Events_Meta_Boxes::get_event_data( $post_id );

		return array(
			'created'        => $created,
			'id'             => $post_id,
			'title'          => $event_data['title'],
			'start_date'     => $event_data['start_date'],
			'start_time'     => $event_data['start_time'],
			'end_time'       => $event_data['end_time'],
			'location'       => $event_data['location'],
			'category'       => $event_data['category'],
			'has_thumbnail'  => $has_thumbnail,
		);
	}

	/**
	 * Find existing demo event by slug meta.
	 *
	 * @param string $slug Demo slug.
	 * @return int
	 */
	private static function find_existing_event( $slug ) {
		$posts = get_posts(
			array(
				'post_type'      => PMI_Events_Post_Type::POST_TYPE,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_key'       => self::DEMO_META_KEY,
				'meta_value'     => $slug,
			)
		);

		return ! empty( $posts ) ? (int) $posts[0] : 0;
	}

	/**
	 * Assign taxonomy term.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $category Category name.
	 */
	private static function assign_category( $post_id, $category ) {
		$term = term_exists( $category, PMI_Events_Taxonomy::TAXONOMY );

		if ( ! $term ) {
			$term = wp_insert_term( $category, PMI_Events_Taxonomy::TAXONOMY );
		}

		if ( is_wp_error( $term ) ) {
			return;
		}

		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		wp_set_object_terms( $post_id, array( $term_id ), PMI_Events_Taxonomy::TAXONOMY );
	}

	/**
	 * Attach featured image from local file.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $file_path Local image path.
	 * @param string $title Attachment title.
	 * @return bool
	 */
	private static function assign_featured_image( $post_id, $file_path, $title ) {
		if ( ! file_exists( $file_path ) ) {
			return false;
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$filename   = basename( $file_path );
		$upload_dir = wp_upload_dir();

		if ( ! empty( $upload_dir['error'] ) ) {
			return false;
		}

		$destination = trailingslashit( $upload_dir['path'] ) . $filename;

		if ( ! copy( $file_path, $destination ) ) {
			return false;
		}

		$filetype = wp_check_filetype( $filename, null );

		$attachment = array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_text_field( $title ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $destination, $post_id );

		if ( is_wp_error( $attach_id ) ) {
			return false;
		}

		$attach_data = wp_generate_attachment_metadata( $attach_id, $destination );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $post_id, $attach_id );

		return true;
	}
}
