<?php
/**
 * Plugin Name: Simplest Google Analytics
 * Author:      Gary Cuga-Moylan
 * Author URI:  https://cuga-moylan.com
 * Description: Simplest plugin to add Google Analytics tracking code to your site. Add your Tracking ID under Settings > General.
 * Version:     1.0
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:	simplest-google-analytics
 *
 * @package WordPress
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Set up settings field.
 */
function sga_settings_init() {

	// Add google analytics field to Settings > General page.
	add_settings_field(
		'sga_tracking_id_field',
		__( 'Google Analytics Tracking ID', 'simplest-google-analytics' ),
		'sga_settings_cb',
		'general'
	);

	// Register value in the options table.
	register_setting(
		'general',
		'sga_tracking_id',
	 	'sga_sanitize_cb'
	);
}
add_action( 'admin_init', 'sga_settings_init' );

/**
 * Add settings field to Settings > General page.
 */
function sga_settings_cb() {
	$ga_tracking_id = get_option( 'sga_tracking_id' );
	echo "<input type='text' name='sga_tracking_id' value='{$ga_tracking_id}' />"; // WPCS XSS:ok. Input sanitized and escaped by sga_ga_sanitize_cb.
}

/**
 * Sanizize settings field.
 *
 * @param string $ga_tracking_id Google analytics tracking ID.
 */
function sga_sanitize_cb( $ga_tracking_id ) {
	$ga_tracking_id = sanitize_text_field( $ga_tracking_id );
	return esc_attr( $ga_tracking_id );
}

/**
 * Add google analytics to page.
 */
function sga_render_tracking_script() {

	$ga_tracking_id = get_option( 'sga_tracking_id', false );

	if ( $ga_tracking_id ) {
		echo "<!-- Google Analytics -->\n";
		echo "<script>\n";
		echo "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
		echo "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
		echo "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
		echo "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');\n";
		echo "ga('create', '{$ga_tracking_id}', 'auto');\n"; // WPCS XSS:ok. Input sanitized and escaped by sga_ga_sanitize_cb.
		echo "ga('send', 'pageview');\n";
		echo "</script>\n";
		echo "<!-- End Google Analytics -->\n";
	}
}
add_action( 'wp_head', 'sga_render_tracking_script' );
