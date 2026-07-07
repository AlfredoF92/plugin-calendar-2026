<?php
/**
 * Podcast card template.
 *
 * @package PMI_Events
 */

defined( 'ABSPATH' ) || exit;

$data      = PMI_Podcast_Meta_Boxes::get_podcast_data( $podcast_id );
$platforms = PMI_Podcast_Icons::get_platforms();
?>
<article class="pmi-podcast-card">
	<?php if ( $data['thumbnail'] ) : ?>
		<a class="pmi-podcast-card__media" href="<?php echo esc_url( $data['permalink'] ); ?>">
			<img src="<?php echo esc_url( $data['thumbnail'] ); ?>" alt="<?php echo esc_attr( $data['title'] ); ?>" loading="lazy">
			<?php if ( $data['episode_number'] ) : ?>
				<span class="pmi-podcast-card__badge">
					<?php
					printf( esc_html__( 'Ep. %s', 'pmi-events' ), esc_html( $data['episode_number'] ) );
					?>
				</span>
			<?php endif; ?>
		</a>
	<?php endif; ?>

	<div class="pmi-podcast-card__body">
		<h3 class="pmi-podcast-card__title">
			<a href="<?php echo esc_url( $data['permalink'] ); ?>"><?php echo esc_html( $data['title'] ); ?></a>
		</h3>

		<?php if ( $data['guests'] ) : ?>
			<p class="pmi-podcast-card__meta">
				<strong><?php esc_html_e( 'Ospiti:', 'pmi-events' ); ?></strong> <?php echo esc_html( $data['guests'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $data['excerpt'] ) : ?>
			<p class="pmi-podcast-card__excerpt"><?php echo esc_html( $data['excerpt'] ); ?></p>
		<?php endif; ?>

		<?php if ( $data['pdu'] ) : ?>
			<p class="pmi-podcast-card__pdu"><?php echo esc_html( $data['pdu'] ); ?></p>
		<?php endif; ?>

		<div class="pmi-podcast-card__links">
			<?php
			foreach ( $platforms as $key => $platform ) :
				$url = isset( $data['links'][ $key ] ) ? $data['links'][ $key ] : '';
				echo PMI_Podcast_Icons::render_link( $key, $url, $platform['label'], $platform['color'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endforeach;

			foreach ( $data['extra_links'] as $link ) :
				if ( empty( $link['url'] ) ) {
					continue;
				}
				$label = ! empty( $link['label'] ) ? $link['label'] : __( 'Link', 'pmi-events' );
				echo PMI_Podcast_Icons::render_link( 'link', $link['url'], $label ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			endforeach;
			?>
		</div>
	</div>
</article>
