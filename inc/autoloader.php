<?php

namespace PawsPlus\Doorknob;

class Autoloader
{

	public static function register( $prepend = false )
	{
		spl_autoload_register( array( new self, 'autoload' ) );
	}

	public static function autoload( $class )
	{
		$class = ltrim( $class, '\\' );

		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$class = str_replace( __NAMESPACE__, '', $class );

		$file = $file = dirname( __FILE__ ) . '/' . strtolower( str_replace( '\\', DIRECTORY_SEPARATOR, $class ) ) . '.php';

		if ( is_file( $file ) ) {
			require_once( $file );
		}
	}

}
