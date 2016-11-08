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
                'version' => '2.0.2'
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
        return '1.1.1';
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
            'facebook/install' => array('action' => "facebook/install/index"),
            'facebook/settings' => array('action' => "facebook/settings/index"),
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
}
