<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

use Facebook;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

class Facebook_ApiService extends BaseApplicationComponent
{
	// Properties
	// =========================================================================

	private $baseApiUrl = 'https://graph.facebook.com/';
	
	// Public Methods
	// =========================================================================

    public function get($uri = null, $query = null, $headers = null)
    {
        $options['query'] = ($query ? $query : []);

        $client = $this->getClient();

        $request = $client->get($uri, $headers, $options);

        $response = $request->send();

        try
        {
            $data = json_decode($response->getBody(), true);
        }
        catch(\Exception $e)
        {
            $data = null;
        }

        return [
            'success' => true,
            'error' => false,
            'data' => $data,
            'response' => $response
        ];
    }

	// Private Methods
	// =========================================================================

	private function getApiUrl()
	{
		$apiVersion = craft()->config->get('apiVersion', 'facebook');

		return $this->baseApiUrl.$apiVersion.'/';
	}

    private function getClient()
    {
        $token = craft()->facebook_oauth->getToken();

        $headers = array();

        if($token)
        {

            $headers['Authorization'] = 'Bearer '.$token->accessToken;
        }

        $client = new Client($this->getApiUrl(), [
            'request.options' => [
                'headers' => $headers
            ]
        ]);

        return $client;
    }
}
