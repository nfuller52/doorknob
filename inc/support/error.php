<?php
namespace PawsPlus\Doorknob\Support;

use PawsPlus\Doorknob\Support\Logger as Logger;

/**
* Handles errors reported by the plugin
*
* 1001 = Invalid User Credentials
* 1002 = Blank Access or Refresh Token
* 5001 = Failed server connection
*
*/
class Error
{

	public static function invalid_credentials( $message = '' )
	{
		Logger::message( $message, '1001: Invalid Credentials -> ' );
		return new \WP_Error( 'denied', __( "<strong>ERROR</strong>: Invalid username and password combination. <a href='" . get_site_url() . "/wp-login.php?action=lostpassword'>Lost your password</a>" ) );
	}

	public static function blank_token( $message = '' )
	{
		Logger::message( $message, '1002: Blank Token -> ' );
		return new \WP_Error( 'denied', __( "<strong>ERROR</strong>: Invalid username and password combination. <a href='" . get_site_url() . "/wp-login.php?action=lostpassword'>Lost your password</a>" ) );
	}

	public static function failed_connection( $message = '' ) {
		Logger::message( $message, '5001: Failed Connection -> ' );
		return new \WP_Error( 'denied', __( "<strong>DO'H</strong>: It seems like our authentication servers are down. Please try again later!" ) );
	}

}
