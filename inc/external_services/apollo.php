<?php

/**
* An interface to accessing the Apollo API
*
* @category Apollo
*/

namespace PawsPlus\Doorknob\ExternalServices;

use PawsPlus\Doorknob\DoorknobOptions as Options;
use PawsPlus\Doorknob\Errors\Error as Error;

class Apollo
{

	/** @var Options */
	private $options = new Options();

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
		if ( empty( $username ) || empty( $password ) ) return Error::invalid_credentials();

		$params = array(
			'body' => array(
				'username' => $username,
				'password' => $password,
				'grant_type' => 'password'
			)
		);

		return $this->request( 'POST', $this->options->token_url(), $params );
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
	public function refresh_access_token( $refresh_token )
	{
		if ( empty( $token ) ) return Error::blank_token();

		$params = array(
			'body' => array(
			),
			'headers' => array(
				'Authorization' => 'Bearer ' . $refresh_token
			)
		);

		return $this->request( 'GET', $this->options->token_url(), $params );
	}

	private function request( $http_method, $url, array $params )
	{
		$params['method']  = strtoupper( $http_method );
		$params['timeout'] = $this->options->timeout();

		$response = wp_remote_reqeust( $url, $params );

		if ( is_wp_error( $response ) ) {
			return Error::failed_onnection( $response->get_error_message() );
		} elseif ( $response['response']['code'] === 401 ) {
			return Error::invalid_credentials();
		} else {
			return $this->map_response( $response );
		}
	}

	private function map_response( $response )
	{
		$payload = array();
		$payload['body']    = json_decode( $response['body'] );
		$payload['headers'] = $response['headers'];
		$payload['code']    = $response['response']['code'];
		$payload['message'] = $response['response']['message'];

		return $payload;
	}

}
