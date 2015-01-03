<?php
namespace PawsPlus\Doorknob\Connection;

defined( 'ABSPATH' ) or die( "It's a trap!" );

class Request
{

	public function __construct()
	{
		$this->options = unserialize( get_option( 'doorknob_options' ) );
	}

	public function environment()
	{
		return $this->options['environment'];
	}

	public function token_url()
	{
		return $this->doorknob_option_url( 'token' );
	}

	public function me_url()
	{
		return $this->doorknob_option_url( 'me' );
	}

	private function doorknob_option_url( $type )
	{
		$url = $this->options['environment'] . '_' . strtolower( $type ) . '_url';
		return $this->options[$url];
	}

}
