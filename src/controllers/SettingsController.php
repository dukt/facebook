<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
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
                Craft::trace("Couldn't get account\r\n".$e->getMessage().'\r\n'.$e->getTraceAsString(), __METHOD__);

                if (method_exists($e, 'getResponse')) {
                    Craft::trace("GuzzleErrorResponse\r\n".$e->getResponse(), __METHOD__);
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
     * @return string
     */
    public function actionOauth()
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
     * @return \yii\web\Response
     */
    public function actionSaveOauthSettings()
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
