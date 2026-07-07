<?php
/**
 * Elementor dynamic tags for podcast text fields.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

class PMI_Podcast_Elementor_Tag_Title extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-title'; }
	public function get_title() { return __( 'Titolo episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['title'] ) { echo esc_html( $d['title'] ); } }
}

class PMI_Podcast_Elementor_Tag_Excerpt extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-excerpt'; }
	public function get_title() { return __( 'Descrizione breve episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['excerpt'] ) { echo wp_kses_post( $d['excerpt'] ); } }
}

class PMI_Podcast_Elementor_Tag_Episode_Number extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-episode-number'; }
	public function get_title() { return __( 'Numero episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['episode_number'] ) { echo esc_html( $d['episode_number'] ); } }
}

class PMI_Podcast_Elementor_Tag_Guests extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-guests'; }
	public function get_title() { return __( 'Ospiti episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['guests'] ) { echo esc_html( $d['guests'] ); } }
}

class PMI_Podcast_Elementor_Tag_Interviewers extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-interviewers'; }
	public function get_title() { return __( 'Intervista a cura di', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['interviewers'] ) { echo esc_html( $d['interviewers'] ); } }
}

class PMI_Podcast_Elementor_Tag_Pdu extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-pdu'; }
	public function get_title() { return __( 'PDU episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && '' !== $d['pdu'] ) { echo esc_html( $d['pdu'] ); } }
}

class PMI_Podcast_Elementor_Tag_Category extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-category'; }
	public function get_title() { return __( 'Categoria episodio', 'pmi-events' ); }
	public function render() { $d = $this->get_podcast_data(); if ( $d && ! empty( $d['categories'] ) ) { echo esc_html( implode( ', ', $d['categories'] ) ); } }
}

class PMI_Podcast_Elementor_Tag_Content extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-content'; }
	public function get_title() { return __( 'Contenuto episodio', 'pmi-events' ); }
	public function render() {
		$d = $this->get_podcast_data();
		if ( ! $d || '' === trim( (string) $d['content'] ) ) {
			return;
		}
		echo apply_filters( 'the_content', $d['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

class PMI_Podcast_Elementor_Tag_Publish_Date extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-publish-date'; }
	public function get_title() { return __( 'Data pubblicazione episodio', 'pmi-events' ); }
	public function get_categories() {
		return array( \Elementor\Modules\DynamicTags\Module::DATETIME_CATEGORY );
	}
	public function render() {
		$d = $this->get_podcast_data();
		if ( ! $d || '' === $d['date'] ) {
			return;
		}
		echo esc_html( $d['date'] );
	}
}

class PMI_Podcast_Elementor_Tag_Episode_Label extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-episode-label'; }
	public function get_title() { return __( 'Etichetta episodio', 'pmi-events' ); }
	public function render() {
		$d = $this->get_podcast_data();
		if ( ! $d || '' === $d['episode_number'] ) {
			return;
		}
		/* translators: %s: episode number */
		echo esc_html( sprintf( __( 'Episodio %s', 'pmi-events' ), $d['episode_number'] ) );
	}
}

class PMI_Podcast_Elementor_Tag_Extra_Links extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-extra-links'; }
	public function get_title() { return __( 'Altri link episodio', 'pmi-events' ); }
	public function render() {
		$d = $this->get_podcast_data();
		if ( ! $d || empty( $d['extra_links'] ) || ! is_array( $d['extra_links'] ) ) {
			return;
		}
		$lines = array();
		foreach ( $d['extra_links'] as $link ) {
			if ( empty( $link['url'] ) ) {
				continue;
			}
			$label   = ! empty( $link['label'] ) ? $link['label'] : $link['url'];
			$lines[] = $label . ': ' . $link['url'];
		}
		if ( empty( $lines ) ) {
			return;
		}
		echo esc_html( implode( "\n", $lines ) );
	}
}

class PMI_Podcast_Elementor_Tag_Listening_Links extends PMI_Podcast_Elementor_Tag_Base {
	public function get_name() { return 'pmi-podcast-listening-links'; }
	public function get_title() { return __( 'Elenco piattaforme ascolto', 'pmi-events' ); }
	public function render() {
		$d = $this->get_podcast_data();
		if ( ! $d ) {
			return;
		}
		$links = PMI_Podcast_Icons::get_listening_links( $d['id'] );
		if ( empty( $links ) ) {
			return;
		}
		$labels = wp_list_pluck( $links, 'label' );
		echo esc_html( implode( ', ', $labels ) );
	}
}
