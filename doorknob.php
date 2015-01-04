<?php
/*
Plugin Name: Doorknob
Version: 0.0.3
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

// Setup the Autoloader
require_once( DOORKNOB_PLUGIN_DIR . '/inc/autoloader.php' );
Autoloader::register();

if ( !array_key_exists( 'dk_doorknob', $GLOBALS ) ) {

	function initialize_plugin()
	{
		$GLOBALS['pp_doorknob'] = new Doorknob();
	}
	add_action( 'init', 'PawsPlus\Doorknob\initialize_plugin' );

	/**
	* Create global functions for other plugins to utilize
	*
	*/
	function doorknob_remote_get( string $url, array $params )
	{
		return $GLOBALS['pp_doorknob']->remote_get( $params );
	}

	/**
	* Create global functions for other plugins to utilize
	*
	*/
	function doorknob_remote_post( string $url, array $params )
	{
		return $GLOBALS['pp_doorknob']->remote_post( $params );
	}
}

class Doorknob
{

	public function __construct()
	{
		if ( is_admin() ) $this->admin_settings();
	}

	public function remote_get( $params )
	{
		return '';
	}

	public function remote_post( $params )
	{
		return '';
	}

	private function admin_settings()
	{
		new AdminSettings( 'doorknob_general', 'doorknob_settings_section' );
	}

}
