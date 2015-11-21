<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class FacebookController extends BaseController
{
    // Properties
    // =========================================================================

    /**
     * @var string
     */
    private $oauthProvider = 'facebook';


    // Public Methods
    // =========================================================================

    public function actionIndex()
    {
        $this->renderTemplate('facebook/index');
    }

    /**
     * Connect
     *
     * @return null
     */
    public function actionConnect()
    {
        // referer

        $referer = craft()->httpSession->get('facebook.referer');

        if (!$referer)
        {
            $referer = craft()->request->getUrlReferrer();

            craft()->httpSession->add('facebook.referer', $referer);
        }

        Craft::log('Facebook Connect - Step 1'."\r\n".print_r([
                'referer' => $referer,
            ], true), LogLevel::Info, true);


        // connect

        if ($response = craft()->oauth->connect(array(
            'plugin'   => 'facebook',
            'provider' => $this->oauthProvider,
            'scopes'   => craft()->config->get('oauthScope', 'facebook'),
            'params'   => craft()->config->get('oauthParams', 'facebook')
        )))
        {
            if ($response['success'])
            {
                // token
                $token = $response['token'];

                // save token
                craft()->facebook_oauth->saveToken($token);

                Craft::log('Facebook Connect - Step 2'."\r\n".print_r([
                        'token' => $token,
                    ], true), LogLevel::Info, true);

                // session notice
                craft()->userSession->setNotice(Craft::t("Connected to Facebook."));
            }
            else
            {
                // session error
                craft()->userSession->setError(Craft::t($response['errorMsg']));
            }
        }
        else
        {
            // session error
            craft()->userSession->setError(Craft::t("Couldn’t connect"));
        }

        // OAuth Step 5

        // redirect

        craft()->httpSession->remove('facebook.referer');

        $this->redirect($referer);
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        if (craft()->facebook_oauth->deleteToken())
        {
            craft()->userSession->setNotice(Craft::t("Disconnected from Facebook."));
        }
        else
        {
            craft()->userSession->setError(Craft::t("Couldn’t disconnect from Facebook"));
        }

        // redirect
        $redirect = craft()->request->getUrlReferrer();
        $this->redirect($redirect);
    }


    /**
     * Settings
     *
     * @return null
     */
    public function actionSettings()
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
                                // $variables['propertiesOpts'] = $propertiesOpts;
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

                            // Craft::log('Couldn’t get account. '.$e->getMessage(), LogLevel::Error);

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
