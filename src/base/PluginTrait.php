<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\base;

use dukt\facebook\Plugin as Facebook;

trait PluginTrait
{
    /**
     * Returns the facebook service.
     *
     * @return \dukt\facebook\services\Facebook The config service
     */
    public function getFacebook()
    {
        /** @var Facebook $this */
        return $this->get('facebook');
    }

    /**
     * Returns the api service.
     *
     * @return \dukt\facebook\services\Api The config service
     */
    public function getApi()
    {
        /** @var Facebook $this */
        return $this->get('api');
    }

    /**
     * Returns the cache service.
     *
     * @return \dukt\facebook\services\Cache The config service
     */
    public function getCache()
    {
        /** @var Facebook $this */
        return $this->get('cache');
    }

    /**
     * Returns the oauth service.
     *
     * @return \dukt\facebook\services\Oauth The config service
     */
    public function getOauth()
    {
        /** @var Facebook $this */
        return $this->get('oauth');
    }

    /**
     * Returns the reports service.
     *
     * @return \dukt\facebook\services\Reports The config service
     */
    public function getReports()
    {
        /** @var Facebook $this */
        return $this->get('reports');
    }
}
