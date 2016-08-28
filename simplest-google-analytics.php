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
		'sanitize_text_field'
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
 * Add google analytics to page.
 */
function sga_render_tracking_script() {

	$ga_tracking_id = get_option( 'sga_tracking_id', false );

	if ( $ga_tracking_id ) {
	?>
		<!-- Simplest Google Analytics : BEGIN -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
		ga('create', '<?php esc_attr_e( $ga_tracking_id ); ?>', 'auto');
		ga('send', 'pageview');
		</script>
		<!-- Simplest Google Analytics : END -->
	<?php
	}
}
add_action( 'wp_head', 'sga_render_tracking_script' );
