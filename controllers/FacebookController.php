<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
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
            'scope'   => craft()->config->get('oauthScope', 'facebook'),
            'authorizationOptions'   => craft()->config->get('oauthAuthorizationOptions', 'facebook')
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
}
