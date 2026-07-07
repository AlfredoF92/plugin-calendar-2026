<?php
/**
 * One-time script: import 3 demo PMI podcast episodes.
 *
 * Run from project root:
 *   c:\xampp\php\php.exe wp-content\plugins\pmi-events\bin\import-demo-podcasts.php
 *
 * @package PMI_Events
 */

if ( php_sapi_name() !== 'cli' && ! defined( 'WP_CLI' ) ) {
	die( "Esegui questo script da riga di comando.\n" );
}

$wp_load = dirname( __DIR__, 4 ) . '/wp-load.php';

if ( ! file_exists( $wp_load ) ) {
	fwrite( STDERR, "Impossibile trovare wp-load.php in: {$wp_load}\n" );
	exit( 1 );
}

require_once $wp_load;

if ( ! class_exists( 'PMI_Podcast_Meta_Boxes' ) ) {
	fwrite( STDERR, "Plugin PMI Events non attivo o modulo Podcast non disponibile.\n" );
	exit( 1 );
}

require_once dirname( __DIR__ ) . '/includes/class-podcast-demo-import.php';

$result = PMI_Podcast_Demo_Import::import_all();

if ( is_wp_error( $result ) ) {
	fwrite( STDERR, 'Errore: ' . $result->get_error_message() . "\n" );
	exit( 1 );
}

echo "Import podcast completato.\n\n";

foreach ( $result['episodes'] as $episode ) {
	echo sprintf(
		"- [%d] Ep.%s %s | ospiti: %s | PDU: %s | categoria: %s | immagine: %s\n",
		$episode['id'],
		$episode['episode'],
		$episode['title'],
		$episode['guests'],
		$episode['pdu'],
		$episode['category'],
		$episode['has_thumbnail'] ? 'sì' : 'no'
	);
}

echo "\nCreati: {$result['created']}, aggiornati: {$result['updated']}, errori: {$result['errors']}\n";
exit( 0 );
