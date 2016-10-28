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
        craft()->facebook->requireDependencies();

        $plugin = craft()->plugins->getPlugin('facebook');

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
                    $account = craft()->facebook_cache->get(['getResourceOwner', $token]);

                    if(!$account)
                    {
                        $account = $provider->getResourceOwner($token);
                        craft()->facebook_cache->set(['getResourceOwner', $token], $account);
                    }

                    if ($account)
                    {
                        $variables['account'] = $account;
                        $variables['settings'] = $plugin->getSettings();
                    }
                }
                catch(\Exception $e)
                {
                    FacebookPlugin::log("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), LogLevel::Error);

                    if(method_exists($e, 'getResponse'))
                    {
                        FacebookPlugin::log("GuzzleErrorResponse\r\n".$e->getResponse(), LogLevel::Error);
                    }

                    $variables['error'] = $e->getMessage();
                }
            }

            $variables['token'] = $token;
            $variables['provider'] = $provider;
        }

        $this->renderTemplate('facebook/settings', $variables);
    }
}
