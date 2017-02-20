<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use Craft;
use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;

class SettingsController extends Controller
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
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');

        $variables = array(
            'provider' => false,
            'account' => false,
            'token' => false,
            'error' => false
        );

        $provider = Facebook::$plugin->oauth->getOauthProvider();

        if ($provider)
        {
            $token = Facebook::$plugin->oauth->getToken();

            if ($token)
            {
                try
                {
                    $account = Facebook::$plugin->cache->get(['getResourceOwner', $token]);

                    if(!$account)
                    {
                        $account = $provider->getResourceOwner($token);
                        Facebook::$plugin->cache->set(['getResourceOwner', $token], $account);
                    }

                    if ($account)
                    {
                        $variables['account'] = $account;
                        $variables['settings'] = $plugin->getSettings();
                    }
                }
                catch(\Exception $e)
                {
                    // FacebookPlugin::log("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), LogLevel::Error);

                    if(method_exists($e, 'getResponse'))
                    {
                        // FacebookPlugin::log("GuzzleErrorResponse\r\n".$e->getResponse(), LogLevel::Error);
                    }

                    $variables['error'] = $e->getMessage();
                }
            }

            $variables['token'] = $token;
            $variables['provider'] = $provider;
        }

        return $this->renderTemplate('facebook/settings/_index', $variables);
    }
}
