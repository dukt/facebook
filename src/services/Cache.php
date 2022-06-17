<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\services;

use Craft;
use yii\base\Component;
use DateInterval;
use dukt\facebook\Plugin as Facebook;
use yii\caching\Dependency;


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
        if (Facebook::$plugin->getSettings()->enableCache == true) {
            $cacheKey = $this->getCacheKey($id);

            return Craft::$app->cache->get($cacheKey);
        }
    }

    /**
     * Stores a value identified by a key into cache.
     *
     * @param array $id
     * @param mixed $value
     * @param int|null $expire
     * @param Dependency|null $dependency
     * @param bool|null $enableCache
     *
     * @return bool
     * @throws \Exception
     */
    public function set(array $id, mixed $value, int $expire = null, Dependency $dependency = null, bool $enableCache = null)
    {
        if (is_null($enableCache)) {
            $enableCache = Facebook::$plugin->getSettings()->enableCache;
        }

        if ($enableCache) {
            $cacheKey = $this->getCacheKey($id);

            if (!$expire) {
                $expire = Facebook::$plugin->getSettings()->cacheDuration;
                $expire = $this->_formatDuration($expire);
            }

            return Craft::$app->cache->set($cacheKey, $value, $expire, $dependency);
        }

        return false;
    }

    // Private Methods
    // =========================================================================
    /**
     * Formats duration.
     *
     * @param        $cacheDuration
     *
     * @return string
     * @throws \Exception
     */
    private function _formatDuration($cacheDuration, string $format = '%s')
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
