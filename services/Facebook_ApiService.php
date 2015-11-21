<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

use Facebook;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

class Facebook_ApiService extends BaseApplicationComponent
{
    private $apiUrl = 'https://graph.facebook.com/v2.5/';

    public function get($uri = null, $query = null, $headers = null)
    {
        $options['query'] = ($query ? $query : []);

        return $this->request('get', $uri, $headers, $options);
    }

    private function request($method='get', $uri = null, $headers = null, $options = [])
    {
        try
        {
            $client = $this->getClient();

            $request = $client->{$method}($uri, $headers, $options);

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
        catch (RequestException $e)
        {
            $exceptionResponse = $e->getResponse();

            $data = json_decode($exceptionResponse->getBody(), true);

            return [
                'success' => false,
                'error' => true,
                'data' => $data,
                'exception' => $e,
            ];
        }
    }

    private function getClient()
    {
        $token = craft()->facebook_oauth->getToken();

        $client = new Client($this->apiUrl, [
            'request.options' => [
                'headers' => ['Authorization' => 'Bearer '.$token->accessToken]
            ]
        ]);

        return $client;
    }
}
