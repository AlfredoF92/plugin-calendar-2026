<?php
/**
 * One-time script: import 3 demo PMI events.
 *
 * Run from project root:
 *   c:\xampp\php\php.exe wp-content\plugins\pmi-events\bin\import-demo-events.php
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

if ( ! class_exists( 'PMI_Events_Meta_Boxes' ) ) {
	fwrite( STDERR, "Plugin PMI Events non attivo.\n" );
	exit( 1 );
}

require_once dirname( __DIR__ ) . '/includes/class-demo-import.php';

$result = PMI_Events_Demo_Import::import_all();

if ( is_wp_error( $result ) ) {
	fwrite( STDERR, 'Errore: ' . $result->get_error_message() . "\n" );
	exit( 1 );
}

echo "Import completato.\n\n";

foreach ( $result['events'] as $event ) {
	echo sprintf(
		"- [%d] %s | %s %s-%s | %s | categoria: %s | immagine: %s\n",
		$event['id'],
		$event['title'],
		$event['start_date'],
		$event['start_time'],
		$event['end_time'],
		$event['location'],
		$event['category'],
		$event['has_thumbnail'] ? 'sì' : 'no'
	);
}

echo "\nCreati: {$result['created']}, aggiornati: {$result['updated']}, errori: {$result['errors']}\n";
exit( 0 );
