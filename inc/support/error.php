<?php

/**
* This class is responsible to handeling all the errors returned
* by this plugin
*
* @category Support
*/
namespace PawsPlus\Doorknob\Support;

use PawsPlus\Doorknob\Support\Logger as Logger;

/**
* Error code index
*
* 1001 = Invalid User Credentials
* 1002 = Blank Access or Refresh Token
* 5001 = Failed server connection
*
*/
class Error
{

	/**
	* Logs a message if the debugger is active and raises a WP_Error specific
	* to invalid user credentials
	* @static
	*
	* @param string $message optionally add a message to be rendered in the logs
	*
	* @return WP_Error
	*/
	public static function invalid_credentials( $message = '' )
	{
		Logger::message( $message, '1001: Invalid Credentials -> ' );
		return new \WP_Error( 'denied', __( "<strong>ERROR</strong>: Invalid username and password combination. <a href='" . get_site_url() . "/wp-login.php?action=lostpassword'>Lost your password</a>" ) );
	}

	/**
	* Logs a message if the debugger is active and raises a WP_Error specific
	* to blank tokens
	* @static
	*
	* @param string $message optionally add a message to be rendered in the logs
	*
	* @return WP_Error
	*/
	public static function blank_token( $message = '' )
	{
		Logger::message( $message, '1002: Blank Token -> ' );
		return new \WP_Error( 'denied', __( "<strong>ERROR</strong>: Invalid username and password combination. <a href='" . get_site_url() . "/wp-login.php?action=lostpassword'>Lost your password</a>" ) );
	}

	/**
	* Logs a message if the debugger is active and raises a WP_Error specific
	* to a failed connection
	* @static
	*
	* @param string $message optionally add a message to be rendered in the logs
	*
	* @return WP_Error
	*/
	public static function failed_connection( $message = '' ) {
		Logger::message( $message, '5001: Failed Connection -> ' );
		return new \WP_Error( 'denied', __( "<strong>DO'H</strong>: It seems like our authentication servers are down. Please try again later!" ) );
	}

}
