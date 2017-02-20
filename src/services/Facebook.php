<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use dukt\facebook\Plugin as FacebookPlugin;

use yii\base\Component;

class Facebook extends Component
{
    // Public Methods
    // =========================================================================

    public function checkPluginRequirements()
    {
        $provider = FacebookPlugin::$plugin->getOauth()->getOauthProvider();

        if ($provider)
        {
            $oauthProviderOptions = Craft::$app->getConfig()->get('oauthProviderOptions', 'facebook');

            if(!empty($oauthProviderOptions['clientId']) && !empty($oauthProviderOptions['clientSecret']))
            {
                $token = FacebookPlugin::$plugin->getOauth()->getToken();

                if($token)
                {
                    return true;
                }
            }
        }

        return false;
    }
}
