<?php

/**
* An interface to accessing the Apollo API
*
* @category Apollo
*/

namespace PawsPlus\Doorknob\ExternalServices;

use PawsPlus\Doorknob\DoorknobOptions as Options;
use PawsPlus\Doorknob\Errors\Error as Error;
use PawsPlus\Doorknob\ExternalServices\HttpRequest as HttpRequest;
use PawsPlus\Doorknob\Models\ApolloUser as ApolloUser;

class Apollo
{

	/** @var Options */
	private $options;

	/** @var HttpRequest */
	private $request;

	/**
	* Constructor method
	*
	* @param PawsPlus\Doorknob\DoorknobOptions $options
	*
	* @return voide
	*/
	public function __construct( Options $options, HttpRequest $request )
	{
		$this->options = $options;
		$this->request = $request;
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
	* Fetch the user meta from Apollo once authenticated
	*
	* @param array $token_items 3 items, access_token, refresh_token and expires_in
	*
	* @return array standardized response from Apollo
	* @return error if connection was not successful or unauthorized
	*/
	public function user( $username, $password )
	{
		if ( empty( $token_items ) ) return Error::invalid_credentials();

		$params = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token_items
			)
		);

		$response = $this->request( 'GET', $this->options->me_url(), $params );

		return $response;
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
		if ( empty( $refresh_token ) ) return Error::blank_token();

		$params = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $refresh_token
			)
		);

		return $this->request( 'GET', $this->options->token_url(), $params );
	}

	/**
	* Make a request to the Apollo web service.
	*
	* @param string $http_method a valid http protocol
	* @param string $url         the url being requested
	* @param array  $params      http options and/or parameters
	*
	* @return array standardized response from Apollo
	* @return error if connection was not successful
	*/
	public function request( $http_method, $url, array $params )
	{
		$params['method']  = strtoupper( $http_method );
		$params['timeout'] = $this->options->timeout();

		$response = \wp_remote_request( $url, $params );

		if ( is_wp_error( $response ) ) {
			return Error::failed_connection( $response->get_error_message() );
		} elseif ( $response['response']['code'] === 401 ) {
			return Error::invalid_credentials();
		} else {
			return $this->map_response( $response );
		}
	}

	/**
	* Map a response from the wordpress function wp_remote_request.
	* Standardize API response into a useable array. This method should
	* act as a funnel for all responses from Apollo.
	*
	* @param array $response a response object from wp_remote_request
	*
	* @return array standardized response from Apollo
	*/
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
