<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use yii\base\Component;
use dukt\oauth\models\Token;

class Oauth extends Component
{
    // Properties
    // =========================================================================

    private $token;

	// Public Methods
	// =========================================================================

    /**
     * Save Token
     *
     * @param Token $token
     */
    public function saveToken(Token $token)
    {
        // get plugin
        $plugin = Craft::$app->plugins->getPlugin('facebook');

        // get settings
        $settings = $plugin->getSettings();


        // do we have an existing token ?

        $existingToken = \dukt\oauth\Plugin::getInstance()->oauth->getTokenById($settings->tokenId);

        if($existingToken)
        {
            $token->id = $existingToken->id;
        }

        // save token
        \dukt\oauth\Plugin::getInstance()->oauth->saveToken($token);

        // set token ID
        $settings->tokenId = $token->id;

        // save plugin settings
        Craft::$app->plugins->savePluginSettings($plugin, $settings->getAttributes());
    }

    /**
     * Get OAuth Token
     */
    public function getToken()
    {
        if($this->token)
        {
            return $this->token;
        }
        else
        {
            // get plugin
            $plugin = Craft::$app->plugins->getPlugin('facebook');

            // get settings
            $settings = $plugin->getSettings();

            // get tokenId
            $tokenId = $settings->tokenId;

            // get token
            $token = \dukt\oauth\Plugin::getInstance()->oauth->getTokenById($tokenId);

            return $token;
        }
    }

    /**
     * Delete Token
     */
    public function deleteToken()
    {
        // get plugin
        $plugin = Craft::$app->plugins->getPlugin('facebook');

        // get settings
        $settings = $plugin->getSettings();

        if($settings->tokenId)
        {
            $token = \dukt\oauth\Plugin::getInstance()->oauth->getTokenById($settings->tokenId);

            if($token)
            {
                if(\dukt\oauth\Plugin::getInstance()->oauth->deleteToken($token))
                {
                    $settings->tokenId = null;

                    Craft::$app->plugins->savePluginSettings($plugin, $settings);

                    return true;
                }
            }
        }

        return false;
    }
}
