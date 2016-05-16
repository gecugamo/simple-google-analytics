<?php
/**
 * Uninstall script for Simplest Google Analytics plugin.
 *
 * @package WordPress
 */

// If uninstall is not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Delete setting from options table.
delete_option( 'sga_tracking_id' );
