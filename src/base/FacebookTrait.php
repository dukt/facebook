<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\base;

use Craft;
use dukt\facebook\Plugin as Facebook;

trait FacebookTrait
{
    // Public Methods
    // =========================================================================

    /**
     * Checks dependencies and redirects to install if one or more are missing
     */
    public function requireDependencies()
    {
        if(!$this->checkDependencies())
        {
            $url = UrlHelper::getUrl('facebook/install');
            Craft::$app->request->redirect($url);
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Checks dependencies
     */
    public function checkDependencies()
    {
        $missingDependencies = $this->getMissingDependencies();

        if(count($missingDependencies) > 0)
        {
            return false;
        }

        return true;
    }

    public function checkPluginRequirements()
    {
        if($this->checkDependencies())
        {
            $provider = \dukt\oauth\Plugin::getInstance()->oauth->getProvider('facebook');

            if ($provider && $provider->isConfigured())
            {
                $token = Facebook::$plugin->facebook_oauth->getToken();

                if($token)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /**
     * Get Missing Dependencies
     */
    public function getMissingDependencies()
    {
        return $this->getDependencies(true);
    }

    // Private Methods
    // =========================================================================

    /**
     * Get Dependencies
     */
    private function getDependencies($missingOnly = false)
    {
        $plugin = Craft::$app->plugins->getPlugin('facebook');

        $dependencies = array();

        $requiredPlugins = $plugin->getRequiredPlugins();

        foreach($requiredPlugins as $key => $requiredPlugin)
        {
            $dependency = $this->getDependency($requiredPlugin);

            if($missingOnly)
            {
                if($dependency['isMissing'])
                {
                    $dependencies[] = $dependency;
                }
            }
            else
            {
                $dependencies[] = $dependency;
            }
        }

        return $dependencies;
    }


    /**
     * Get dependency
     *
     * @return array
     */
    private function getDependency($dependency)
    {
        $isMissing = true;

        $plugin = Craft::$app->plugins->getPlugin($dependency['handle'], false);

        if($plugin)
        {
            $currentVersion = $plugin->version;

            if(version_compare($currentVersion, $dependency['version']) >= 0)
            {
                $allPluginInfo = Craft::$app->plugins->getAllPluginInfo();

                if(isset($allPluginInfo[$dependency['handle']]))
                {
                    $pluginInfos = $allPluginInfo[$dependency['handle']];

                    if($pluginInfos['isInstalled'] && $pluginInfos['isEnabled'])
                    {
                        $isMissing = false;
                    }
                }
            }
        }

        $dependency['isMissing'] = $isMissing;
        $dependency['plugin'] = $plugin;
        $dependency['pluginLink'] = 'https://dukt.net/craft/'.$dependency['handle'];

        return $dependency;
    }
}
