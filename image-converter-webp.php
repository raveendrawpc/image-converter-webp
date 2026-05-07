<?php
/**
 * Plugin Name: Image Converter for WebP
 * Plugin URI:  https://github.com/badasswp/image-converter-webp
 * Description: Convert your WordPress JPG/PNG images to WebP formats during runtime.
 * Version:     1.5.0
 * Author:      badasswp
 * Author URI:  https://github.com/badasswp
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: image-converter-webp
 * Domain Path: /languages
 *
 * @package ImageConverterWebP
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ICFW_AUTOLOAD', __DIR__ . '/vendor/autoload.php' );

/**
 * Bail out, if Composer is NOT installed.
 * Log error message.
 *
 * @return bool
 */
function icfw_can_autoload(): bool {
	if ( ! file_exists( ICFW_AUTOLOAD ) ) {
		error_log(
			sprintf(
				esc_html__( 'Fatal Error: Composer not setup in %', 'image-converter-webp' ),
				__DIR__
			)
		);

		return false;
	}

	// Require autoload.
	require_once ICFW_AUTOLOAD;

	return true;
}

/**
 * Generate autoload notice if Composer is
 * NOT installed.
 *
 * @return void
 */
function icfw_autoload_notice(): void {
	printf(
		'<div class="notice notice-error"><p>%s</p></div>',
		sprintf(
			/* translators: plugin autoload file path. */
			esc_html__( 'Fatal Error: %s file does not exist. Please run `composer install` before activating Image Converter for WebP.', 'image-converter-webp' ),
			esc_html( ICFW_AUTOLOAD )
		)
	);
}

/**
 * Run plugin.
 *
 * @return void
 */
function icfw_run(): void {
	if ( icfw_can_autoload() ) {
		require_once __DIR__ . '/inc/Helpers/functions.php';
		( \ImageConverterWebP\Plugin::get_instance() )->run();
	} else {
		add_action( 'admin_notices', 'icfw_autoload_notice' );
	}
}

register_activation_hook(
	__FILE__,
	function () {
		if ( ! file_exists( ICFW_AUTOLOAD ) ) {
			// Deactivate immediately so the plugin is never left in a broken active state.
			deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die(
				sprintf(
					/* translators: plugin autoload file path. */
					esc_html__( 'Image Converter for WebP could not be activated. The file %s does not exist. Please run `composer install` before activating the plugin.', 'image-converter-webp' ),
					'<code>' . esc_html( ICFW_AUTOLOAD ) . '</code>'
				),
				esc_html__( 'Plugin Activation Error', 'image-converter-webp' ),
				[ 'back_link' => true ]
			);
		}
	}
);

icfw_run();
