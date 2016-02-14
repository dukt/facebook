<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_PluginService extends BaseApplicationComponent
{
    /**
     * Require dependencies
     *
     * @return bool
     */
    public function requireDependencies()
    {
        $plugin = craft()->plugins->getPlugin('facebook');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $url = UrlHelper::getUrl('facebook/settings');
            craft()->request->redirect($url);
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * Check requirements
     *
     * @return bool
     */
    public function checkRequirements($redirect = false)
    {
        // dependencies
        $plugin = craft()->plugins->getPlugin('facebook');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            if($redirect)
            {
                $url = UrlHelper::getUrl('facebook/settings');
                craft()->request->redirect($url);
            }

            return false;
        }
        else
        {
            // oauth
            $provider = craft()->oauth->getProvider('google');

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
                if($redirect)
                {
                    $url = UrlHelper::getUrl('facebook/settings');
                    craft()->request->redirect($url);
                }

                return false;
            }
        }
    }
}
