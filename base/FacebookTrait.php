<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

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
            craft()->request->redirect($url);
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
            $provider = craft()->oauth->getProvider('facebook');

            if ($provider && $provider->isConfigured())
            {
                $token = craft()->facebook_oauth->getToken();

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
        $plugin = craft()->plugins->getPlugin('facebook');

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
     * Get Dependency
     */
    private function getDependency($dependency)
    {
        $isMissing = true;
        $isInstalled = true;

        $plugin = craft()->plugins->getPlugin($dependency['handle'], false);

        if($plugin)
        {
            $currentVersion = $plugin->version;


            // requires update ?

            if(version_compare($currentVersion, $dependency['version']) >= 0)
            {
                // no (requirements OK)

                if($plugin->isInstalled && $plugin->isEnabled)
                {
                    $isMissing = false;
                }
            }
            else
            {
                // yes (requirement not OK)
            }
        }
        else
        {
            // not installed
        }

        $dependency['isMissing'] = $isMissing;
        $dependency['plugin'] = $plugin;
        $dependency['pluginLink'] = 'https://dukt.net/craft/'.$dependency['handle'];

        return $dependency;
    }
}
