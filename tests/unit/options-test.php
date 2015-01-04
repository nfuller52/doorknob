<?php

use PawsPlus\Doorknob\Models\Options as Options;

class OptionsTest extends WP_UnitTestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->fake_options = array(
			'environment'          => 'test',
			'test_token_url'       => 'http://localhost:3000/oauth/tokens',
			'test_me_url'          => 'http://localhost:3000/me',
			'staging_token_url'    => 'https://staging-apollo.pet-records.com/oauth/token',
			'staging_me_url'       => 'https://staging-apollo.pet-records.com/me',
			'edge_token_url'       => 'https://edge-apollo.pet-records.com/oauth/token',
			'edge_me_url'          => 'https://edge-apollo.pet-records.com/me',
			'production_token_url' => 'https://apollo.pet-records.com/oauth/token',
			'production_me_url'    => 'https://apollo.pet-records.com/me',
			'timeout'              => 30
		);
		add_option( 'doorknob_options', $this->fake_options );

		$this->options = new Options();
	}

	public function tearDown()
	{
		delete_option( 'doorknob_options' );

		parent::tearDown();
	}

	public function testItCanBeInstantiated()
	{
		$this->assertInstanceOf( 'PawsPlus\Doorknob\Models\Options', $this->options );
	}

	public function testEnviornmentReturnsCorrectOptionValue()
	{
		$result = $this->options->environment();

		$this->assertInternalType( 'string', $result );
		$this->assertEquals( $this->fake_options['environment'], $result );
	}

	public function testTokenUrlReturnsCorrectOptionValue()
	{
		$result = $this->options->token_url();

		$this->assertInternalType( 'string', $result );
		$this->assertEquals( $this->fake_options['test_token_url'], $result );
	}

	public function testTokenUrlReturnsCorrectEnvironmentOption()
	{
		$this->fake_options['environment'] = 'staging';
		update_option( 'doorknob_options', $this->fake_options );

		$new_options = new Options();
		$result = $new_options->token_url();

		$this->assertInternalType( 'string', $result );
		$this->assertEquals( $this->fake_options['staging_token_url'], $result );
	}

	public function testMeUrlReturnsCorrectOptionValue()
	{
		$result = $this->options->me_url();

		$this->assertInternalType( 'string', $result );
		$this->assertEquals( $this->fake_options['test_me_url'], $result );
	}

	public function testMeUrlReturnsCorrectEnvironmentOption()
	{
		$this->fake_options['environment'] = 'staging';
		update_option( 'doorknob_options', $this->fake_options );

		$new_options = new Options();
		$result = $new_options->me_url();

		$this->assertInternalType( 'string', $result );
		$this->assertEquals( $this->fake_options['staging_me_url'], $result );
	}

	public function testTimeoutReturnsCorrectOptionValue()
	{
		$result = $this->options->timeout();

		$this->assertInternalType( 'integer', $result );
		$this->assertEquals( $this->fake_options['timeout'], $result );
	}

}
