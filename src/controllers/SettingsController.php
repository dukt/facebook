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

/**
 * Class SettingsController
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class SettingsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Settings
     *
     * @return string
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

        $provider = Facebook::$plugin->getOauth()->getOauthProvider();

        if ($provider)
        {
            $token = Facebook::$plugin->getOauth()->getToken();

            if ($token)
            {
                try
                {
                    $account = Facebook::$plugin->getCache()->get(['getResourceOwner', $token]);

                    if(!$account)
                    {
                        $account = $provider->getResourceOwner($token);
                        Facebook::$plugin->getCache()->set(['getResourceOwner', $token], $account);
                    }

                    if ($account)
                    {
                        $variables['account'] = $account;
                        $variables['settings'] = $plugin->getSettings();
                    }
                }
                catch(\Exception $e)
                {
                    Craft::trace("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), __METHOD__);

                    if(method_exists($e, 'getResponse'))
                    {
                        Craft::trace("GuzzleErrorResponse\r\n".$e->getResponse(), __METHOD__);
                    }

                    $variables['error'] = $e->getMessage();
                }
            }

            $variables['token'] = $token;
            $variables['provider'] = $provider;
        }

        $variables['redirectUri'] = Facebook::$plugin->oauth->getRedirectUri();
        $variables['oauthProviderOptions'] = Craft::$app->getConfig()->get('oauthProviderOptions', 'facebook');

        return $this->renderTemplate('facebook/settings/_index', $variables);
    }
}
