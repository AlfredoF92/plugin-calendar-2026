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
