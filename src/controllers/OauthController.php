<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\controllers;

use Craft;
use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;
use Exception;
use yii\web\Response;

/**
 * Class OauthController
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class OauthController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * OAuth connect.
     *
     * @return Response
     */
    public function actionConnect(): Response
    {
        $provider = Facebook::$plugin->getOauth()->getOauthProvider();

        Craft::$app->getSession()->set('facebook.oauthState', $provider->getState());

        $options = Facebook::$plugin->getSettings()->oauthAuthorizationOptions;
        $options['scope'] = Facebook::$plugin->getSettings()->oauthScope;

        $authorizationUrl = $provider->getAuthorizationUrl($options);

        return $this->redirect($authorizationUrl);
    }

    /**
     * OAuth callback.
     *
     * @return Response
     */
    public function actionCallback(): Response
    {
        $provider = Facebook::$plugin->getOauth()->getOauthProvider();

        $code = Craft::$app->getRequest()->getParam('code');

        try {
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Save token
            Facebook::$plugin->getOauth()->saveToken($token);

            // Todo: Reset session variables

            // Redirect
            Craft::$app->getSession()->setNotice(Craft::t('facebook', "Connected to Facebook."));
        } catch (Exception $exception) {
            // Failed to get the token credentials or user details.
            Craft::$app->getSession()->setError($exception->getMessage());
        }

        return $this->redirect('facebook/settings');
    }

    /**
     * OAuth disconnect.
     *
     * @return Response
     */
    public function actionDisconnect(): Response
    {
        if (Facebook::$plugin->getOauth()->deleteToken()) {
            Craft::$app->getSession()->setNotice(Craft::t('facebook', "Disconnected from Facebook."));
        } else {
            Craft::$app->getSession()->setError(Craft::t('facebook', "Couldn’t disconnect from Facebook"));
        }

        // redirect
        $redirect = Craft::$app->getRequest()->referrer;

        return $this->redirect($redirect);
    }
}
