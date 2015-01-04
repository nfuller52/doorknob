<?php

/**
* An interface to accessing the Plugin Options
*
* @category Models
*/

namespace PawsPlus\Doorknob\Models;

class Options
{
	/** @var private options */
	private $options;

	/**
	* Constructor method
	*
	* @return void
	*/
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

	/**
	* Return the timeout value requests will stick around for
	*
	* @return integer
	*/
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
