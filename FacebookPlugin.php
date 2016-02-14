<?php

namespace Craft;

class FacebookPlugin extends BasePlugin
{
    // Public Methods
    // =========================================================================

    /**
     * Get Required Plugins
     */
    public function getRequiredPlugins()
    {
        return array(
            array(
                'name' => "OAuth",
                'handle' => 'oauth',
                'url' => 'https://dukt.net/craft/oauth',
                'version' => '1.0.0'
            )
        );
    }

    /**
     * Get Name
     */
    public function getName()
    {
        return Craft::t('Facebook');
    }

    /**
     * Get Description
     */
    public function getDescription()
    {
        return Craft::t('Facebook Insights widget for the dashboard.');
    }

    /**
     * Get Version
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Get Developer
     */
    public function getDeveloper()
    {
        return 'Dukt';
    }

    /**
     * Get Developer URL
     */
    public function getDeveloperUrl()
    {
        return 'https://dukt.net/';
    }

    /**
     * Get Settings URL
     */
    public function getSettingsUrl()
    {
        return 'facebook/settings';
    }

    /**
     * Hook Register CP Routes
     */
    public function registerCpRoutes()
    {
        return array(
            'facebook' => array('action' => "facebook/index"),
            'facebook\/settings' => array('action' => "facebook/settings/index"),
        );
    }

    /**
     * On Before Uninstall
     */
    public function onBeforeUninstall()
    {
        if(isset(craft()->oauth))
        {
            craft()->oauth->deleteTokensByPlugin('facebook');
        }
    }

    /**
     * Get Plugin Dependencies
     */
    public function getPluginDependencies($missingOnly = true)
    {
        $dependencies = array();

        $plugins = $this->getRequiredPlugins();

        foreach($plugins as $key => $plugin)
        {
            $dependency = $this->getPluginDependency($plugin);

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

    // Protected Methods
    // =========================================================================

    /**
     * Defined Settings
     */
    protected function defineSettings()
    {
        return array(
            'tokenId' => array(AttributeType::Number),
            'facebookInsightsObjectId' => array(AttributeType::String),
        );
    }

    /**
     * Get Plugin Dependency
     */
    private function getPluginDependency($dependency)
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

        return $dependency;
    }
}
