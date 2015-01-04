<?php

/**
* This class is a simple helper class to pass around when we need to log to the debugger
*
* @category Support
*/
namespace PawsPlus\Doorknob\Support;

class Logger
{

	/**
	* Logs a message if the debugger
	* @static
	*
	* @param string|array|object $message output to the debugger
	* @param string $prefix               optionally add a prefix to the message
	*
	* @return void
	*/
	public static function message( $message, $prefix = '' )
	{
		if ( WP_DEBUG === true ) {
			if ( is_array( $message ) || is_object( $message ) ) {
				error_log( __NAMESPACE__ . $prefix . print_r( $message, true ) );
			} else {
				error_log( __NAMESPACE__ . $prefix . $message );
			}
		}
	}

}
