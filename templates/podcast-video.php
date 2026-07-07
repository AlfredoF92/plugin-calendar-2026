<?php
/**
 * Podcast YouTube video embed template.
 *
 * Expects: $embed_html (string).
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="pmi-podcast-video-shell">
	<div class="pmi-podcast-video">
		<?php echo $embed_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
