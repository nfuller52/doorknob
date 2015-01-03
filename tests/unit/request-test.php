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

	public function tearDown()
	{
		delete_option( 'apollo' );

		parent::tearDown();
	}

	/**
	* @test
	*/
	public function test_request_can_be_instantiated()
	{
		$request = new Request();

		$this->assertInstanceOf( 'PawsPlus\Doorknob\Connection\Request', $request );
	}

	/**
	* @test
	*/
	public function test_environment()
	{
		$request = new Request();

		$environment = $request->environment();

		$this->assertSame( $environment, 'test' );
	}

}
