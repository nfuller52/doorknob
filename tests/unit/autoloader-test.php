<?php
use PawsPlus\Doorknob\Autoloader as Autoloader;

class AutoloaderTest extends WP_UnitTestCase
{
	public function setUp()
	{
		$this->includes_dir = DOORKNOB_PLUGIN_DIR . 'inc/';
	}

	public function test_valid_file_at_root()
	{
		$expected_file = Autoloader::autoload( 'PawsPlus\Doorknob\AdminSettings' );
		$expected_path = $this->includes_dir . 'admin_settings.php';

		$this->assertEquals( $expected_file, $expected_path );
	}

	public function test_bad_class_name()
	{
		$expected_file = Autoloader::autoload( 'PawsPlus\Doorknob\LardVard' );

		$this->assertFalse( $expected_file );
	}

	public function test_nested_namespace()
	{
		$expected_file = Autoloader::autoload( 'PawsPlus\Doorknob\Connection\Request' );
		$expected_path = $this->includes_dir . 'connection/request.php';

		$this->assertEquals( $expected_file, $expected_path );
	}

}
