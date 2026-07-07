<?php
/**
 * Demo podcast episodes import logic.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Imports bundled/random demo podcast episodes.
 */
class PMI_Podcast_Demo_Import {

	const DEMO_META_KEY = '_pmi_podcast_demo_slug';

	/**
	 * Import or update 3 demo podcast episodes with randomized data.
	 *
	 * @return array|WP_Error
	 */
	public static function import_all() {
		$episodes = self::generate_episodes_data();
		$report   = array(
			'created'  => 0,
			'updated'  => 0,
			'errors'   => 0,
			'episodes' => array(),
		);

		foreach ( $episodes as $episode_data ) {
			$result = self::import_episode( $episode_data );

			if ( is_wp_error( $result ) ) {
				++$report['errors'];
				continue;
			}

			if ( $result['created'] ) {
				++$report['created'];
			} else {
				++$report['updated'];
			}

			$report['episodes'][] = $result;
		}

		return $report;
	}

	/**
	 * Build 3 episode definitions: one fixed (ep. 99) + two randomized.
	 *
	 * @return array[]
	 */
	private static function generate_episodes_data() {
		$cover = PMI_EVENTS_DIR . 'assets/demo/podcast-cover-episodio.png';

		$episodes = array(
			self::get_episode_99_data( $cover ),
			self::build_random_episode( 98, $cover ),
			self::build_random_episode( 97, $cover ),
		);

		return $episodes;
	}

	/**
	 * Fixed demo episode based on the provided cover reference.
	 *
	 * @param string $cover Cover image path.
	 * @return array
	 */
	private static function get_episode_99_data( $cover ) {
		return array(
			'slug'             => 'podcast-episodio-99-forum-2026',
			'episode_number'   => '99',
			'title'            => 'Episodio 99: Verso il futuro con il PMI Italy Forum 2026',
			'category'         => 'Project Management On The Go',
			'excerpt'          => 'Nel nuovo episodio di Project Management On The Go parliamo del PMI Italy Forum 2026 e del tema "Steering the future", con Elena Venuti e Gianmaria Borgonovo.',
			'content'          => '<p>Nel nuovo episodio di <strong>Project Management On The Go</strong>, il podcast di PMI Northern Italy, parliamo del <strong>PMI Italy Forum 2026</strong> e del suo tema <em>Steering the future</em>.</p><p>Con <strong>Elena Venuti</strong> e <strong>Gianmaria Borgonovo</strong> esploriamo le macro-tendenze che stanno ridefinendo il project management e il ruolo dei professionisti PMI nel contesto attuale.</p><p>Intervista a cura di <strong>Michela Lorenzi</strong> e <strong>Valerio Casalini</strong>.</p><p>Ascoltando l\'episodio puoi maturare <strong>0,5 PDU</strong> nella categoria <strong>Power Skills</strong>, registrabili nella sezione Online and Digital Media del portale PMI.</p>',
			'guests'           => 'Elena Venuti, Gianmaria Borgonovo',
			'interviewers'     => 'Michela Lorenzi, Valerio Casalini',
			'pdu'              => '0,5 PDU – Power Skills',
			'links'            => self::random_platform_links( '99' ),
			'extra_links'      => array(
				array(
					'label' => 'Sito PMI Northern Italy',
					'url'   => 'https://www.pmi-nic.org/',
				),
			),
			'image'            => $cover,
		);
	}

	/**
	 * Build a randomized episode definition.
	 *
	 * @param int    $number Episode number.
	 * @param string $cover  Cover image path.
	 * @return array
	 */
	private static function build_random_episode( $number, $cover ) {
		$topics = array(
			array(
				'title'   => 'PMBoK8: cosa cambia per i project manager',
				'excerpt' => 'Un confronto sulle novità del PMBoK8 e su come interpretarle nei contesti professionali quotidiani.',
				'guests'  => array( 'Laura Lazzerini', 'Edoardo Favari' ),
			),
			array(
				'title'   => 'Leadership e team distribuiti nel project management',
				'excerpt' => 'Strategie pratiche per guidare team ibridi e remoti con efficacia, fiducia e comunicazione chiara.',
				'guests'  => array( 'Francesca Bianchi', 'Marco Rinaldi' ),
			),
			array(
				'title'   => 'AI e project success: prospettive dal campo',
				'excerpt' => 'Come l\'intelligenza artificiale sta influenzando pianificazione, decisioni e successo dei progetti IT.',
				'guests'  => array( 'Sara Colombo', 'Andrea Moretti' ),
			),
			array(
				'title'   => 'Cultura PM in organizzazioni non tradizionali',
				'excerpt' => 'Esperienze e best practice per diffondere una cultura del project management in contesti innovativi.',
				'guests'  => array( 'Giulia Ferretti', 'Paolo Santini' ),
			),
		);

		$interviewers_pool = array(
			'Valerio Casalini',
			'Michela Lorenzi',
			'Davide La Valle',
			'Valerio Casalini, Michela Lorenzi',
			'Davide La Valle, Valerio Casalini',
		);

		$pdu_pool = array(
			'0,5 PDU – Power Skills',
			'0,5 PDU – Ways of Working',
			'1,0 PDU – Business Acumen',
			'1,0 PDU – Power Skills',
		);

		$topic         = $topics[ wp_rand( 0, count( $topics ) - 1 ) ];
		$guests        = implode( ', ', $topic['guests'] );
		$interviewers  = $interviewers_pool[ wp_rand( 0, count( $interviewers_pool ) - 1 ) ];
		$pdu           = $pdu_pool[ wp_rand( 0, count( $pdu_pool ) - 1 ) ];
		$slug          = 'podcast-episodio-' . $number . '-' . sanitize_title( $topic['title'] );

		$content = sprintf(
			'<p>Nuovo episodio di <strong>Project Management On The Go</strong>, il podcast di PMI Northern Italy.</p><p>In questo episodio parliamo di <strong>%1$s</strong> con <strong>%2$s</strong>.</p><p>Intervista a cura di <strong>%3$s</strong>.</p><p>%4$s</p>',
			esc_html( $topic['title'] ),
			esc_html( $guests ),
			esc_html( $interviewers ),
			esc_html( $topic['excerpt'] )
		);

		$extra_labels = array( 'LinkedIn Audio', 'Castbox', 'Google Podcasts', 'RSS Feed' );
		$extra_label  = $extra_labels[ wp_rand( 0, count( $extra_labels ) - 1 ) ];

		return array(
			'slug'             => $slug,
			'episode_number'   => (string) $number,
			'title'            => sprintf( 'Episodio %1$d: %2$s', $number, $topic['title'] ),
			'category'         => 'Project Management On The Go',
			'excerpt'          => $topic['excerpt'],
			'content'          => $content,
			'guests'           => $guests,
			'interviewers'     => $interviewers,
			'pdu'              => $pdu,
			'links'            => self::random_platform_links( (string) $number ),
			'extra_links'      => array(
				array(
					'label' => $extra_label,
					'url'   => 'https://www.pmi-nic.org/podcast/',
				),
			),
			'image'            => $cover,
		);
	}

	/**
	 * Generate plausible platform URLs for an episode.
	 *
	 * @param string $episode_number Episode number.
	 * @return array<string,string>
	 */
	private static function random_platform_links( $episode_number ) {
		$base = 'https://www.pmi-nic.org/podcast/episodio-' . $episode_number;

		return array(
			'apple'         => $base . '/apple',
			'spotify'       => $base . '/spotify',
			'youtube'       => $base . '/youtube',
			'youtube_music' => wp_rand( 0, 1 ) ? $base . '/youtube-music' : '',
			'amazon_music'  => wp_rand( 0, 1 ) ? $base . '/amazon-music' : '',
			'spreaker'      => $base . '/spreaker',
		);
	}

	/**
	 * Import a single episode.
	 *
	 * @param array $data Episode data.
	 * @return array|WP_Error
	 */
	private static function import_episode( $data ) {
		$existing = self::find_existing_episode( $data['slug'] );
		$created  = false;

		$postarr = array(
			'post_type'    => PMI_Podcast_Post_Type::POST_TYPE,
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
		update_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_EPISODE_NUMBER, $data['episode_number'] );
		update_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_GUESTS, $data['guests'] );
		update_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_INTERVIEWERS, $data['interviewers'] );
		update_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_PDU, $data['pdu'] );
		update_post_meta(
			$post_id,
			PMI_Podcast_Meta_Boxes::META_YOUTUBE_VIDEO,
			isset( $data['youtube_video'] ) ? esc_url_raw( $data['youtube_video'] ) : ''
		);

		foreach ( PMI_Podcast_Meta_Boxes::get_platform_meta_map() as $key => $meta_key ) {
			$value = isset( $data['links'][ $key ] ) ? $data['links'][ $key ] : '';
			update_post_meta( $post_id, $meta_key, $value );
		}

		update_post_meta( $post_id, PMI_Podcast_Meta_Boxes::META_EXTRA_LINKS, $data['extra_links'] );

		self::assign_category( $post_id, $data['category'] );
		$has_thumbnail = self::assign_featured_image( $post_id, $data['image'], $data['title'] );

		$podcast_data = PMI_Podcast_Meta_Boxes::get_podcast_data( $post_id );

		return array(
			'created'       => $created,
			'id'            => $post_id,
			'title'         => $podcast_data['title'],
			'episode'       => $podcast_data['episode_number'],
			'guests'        => $podcast_data['guests'],
			'pdu'           => $podcast_data['pdu'],
			'category'      => $podcast_data['category'],
			'has_thumbnail' => $has_thumbnail,
		);
	}

	/**
	 * Find existing demo episode by slug meta.
	 *
	 * @param string $slug Demo slug.
	 * @return int
	 */
	private static function find_existing_episode( $slug ) {
		$posts = get_posts(
			array(
				'post_type'      => PMI_Podcast_Post_Type::POST_TYPE,
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
	 * @param int    $post_id  Post ID.
	 * @param string $category Category name.
	 */
	private static function assign_category( $post_id, $category ) {
		$term = term_exists( $category, PMI_Podcast_Taxonomy::TAXONOMY );

		if ( ! $term ) {
			$term = wp_insert_term( $category, PMI_Podcast_Taxonomy::TAXONOMY );
		}

		if ( is_wp_error( $term ) ) {
			return;
		}

		$term_id = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
		wp_set_object_terms( $post_id, array( $term_id ), PMI_Podcast_Taxonomy::TAXONOMY );
	}

	/**
	 * Attach featured image from local file.
	 *
	 * @param int    $post_id   Post ID.
	 * @param string $file_path Local image path.
	 * @param string $title     Attachment title.
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
