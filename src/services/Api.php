<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\services;

use Craft;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
        try {
            $client = $this->getClient();

            $options['query'] = ($query ? $query : []);

            if ($headers) {
                $options['headers'] = $headers;
            }

            $response = $client->request('GET', $uri, $options);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Craft::error('Error requesting Facebook’s API: '.$e->getResponse()->getBody(), __METHOD__);
            throw $e;
        }
    }

    /**
     * @param       $facebookInsightsObjectId
     * @param array $options
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInsights($facebookInsightsObjectId, array $options = [])
    {
        try {
            $pageAccessTokenResponse = $this->get('/'.$facebookInsightsObjectId, ['fields' => 'access_token']);

            if(empty($pageAccessTokenResponse['access_token'])) {
                throw new \Exception('Couldn’t retrieve page access token for '.$facebookInsightsObjectId);
            }

            $client = $this->getClient($pageAccessTokenResponse['access_token']);

            $response = $client->request('GET', '/'.$facebookInsightsObjectId.'/insights', $options);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            Craft::error('Error requesting insights: '.$e->getResponse()->getBody(), __METHOD__);
            throw $e;
        }
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
