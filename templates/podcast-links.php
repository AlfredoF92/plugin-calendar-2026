<?php
/**
 * Podcast listening links template (single episode).
 *
 * Expects: $atts (array), $links (array), $post_id (int).
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="pmi-podcast-links-shell">
	<?php if ( ! empty( $atts['title'] ) ) : ?>
		<h3 class="pmi-podcast-links__title"><?php echo esc_html( $atts['title'] ); ?></h3>
	<?php endif; ?>

	<div class="pmi-podcast-links">
		<?php
		foreach ( $links as $link ) {
			echo PMI_Podcast_Icons::render_list_item( $link['key'], $link['url'], $link['label'], $link['color'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		?>
	</div>
</div>
