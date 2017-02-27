<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

use dukt\facebook\Plugin as Facebook;

/**
 * Class FacebookVariable
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class FacebookVariable
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the API service object.
     *
     * @return Api
     */
    public function api()
    {
        return Facebook::$plugin->getApi();
    }
}