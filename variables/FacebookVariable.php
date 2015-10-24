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

    public function api()
    {
        return craft()->facebook_api;
    }
}