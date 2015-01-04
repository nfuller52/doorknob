<?php

use PawsPlus\Doorknob\Models\Apollo as Apollo;

class ApolloTest extends WP_UnitTestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->options = $this->getMockBuilder( 'PawsPlus\Doorknob\Models\Options' )
			->setMethods( array( 'token_url', 'me_url' ) )
			->getMock();

		$this->http = $this->getMockBuilder( 'PawsPlus\Doorknob\Services\Http' )
			->setMethods( array( 'post', 'get' ) )
			->getMock();

		$this->apollo = new Apollo( $this->options, $this->http );
	}

	public function testItCanBeInstantiated()
	{
		$this->assertInstanceOf( 'PawsPlus\Doorknob\Models\Apollo', $this->apollo );
	}

	public function testRequestTokensFailsWithoutUsername()
	{
		$result = $this->apollo->request_tokens( '', 'secret_password' );
		$this->assertInstanceOf( 'WP_Error', $result );
	}

	public function testRequestTokensFailsWithoutPassword()
	{
		$result = $this->apollo->request_tokens( 'user@email.com', '' );
		$this->assertInstanceOf( 'WP_Error', $result );
	}

	public function testRequestTokensReturnsArrayUponSuccess()
	{
		$this->http->expects( $this->once() )
			->method( 'post' )
			->will( $this->returnValue( array() ) );

		$result = $this->apollo->request_tokens( 'user@email.com', 'secret_password' );
		$this->assertInternalType( 'array', $result );
	}

	public function testRequestTokensReturnsWPErrorUponFailure()
	{
		$this->http->expects( $this->once() )
			->method( 'post' )
			->will( $this->returnValue( new WP_Error() ) );

		$result = $this->apollo->request_tokens( 'user@email.com', 'secret_password' );
		$this->assertInstanceOf( 'WP_Error', $result );
	}

	public function testUserFailsWithBlankToken()
	{
		$result = $this->apollo->user( '' );
		$this->assertInstanceOf( 'WP_Error', $result );
	}

	public function testUserReturnsArrayUponSuccess()
	{
		$this->http->expects( $this->once() )
			->method( 'get' )
			->will( $this->returnValue( array() ) );

		$result = $this->apollo->user( 'access_token' );
		$this->assertInternalType( 'array', $result );
	}

	public function testUserReturnsWPErrorUponFailure()
	{
		$this->http->expects( $this->once() )
			->method( 'get' )
			->will( $this->returnValue( new WP_Error() ) );

		$result = $this->apollo->user( 'access_token' );
		$this->assertInstanceOf( 'WP_Error', $result );
	}

}
