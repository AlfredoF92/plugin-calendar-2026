<?php
/**
 * Elementor dynamic tags for single event text fields.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Event title.
 */
class PMI_Events_Elementor_Tag_Title extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-title';
	}

	public function get_title() {
		return __( 'Titolo evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['title'] ) {
			return;
		}

		echo esc_html( $data['title'] );
	}
}

/**
 * Event short description (excerpt).
 */
class PMI_Events_Elementor_Tag_Excerpt extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-excerpt';
	}

	public function get_title() {
		return __( 'Descrizione breve evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['excerpt'] ) {
			return;
		}

		echo wp_kses_post( $data['excerpt'] );
	}
}

/**
 * Event category (taxonomy).
 */
class PMI_Events_Elementor_Tag_Category extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-category';
	}

	public function get_title() {
		return __( 'Categoria evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || empty( $data['categories'] ) ) {
			return;
		}

		echo esc_html( implode( ', ', $data['categories'] ) );
	}
}

/**
 * Event location.
 */
class PMI_Events_Elementor_Tag_Location extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-location';
	}

	public function get_title() {
		return __( 'Luogo evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['location'] ) {
			return;
		}

		echo esc_html( $data['location'] );
	}
}

/**
 * Event organizer.
 */
class PMI_Events_Elementor_Tag_Organizer extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-organizer';
	}

	public function get_title() {
		return __( 'Organizzatore evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['organizer'] ) {
			return;
		}

		echo esc_html( $data['organizer'] );
	}
}

/**
 * Event language.
 */
class PMI_Events_Elementor_Tag_Language extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-language';
	}

	public function get_title() {
		return __( 'Lingua evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['language'] ) {
			return;
		}

		echo esc_html( $data['language'] );
	}
}

/**
 * Event PDU points.
 */
class PMI_Events_Elementor_Tag_Pdu extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-pdu';
	}

	public function get_title() {
		return __( 'N. PDU evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['pdu'] ) {
			return;
		}

		echo esc_html( $data['pdu'] );
	}
}

/**
 * Event price for members.
 */
class PMI_Events_Elementor_Tag_Price_Members extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-price-members';
	}

	public function get_title() {
		return __( 'Prezzo soci evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['price_members'] ) {
			return;
		}

		echo esc_html( $data['price_members'] );
	}
}

/**
 * Event price for non-members/guests.
 */
class PMI_Events_Elementor_Tag_Price_Guests extends PMI_Events_Elementor_Tag_Base {

	public function get_name() {
		return 'pmi-event-price-guests';
	}

	public function get_title() {
		return __( 'Prezzo non soci evento', 'pmi-events' );
	}

	public function render() {
		$data = $this->get_event_data();

		if ( ! $data || '' === $data['price_guests'] ) {
			return;
		}

		echo esc_html( $data['price_guests'] );
	}
}
