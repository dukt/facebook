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
        $provider = FacebookPlugin::$plugin->oauth->getOauthProvider();

        if ($provider)
        {
            $oauthProviderOptions = Craft::$app->config->get('oauthProviderOptions', 'facebook');

            if(!empty($oauthProviderOptions['clientId']) && !empty($oauthProviderOptions['clientSecret']))
            {
                $token = FacebookPlugin::$plugin->oauth->getToken();

                if($token)
                {
                    return true;
                }
            }
        }

        return false;
    }
}
