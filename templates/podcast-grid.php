<?php
/**
 * Podcast grid template.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="pmi-podcast-grid-shell">
	<?php if ( ! empty( $atts['title'] ) ) : ?>
		<h2 class="pmi-podcast-grid__title"><?php echo esc_html( $atts['title'] ); ?></h2>
	<?php endif; ?>

	<?php if ( $query->have_posts() ) : ?>
		<div class="pmi-podcast-grid" style="--pmi-podcast-columns: <?php echo esc_attr( $columns ); ?>">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$podcast_id = get_the_ID();
				include PMI_EVENTS_DIR . 'templates/podcast-card.php';
			endwhile;
			?>
		</div>
	<?php else : ?>
		<p class="pmi-podcast-grid__empty"><?php esc_html_e( 'Nessun episodio disponibile al momento.', 'pmi-events' ); ?></p>
	<?php endif; ?>

	<?php
	$show_archive_link = 'yes' === $atts['archive_link'];
	$archive_url       = $show_archive_link ? get_post_type_archive_link( PMI_Podcast_Post_Type::POST_TYPE ) : '';
	?>
	<?php if ( $archive_url ) : ?>
		<div class="pmi-podcast-grid__footer">
			<a class="pmi-podcast-grid__footer-link" href="<?php echo esc_url( $archive_url ); ?>"><?php echo esc_html( $atts['archive_label'] ); ?></a>
		</div>
	<?php endif; ?>
</div>
