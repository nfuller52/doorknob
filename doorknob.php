<?php
/*
Plugin Name: Doorknob
Version: 0.0.2
Description: An Oauth manager for Apollo integration.
Author: Nick Fuller
Author URI: http://www.pawsplus.com
Plugin URI: http://www.pawsplus.com
Text Domain: doorknob
Domain Path: /languages
*/

namespace PawsPlus\Doorknob;

defined( 'ABSPATH' ) or die( "It's a trap!" );
define( 'DOORKNOB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once( DOORKNOB_PLUGIN_DIR . '/inc/autoloader.php' );
Autoloader::register();

if ( !array_key_exists( 'dk_doorknob', $GLOBALS ) ) {
}
