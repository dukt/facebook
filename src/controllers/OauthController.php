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
use Exception;

class OauthController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Connect
     *
     * @return null
     */
    public function actionConnect()
    {
        $provider = Facebook::$plugin->oauth->getOauthProvider();

        Craft::$app->getSession()->set('facebook.oauthState', $provider->getState());

        $options = Craft::$app->config->get('oauthAuthorizationOptions', 'facebook');
        $options['scope'] = Craft::$app->config->get('oauthScope', 'facebook');

        $authorizationUrl = $provider->getAuthorizationUrl($options);

        return $this->redirect($authorizationUrl);
    }

    /**
     * Callback
     *
     * @return null
     */
    public function actionCallback()
    {
        $provider = Facebook::$plugin->oauth->getOauthProvider();

        $code = Craft::$app->request->getParam('code');

        try {
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Save token
            Facebook::$plugin->oauth->saveToken($token);

            // Reset session variables

            // Redirect
            Craft::$app->getSession()->setNotice(Craft::t('facebook', "Connected to Google Analytics."));

        } catch (Exception $e) {
            // Failed to get the token credentials or user details.
            Craft::$app->getSession()->setError($e->getMessage());
        }

        return $this->redirect('facebook/settings');
    }

    /**
     * Disconnect
     *
     * @return null
     */
    public function actionDisconnect()
    {
        if (Facebook::$plugin->oauth->deleteToken())
        {
            Craft::$app->getSession()->setNotice(Craft::t('app', "Disconnected from Facebook."));
        }
        else
        {
            Craft::$app->getSession()->setError(Craft::t('app', "Couldn’t disconnect from Facebook"));
        }

        // redirect
        $redirect = Craft::$app->request->referrer;
        return $this->redirect($redirect);
    }
}
