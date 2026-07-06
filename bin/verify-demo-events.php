<?php
$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';
require_once $wp_load;

$slugs = array(
	'podcast-episodio-98-pmbok8',
	'webinar-cultura-pm-ambienti-non-tradizionali',
	'studio-ai-maturity-it-project-success',
);

$required_meta = array(
	'_pmi_event_start_date',
	'_pmi_event_end_date',
	'_pmi_event_start_time',
	'_pmi_event_end_time',
	'_pmi_event_location',
	'_pmi_event_organizer',
	'_pmi_event_language',
	'_pmi_event_price_members',
	'_pmi_event_price_guests',
	'_pmi_event_registration_url',
);

foreach ( $slugs as $slug ) {
	$posts = get_posts(
		array(
			'post_type'      => 'pmi_event',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_pmi_event_demo_slug',
			'meta_value'     => $slug,
		)
	);

	if ( empty( $posts ) ) {
		echo "MISSING: {$slug}\n";
		continue;
	}

	$post = $posts[0];
	$data = PMI_Events_Meta_Boxes::get_event_data( $post->ID );
	$thumb = has_post_thumbnail( $post->ID ) ? 'yes' : 'no';
	$excerpt_ok = ! empty( $post->post_excerpt ) ? 'yes' : 'no';
	$content_ok = ! empty( $post->post_content ) ? 'yes' : 'no';
	$cat_ok = ! empty( $data['category'] ) ? $data['category'] : 'NO';

	echo "OK: {$slug}\n";
	echo "  title: {$data['title']}\n";
	echo "  date: {$data['start_date']} -> {$data['end_date']}\n";
	echo "  time: {$data['start_time']} -> {$data['end_time']}\n";
	echo "  location: {$data['location']}\n";
	echo "  organizer: {$data['organizer']}\n";
	echo "  language: {$data['language']}\n";
	echo "  pdu: {$data['pdu']}\n";
	echo "  prices: {$data['price_members']} / {$data['price_guests']}\n";
	echo "  registration: {$data['registration_url']}\n";
	echo "  category: {$cat_ok}\n";
	echo "  excerpt: {$excerpt_ok} | content: {$content_ok} | thumbnail: {$thumb}\n";

	foreach ( $required_meta as $meta_key ) {
		$value = get_post_meta( $post->ID, $meta_key, true );
		if ( '' === $value && '_pmi_event_pdu' !== $meta_key ) {
			echo "  WARN empty meta: {$meta_key}\n";
		}
	}

	echo "\n";
}
