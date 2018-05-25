<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use GuzzleHttp\Client;
use yii\base\Component;
use dukt\facebook\Plugin as Facebook;

/**
 * Class Api service
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Api extends Component
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    private $baseApiUrl = 'https://graph.facebook.com/';

    // Public Methods
    // =========================================================================

    /**
     * Performs an authenticated GET request on Facebook’s API
     *
     * @param null $uri
     * @param null $query
     * @param null $headers
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($uri = null, $query = null, $headers = null)
    {
        $client = $this->getClient();

        $options['query'] = ($query ? $query : []);

        if ($headers) {
            $options['headers'] = $headers;
        }

        $response = $client->request('GET', $uri, $options);

        return json_decode($response->getBody(), true);
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the Facebook API URL.
     *
     * @return string
     */
    private function getApiUrl()
    {
        $apiVersion = Facebook::$plugin->getSettings()->apiVersion;

        return $this->baseApiUrl.$apiVersion.'/';
    }

    /**
     * Return the authenticated Facebook OAuth client.
     *
     * @param null $accessToken
     *
     * @return Client
     */
    private function getClient($accessToken = null): Client
    {
        if(!$accessToken) {
            $token = Facebook::$plugin->getOauth()->getToken();
            $accessToken = $token->getToken();
        }

        $headers = [];

        if ($accessToken) {
            $headers['Authorization'] = 'Bearer '.$accessToken;
        }

        $options = [
            'base_uri' => $this->getApiUrl(),
            'headers' => $headers
        ];

        return new Client($options);
    }
}
