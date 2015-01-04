<?php
namespace PawsPlus\Doorknob;

class Logger
{
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
