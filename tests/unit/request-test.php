<?php
use PawsPlus\Doorknob\Request as Request;

class RequestTest extends WP_UnitTestCase
{

	public function setUp()
	{
		parent::setUp();

		$options = array(
			'environment' => 'test',
			'token_url'   => 'http://localhost:3000/oauth/token',
			'me_url'      => 'http://localhost:3000/me'
		);
		add_option( 'apollo', serialize( $options ) );
	}

	function tearDown()
	{
		delete_option( 'apollo' );
		parent::tearDown();
	}

	function test_request_can_be_instantiated()
	{
		// Arrange
		$request = new Request();

		// Act

		// Assert
		$this->assertInstanceOf( 'PawsPlus\Doorknob\Request', $request );
	}

	function test_environment()
	{
		// Arrange
		$request = new Request();

		// Act
		$environment = $request->environment();

		// Assert
		$this->assertSame( $environment, 'test' );
	}

}
