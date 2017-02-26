<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use yii\base\Component;
use DateInterval;

/**
 * Class Cache
 *
 * @package dukt\facebook\services
 */
class Cache extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * @param $id
     *
     * @return mixed
     */
    public function get($id)
    {
        if(Craft::$app->getConfig()->get('enableCache', 'facebook') == true)
        {
            $cacheKey = $this->getCacheKey($id);

            return Craft::$app->cache->get($cacheKey);
        }
    }

    /**
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
            $enableCache = Craft::$app->getConfig()->get('enableCache', 'facebook');
        }

        if($enableCache)
        {
            $cacheKey = $this->getCacheKey($id);

            if(!$expire)
            {
                $expire = Craft::$app->getConfig()->get('cacheDuration', 'facebook');
                $expire = $this->_formatDuration($expire);
            }

            return Craft::$app->cache->set($cacheKey, $value, $expire, $dependency);
        }
    }

    // Private Methods
    // =========================================================================

    /**
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
     * Return the cache key
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
