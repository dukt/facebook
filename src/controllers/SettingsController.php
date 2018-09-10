<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\controllers;

use Craft;
use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;
use yii\web\Response;

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
     * Settings index.
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        $token = Facebook::$plugin->getOauth()->getToken();

        if ($token) {
            try {
                $account = Facebook::$plugin->getCache()->get(['getResourceOwner', $token]);

                if (!$account) {
                    $provider = Facebook::$plugin->getOauth()->getOauthProvider();
                    $account = $provider->getResourceOwner($token);
                    Facebook::$plugin->getCache()->set(['getResourceOwner', $token], $account);
                }
            } catch (\Exception $e) {
                Craft::info("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), __METHOD__);

                if (method_exists($e, 'getResponse')) {
                    Craft::info("GuzzleErrorResponse\r\n".$e->getResponse(), __METHOD__);
                }

                $error = $e->getMessage();
            }
        }

        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');

        return $this->renderTemplate('facebook/settings/index', [
            'account' => (isset($account) ? $account : null),
            'token' => (isset($token) ? $token : null),
            'error' => (isset($error) ? $error : null),
            'settings' => $plugin->getSettings(),
            'redirectUri' => Facebook::$plugin->oauth->getRedirectUri(),
        ]);
    }

    /**
     * OAuth settings.
     *
     * @return Response
     */
    public function actionOauth(): Response
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');

        return $this->renderTemplate('facebook/settings/oauth', [
            'redirectUri' => Facebook::$plugin->oauth->getRedirectUri(),
            'settings' => $plugin->getSettings(),
        ]);
    }

    /**
     * Save OAuth settings.
     *
     * @return Response
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSaveOauthSettings(): Response
    {
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');
        $settings = $plugin->getSettings();

        $postSettings = Craft::$app->getRequest()->getBodyParam('settings');

        $settings['oauthClientId'] = $postSettings['oauthClientId'];
        $settings['oauthClientSecret'] = $postSettings['oauthClientSecret'];

        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->getAttributes());

        Craft::$app->getSession()->setNotice(Craft::t('facebook', 'OAuth settings saved.'));

        return $this->redirectToPostedUrl();
    }
}
