<?php
/**
 * Web runner: import demo podcast episodes on the live WordPress site.
 *
 * Protected by a one-time key file (.podcast-import-key) uploaded alongside this script.
 * Used by deploy/deploy.php --import-podcasts
 *
 * @package PMI_Events
 */

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	http_response_code( 500 );
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo "Impossibile trovare wp-load.php\n";
	exit( 1 );
}

require_once $wp_load;

header( 'Content-Type: text/plain; charset=utf-8' );

$key_file = __DIR__ . '/.podcast-import-key';
$provided = isset( $_GET['key'] ) ? (string) $_GET['key'] : '';

if ( ! file_exists( $key_file ) ) {
	http_response_code( 403 );
	echo "Chiave import non configurata o già utilizzata.\n";
	exit( 1 );
}

$expected = trim( (string) file_get_contents( $key_file ) );

if ( '' === $provided || ! hash_equals( $expected, $provided ) ) {
	http_response_code( 403 );
	echo "Chiave non valida.\n";
	exit( 1 );
}

@unlink( $key_file );

if ( ! class_exists( 'PMI_Podcast_Meta_Boxes' ) ) {
	http_response_code( 500 );
	echo "Modulo Podcast non disponibile. Verifica che il plugin sia attivo.\n";
	exit( 1 );
}

require_once dirname( __DIR__ ) . '/includes/class-podcast-demo-import.php';

$result = PMI_Podcast_Demo_Import::import_all();

if ( is_wp_error( $result ) ) {
	http_response_code( 500 );
	echo 'Errore: ' . $result->get_error_message() . "\n";
	exit( 1 );
}

echo "Import podcast completato sul sito live.\n\n";

foreach ( $result['episodes'] as $episode ) {
	echo sprintf(
		"- [%d] Ep.%s %s | ospiti: %s | PDU: %s | immagine: %s\n",
		$episode['id'],
		$episode['episode'],
		$episode['title'],
		$episode['guests'],
		$episode['pdu'],
		$episode['has_thumbnail'] ? 'sì' : 'no'
	);
}

echo "\nCreati: {$result['created']}, aggiornati: {$result['updated']}, errori: {$result['errors']}\n";
exit( 0 );
