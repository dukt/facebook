<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class FacebookVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Request the API.
     */
    public function get($uri, $queryParams = null, $accessToken = null)
    {
        return craft()->facebook_api->get($uri, $queryParams, $accessToken);
    }
}