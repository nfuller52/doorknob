<?php

namespace PawsPlus\Doorknob;

class DoorknobOptions
{
	/** @var private options */
	private $options;

	public function __construct()
	{
		$this->options = get_option( 'doorknob_options' );
	}

	/**
	* Return the current environment setting for the plugin
	*
	* @return string
	*/
	public function environment()
	{
		return $this->options['environment'];
	}

	/**
	* Returns the token request url string
	*
	* @return string
	*/
	public function token_url()
	{
		return $this->url_type( 'token' );
	}

	/**
	* Return the user request url string
	*
	* @return string
	*/
	public function me_url()
	{
		return $this->url_type( 'me' );
	}

	public function timeout()
	{
		return $this->options['timeout'];
	}

	/**
	* Dynamically selects the appropriate key from the options array
	*
	* @param string $type 'me' || 'token'
	*
	* @return string
	*/
	private function url_type( $type )
	{
		$url = $this->environment() . '_' . strtolower( $type ) . '_url';
		return $this->options[$url];
	}

}
