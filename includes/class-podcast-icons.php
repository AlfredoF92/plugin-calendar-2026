<?php
/**
 * Icons and platform metadata for podcast links.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

/**
 * Provides fixed listening-platform definitions and inline SVG icons.
 */
class PMI_Podcast_Icons {

	/**
	 * Fixed platforms, in display order.
	 *
	 * @return array<string,array{label:string,color:string}>
	 */
	public static function get_platforms() {
		return array(
			'apple'         => array(
				'label' => __( 'Apple Podcast', 'pmi-events' ),
				'color' => '#9933CC',
			),
			'spotify'       => array(
				'label' => __( 'Spotify', 'pmi-events' ),
				'color' => '#1DB954',
			),
			'youtube'       => array(
				'label' => __( 'YouTube', 'pmi-events' ),
				'color' => '#FF0000',
			),
			'youtube_music' => array(
				'label' => __( 'YouTube Music', 'pmi-events' ),
				'color' => '#FF0000',
			),
			'amazon_music'  => array(
				'label' => __( 'Amazon Music', 'pmi-events' ),
				'color' => '#00A8E1',
			),
			'spreaker'      => array(
				'label' => __( 'Spreaker', 'pmi-events' ),
				'color' => '#F5A623',
			),
		);
	}

	/**
	 * Get inline SVG markup for a platform (or the generic link icon).
	 *
	 * @param string $key Platform key, or 'link' for the generic icon.
	 * @return string
	 */
	public static function get_icon_svg( $key ) {
		switch ( $key ) {
			case 'apple':
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="3.1" fill="currentColor"/><path d="M12 2.5a9.5 9.5 0 0 1 9.5 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/><path d="M12 6a6 6 0 0 1 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/><path d="M4.5 12A7.5 7.5 0 0 1 12 4.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none" opacity="0.55"/></svg>';

			case 'spotify':
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6 9.6c3.7-1.1 8.3-.9 11.6 1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M6.8 13c3-.85 6.9-.7 9.6.85" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M7.5 16.3c2.4-.6 5.5-.5 7.6.75" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>';

			case 'youtube':
				return '<svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M10 8.6v6.8L16 12l-6-3.4Z"/></svg>';

			case 'youtube_music':
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="6.2" stroke="currentColor" stroke-width="1.6"/><path d="M10.3 9.4v5.2L15 12l-4.7-2.6Z" fill="currentColor"/></svg>';

			case 'amazon_music':
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9 16V8.6l7-1.6v7.8" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" fill="none"/><circle cx="8" cy="16.5" r="1.8" fill="currentColor"/><circle cx="15" cy="14.8" r="1.8" fill="currentColor"/></svg>';

			case 'spreaker':
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><circle cx="12" cy="12" r="2" fill="currentColor"/><path d="M8.3 9.5a5 5 0 0 0 0 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M15.7 9.5a5 5 0 0 1 0 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M5.6 7a9 9 0 0 0 0 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity="0.6"/><path d="M18.4 7a9 9 0 0 1 0 10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity="0.6"/></svg>';

			case 'link':
			default:
				return '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M9.5 14.5 14.5 9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M11 8.3 12.2 7a3 3 0 1 1 4.2 4.2l-1.3 1.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/><path d="M13 15.7 11.8 17a3 3 0 1 1-4.2-4.2l1.3-1.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" fill="none"/></svg>';
		}
	}

	/**
	 * Render a single platform/link icon button.
	 *
	 * @param string $key   Platform key or 'link'.
	 * @param string $url   Target URL.
	 * @param string $label Accessible label.
	 * @param string $color Optional background color override.
	 * @return string
	 */
	public static function render_link( $key, $url, $label, $color = '' ) {
		if ( empty( $url ) ) {
			return '';
		}

		$style = $color ? sprintf( ' style="--pmi-podcast-icon-color:%s"', esc_attr( $color ) ) : '';

		return sprintf(
			'<a class="pmi-podcast-icon pmi-podcast-icon--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer" title="%3$s"%4$s><span class="screen-reader-text">%3$s</span>%5$s</a>',
			esc_attr( $key ),
			esc_url( $url ),
			esc_attr( $label ),
			$style,
			self::get_icon_svg( $key )
		);
	}
}
