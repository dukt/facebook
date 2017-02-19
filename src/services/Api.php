<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use GuzzleHttp\Client;
use Guzzle\Http\Exception\RequestException;
use yii\base\Component;
use dukt\facebook\Plugin as Facebook;

class Api extends Component
{
	// Properties
	// =========================================================================

	private $baseApiUrl = 'https://graph.facebook.com/';
	
	// Public Methods
	// =========================================================================

    public function get($uri = null, $query = null, $headers = null)
    {
        $client = $this->getClient();

        $options['query'] = ($query ? $query : []);

        if($headers)
        {
            $options['headers'] = $headers;
        }

        $response = $client->request('GET', $uri, $options);

        $jsonResponse = json_decode($response->getBody(), true);

        return $jsonResponse;
    }

	// Private Methods
	// =========================================================================

	private function getApiUrl()
	{
		$apiVersion = Craft::$app->config->get('apiVersion', 'facebook');

		return $this->baseApiUrl.$apiVersion.'/';
	}

    private function getClient()
    {
        $token = Facebook::$plugin->oauth->getToken();

        $headers = array();

        if($token)
        {

            $headers['Authorization'] = 'Bearer '.$token->getToken();
        }

/*        $client = new Client($this->getApiUrl(), [
            'request.options' => [
                'headers' => $headers
            ]
        ]);
        */
        $options = [
            'base_uri' => $this->getApiUrl(),
            'headers' => $headers
        ];

        return new Client($options);
    }
}
