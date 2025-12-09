<?php
/**
 * Plugin Name:     Book Manager
 * Plugin URI:      https://example.com
 * Plugin Prefix:   BOMAN
 * Description:     Manage book information with custom table and post types.
 * Author:          Amir Safari
 * Author URI:      https://profiles.wordpress.org/amirsafaridevs/
 * Text Domain:     book-manager
 * Domain Path:     /languages
 * Version:         0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require dirname( __FILE__ ) . '/vendor/autoload.php';
}

$app = boman_init();
