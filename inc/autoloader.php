<?php
namespace PawsPlus\Doorknob;

class Autoloader
{

	public static function register()
	{
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	public static function autoload( $class )
	{
		$class = ltrim( $class, '\\' );

		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) return;

		$class = str_replace( __NAMESPACE__, '', $class );
		$class = preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1_', $class );
		$file = dirname( __FILE__ ) . strtolower( str_replace( '\\', DIRECTORY_SEPARATOR, $class ) ) . '.php';

		if ( is_file( $file ) ) require_once( $file );
	}

}
