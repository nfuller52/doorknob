<?php

/**
* An interface to accessing the Apollo API
*
* @category Models
*/

namespace PawsPlus\Doorknob\Models;

use PawsPlus\Doorknob\Models\Options as Options;
use PawsPlus\Doorknob\Support\Error as Error;
use PawsPlus\Doorknob\Services\Http as Http;

class Apollo
{

	/** @var Options */
	private $options;

	/** @var Http */
	private $http;

	/**
	* Constructor method
	*
	* @param PawsPlus\Doorknob\DoorknobOptions $options
	* @param PawsPlus\Doorknob\Services\Http   $options
	*
	* @return void
	*/
	public function __construct( Options $options, Http $http )
	{
		$this->options = $options;
		$this->http    = $http;
	}

	/**
	* Fetch access and refresh tokens via grant type of password
	* using a username and password combination
	*
	* @param string $username most likely the users email
	* @param string $password the same users password
	*
	* @return array standardized response from Apollo
	* @return error if connection was not successful or unauthorized
	*/
	public function request_tokens( $username, $password )
	{
		if ( $this->invalid_username_and_password( $username, $password ) ) return Error::invalid_credentials();

		$params = array(
			'body' => array(
				'username' => $username,
				'password' => $password,
				'grant_type' => 'password'
			)
		);

		return $this->http->post( $this->options->token_url(), $params );
	}

	/**
	* Fetch the user meta from Apollo once authenticated
	*
	* @param array $access_token
	*
	* @return array standardized response from Apollo
	* @return error if connection was not successful or unauthorized
	*/
	public function user( $access_token )
	{
		if ( empty( $access_token ) ) return Error::blank_token();

		$params = $this->auth_token_params( $access_token );

		return $this->http->get( $this->options->me_url(), $params );
	}

	/**
	* Fetch new access and refresh tokens upon original expiration of
	* access token
	*
	* @param string $token refresh token
	*
	* @return array standardized response from Apollo
	* @return error if connection was not successful
	*/
	private function refresh_access_token( $refresh_token )
	{
		if ( empty( $refresh_token ) ) return Error::blank_token();

		$params = $this->auth_token_params( $refresh_token );

		return $this->http->get( $this->options->token_url(), $params );
	}

	/**
	* Verify that the username and password are not null, false or empty strings
	*
	* @param string $username
	* @param string $password
	*
	* @return boolean
	*/
	private function invalid_username_and_password( $username, $password )
	{
		return ( empty( $username ) || empty( $password ) );
	}

	/**
	* Form the appropriate http options array for making requests to the server
	*
	* @param string $token
	*
	* @return array with properly formed headers array for making requests
	*/
	private function auth_token_params( $token )
	{
		return array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token
			)
		);
	}

}
