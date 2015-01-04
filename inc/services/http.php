<?php

/**
* An interface for requesting HTTP services
*
* @category Services
*/

namespace PawsPlus\Doorknob\Services;

class Http
{

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

}
