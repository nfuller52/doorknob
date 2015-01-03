<?php
use PawsPlus\Doorknob\Connection\Request as Request;

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
		// Given
		$request = new Request();

		// When

		// Then
		$this->assertInstanceOf( 'PawsPlus\Doorknob\Connection\Request', $request );
	}

	function test_environment()
	{
		// Given
		$request = new Request();

		// When
		$environment = $request->environment();

		// Then
		$this->assertSame( $environment, 'test' );
	}

}
