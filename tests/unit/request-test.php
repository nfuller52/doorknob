<?php
use PawsPlus\Doorknob\Connection\Request as Request;

class RequestTest extends WP_UnitTestCase
{

	public function setUp()
	{
		parent::setUp();

		$this->options = array(
			'environment'            => 'test',
			'test_token_url'         => 'http://localhost:3000/oauth/tokens',
			'test_me_url'            => 'http://localhost:3000/me',
			'staging_token_url'      => 'https://staging-apollo.pet-records.com/oauth/token',
			'staging_me_url'         => 'https://staging-apollo.pet-records.com/me',
			'edge_token_url'         => 'https://edge-apollo.pet-records.com/oauth/token',
			'edge_me_url'            => 'https://edge-apollo.pet-records.com/me',
			'production_token_url'   => 'https://apollo.pet-records.com/oauth/token',
			'production_me_url'      => 'https://apollo.pet-records.com/me',
		);
		add_option( 'doorknob_options', serialize( $this->options ) );

		$this->request = new Request();
	}

	public function tearDown()
	{
		delete_option( 'doorknob_options' );

		parent::tearDown();
	}

	public function test_request_can_be_instantiated()
	{
		$this->assertInstanceOf( 'PawsPlus\Doorknob\Connection\Request', $this->request );
	}

	public function test_environment()
	{
		$environment = $this->request->environment();

		$this->assertSame( $environment, 'test' );
	}

	public function test_token_url()
	{
		$token_url = $this->request->token_url();
		$this->assertSame( $token_url, $this->options['test_token_url'] );

		$this->options['environment'] = 'staging';
		$this->options['staging_token_url'] = 'https://somewebsite.fart';
		update_option( 'doorknob_options', serialize( $this->options ) );

		$new_request = new Request();
		$token_url = $new_request->token_url();
		$this->assertSame( $token_url, $this->options['staging_token_url'] );
	}

	public function test_me_url()
	{
		$me_url = $this->request->me_url();
		$this->assertSame( $me_url, $this->options['test_me_url'] );

		$this->options['environment'] = 'staging';
		$this->options['staging_me_url'] = 'https://somewebsite.fart';
		update_option( 'doorknob_options', serialize( $this->options ) );

		$new_request = new Request();
		$me_url = $new_request->me_url();
		$this->assertSame( $me_url, $this->options['staging_me_url'] );
	}

}
