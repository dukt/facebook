<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Facebook;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use yii\base\Component;

class Api extends Component
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

        $data = $response->json();

        return $data;
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
