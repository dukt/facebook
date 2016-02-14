<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_SettingsController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * Settings
     *
     * @return null
     */
    public function actionIndex()
    {
        $plugin = craft()->plugins->getPlugin('facebook');
        $pluginDependencies = $plugin->getPluginDependencies();

        if (count($pluginDependencies) > 0)
        {
            $this->renderTemplate('facebook/settings/_dependencies', ['pluginDependencies' => $pluginDependencies]);
        }
        else
        {
            if (isset(craft()->oauth))
            {
                $variables = array(
                    'provider' => false,
                    'account' => false,
                    'token' => false,
                    'error' => false
                );

                $provider = craft()->oauth->getProvider('facebook');

                if ($provider && $provider->isConfigured())
                {
                    $token = craft()->facebook_oauth->getToken();

                    if ($token)
                    {
                        try
                        {
                            $account = craft()->facebook_cache->get(['getAccount', $token]);

                            if(!$account)
                            {
                                $account = $provider->getAccount($token);
                                craft()->facebook_cache->set(['getAccount', $token], $account);
                            }

                            if ($account)
                            {

                                $variables['account'] = $account;

                                $variables['settings'] = $plugin->getSettings();
                            }
                        }
                        catch(\Exception $e)
                        {
                            Craft::log("Facebook.Debug - Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), LogLevel::Info, true);

                            if(method_exists($e, 'getResponse'))
                            {
                                Craft::log("Facebook.Debug.GuzzleErrorResponse\r\n".$e->getResponse(), LogLevel::Info, true);
                            }

                            $variables['error'] = $e->getMessage();
                        }
                    }

                    $variables['token'] = $token;
                    $variables['provider'] = $provider;
                }

                $this->renderTemplate('facebook/settings', $variables);
            }
            else
            {
                $this->renderTemplate('facebook/settings/_oauthNotInstalled');
            }
        }
    }
}
