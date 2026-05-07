<?php
/**
 * Bootstrap Tests.
 *
 * Set up PHPUnit related dependencies
 * for WP Mock tests.
 *
 * @package ImageConverterWebP
 */

// First we need to load the composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Stub WP functions not covered by WP_Mock's built-in stubs.
if ( ! function_exists( 'register_activation_hook' ) ) {
	function register_activation_hook( $file, $callback ) {} // phpcs:ignore
}

if ( ! function_exists( 'plugin_basename' ) ) {
	function plugin_basename( $file ) { // phpcs:ignore
		return basename( $file );
	}
}

// Load the main plugin file before strict mode is activated so that the
// add_action / add_filter calls fired by icfw_run() are not counted as
// unexpected calls in any test.
require_once dirname( __DIR__ ) . '/image-converter-webp.php';

// Bootstrap WP_Mock.
WP_Mock::activateStrictMode();
WP_Mock::bootstrap();
