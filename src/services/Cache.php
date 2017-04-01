<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use yii\base\Component;
use DateInterval;
use dukt\facebook\Plugin as Facebook;


/**
 * Class Cache service
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Cache extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Retrieves a value from cache with a specified key.
     *
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        if(Facebook::$plugin->getSettings()->enableCache == true)
        {
            $cacheKey = $this->getCacheKey($id);

            return Craft::$app->cache->get($cacheKey);
        }
    }

    /**
     * Stores a value identified by a key into cache.
     *
     * @param      $id
     * @param      $value
     * @param null $expire
     * @param null $dependency
     * @param null $enableCache
     *
     * @return bool
     */
    public function set($id, $value, $expire = null, $dependency = null, $enableCache = null)
    {
        if(is_null($enableCache))
        {
            $enableCache = Facebook::$plugin->getSettings()->enableCache;
        }

        if($enableCache)
        {
            $cacheKey = $this->getCacheKey($id);

            if(!$expire)
            {
                $expire = Facebook::$plugin->getSettings()->cacheDuration;
                $expire = $this->_formatDuration($expire);
            }

            return Craft::$app->cache->set($cacheKey, $value, $expire, $dependency);
        }
    }

    // Private Methods
    // =========================================================================

    /**
     * Formats duration.
     *
     * @param        $cacheDuration
     * @param string $format
     *
     * @return string
     */
    private function _formatDuration($cacheDuration, $format='%s')
    {
        $cacheDuration = new DateInterval($cacheDuration);

        return $cacheDuration->format($format);
    }

    /**
     * Return the cache key.
     *
     * @param array $request
     *
     * @return string
     */
    private function getCacheKey(array $request)
    {
        $dataSourceClassName = 'Facebook';

        unset($request['CRAFT_CSRF_TOKEN']);

        $request[] = $dataSourceClassName;

        $hash = md5(serialize($request));

        return 'facebook.'.$hash;
    }
}
