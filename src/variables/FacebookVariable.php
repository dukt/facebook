<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class FacebookVariable
{
    // Public Methods
    // =========================================================================

    public function api()
    {
        return Facebook::$plugin->facebook_api;
    }
}