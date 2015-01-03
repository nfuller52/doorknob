<?php
namespace PawsPlus\Doorknob;

class Autoloader
{

	private static $namespace = __NAMESPACE__;

	public static function register()
	{
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	public static function autoload( $class )
	{
		$class = ltrim( $class, '\\' );

		if ( 0 !== strpos( $class, self::$namespace ) ) return;

		$class = str_replace( self::$namespace, '', $class );
		$class = preg_replace( '/([a-zA-Z])(?=[A-Z])/', '$1_', $class );
		$file = dirname( __FILE__ ) . strtolower( str_replace( '\\', DIRECTORY_SEPARATOR, $class ) ) . '.php';

		if ( is_file( $file ) ) {
			require_once( $file );
			return $file;
		} else {
			return false;
		}
	}

}
