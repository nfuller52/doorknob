<?php

use PawsPlus\Doorknob\ExternalServices\Apollo as Apollo;

class ApolloTest extends WP_UnitTestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->options = $this->getMockBuilder( 'PawsPlus\Doorknob\DoorknobOptions' )
			->getMock();

		$this->mockApollo = $this->getMockBuilder( 'PawsPlus\Doorknob\DoorknobOptions' )
			->setConstructorArgs( array( $this->options ) )
			->setMethods( array( 'request' ) )
			->getMock();

		$this->apollo = new Apollo( $this->options );
	}

	public function testItCanBe()
	{
		$this->assertInstanceOf( 'PawsPlus\Doorknob\ExternalServices\Apollo', $this->apollo );
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
		$this->mockApollo->expects( $this->once() )
			->method( 'request' )
			->will( $this->returnValue( array() ) );

		$result = $this->apollo->request_tokens( 'user@email.com', 'secret_password' );
		$this->assertInternalType( 'array', $result );
	}

}
