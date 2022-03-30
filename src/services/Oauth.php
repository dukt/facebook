<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\services;

use craft\helpers\Json;
use craft\helpers\UrlHelper;
use dukt\facebook\Plugin as Facebook;
use League\OAuth2\Client\Provider\Facebook as FacebookProvider;
use yii\base\Component;
use League\OAuth2\Client\Token\AccessToken;

/**
 * Class Oauth service
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Oauth extends Component
{
    // Properties
    // =========================================================================

    /**
     * @var
     */
    private $token;

    // Public Methods
    // =========================================================================

    /**
     * Gets the Facebook OAuth provider
     *
     * @return FacebookProvider
     */
    public function getOauthProvider()
    {
        $options = [];

        $clientId = Facebook::$plugin->getClientId();

        if ($clientId) {
            $options['clientId'] = $clientId;
        }

        $clientSecret = Facebook::$plugin->getClientSecret();

        if ($clientSecret) {
            $options['clientSecret'] = $clientSecret;
        }

        if (!isset($options['graphApiVersion'])) {
            $options['graphApiVersion'] = Facebook::$plugin->getSettings()->apiVersion;
        }

        if (!isset($options['redirectUri'])) {
            $options['redirectUri'] = $this->getRedirectUri();
        }

        return new FacebookProvider($options);
    }

    /**
     * Saves a token
     *
     * @param AccessToken $token
     */
    public function saveToken(AccessToken $token)
    {
        $account = Facebook::$plugin->getAccounts()->getAccount();

        $account->token = [
            'accessToken' => $token->getToken(),
            'expires' => $token->getExpires(),
            'refreshToken' => $token->getRefreshToken(),
            'resourceOwnerId' => $token->getResourceOwnerId(),
            'values' => $token->getValues(),
        ];;

        return Facebook::$plugin->getAccounts()->saveAccount($account);
    }

    /**
     * Gets a token
     *
     * @return AccessToken|null
     */
    public function getToken()
    {
        if ($this->token) {
            return $this->token;
        }

        $account = Facebook::$plugin->getAccounts()->getAccount();

        if (!$account || !$account->token) {
            return null;
        }

        $accountToken = Json::decode($account->token);

        $token = new AccessToken([
            'access_token' => (isset($accountToken['accessToken']) ? $accountToken['accessToken'] : null),
            'expires' => (isset($accountToken['expires']) ? $accountToken['expires'] : null),
            'refresh_token' => (isset($accountToken['refreshToken']) ? $accountToken['refreshToken'] : null),
            'resource_owner_id' => (isset($accountToken['resourceOwnerId']) ? $accountToken['resourceOwnerId'] : null),
            'values' => (isset($accountToken['values']) ? $accountToken['values'] : null),
        ]);

        if ($token->getExpires() && $token->hasExpired()) {
            $provider = $this->getOauthProvider();
            $grant = new \League\OAuth2\Client\Grant\RefreshToken();
            $newToken = $provider->getAccessToken($grant, ['refresh_token' => $token->getRefreshToken()]);

            $token = new AccessToken([
                'access_token' => $newToken->getToken(),
                'expires' => $newToken->getExpires(),
                'refresh_token' => $accountToken['refreshToken'],
                'resource_owner_id' => $newToken->getResourceOwnerId(),
                'values' => $newToken->getValues(),
            ]);

            $this->saveToken($token);
        }

        return $token;
    }

    /**
     * Deletes a token
     *
     * @return bool
     */
    public function deleteToken()
    {
        $account = Facebook::$plugin->getAccounts()->getAccount();

        return Facebook::$plugin->getAccounts()->deleteAccount($account);
    }

    /**
     * Gets the redirect URI.
     *
     * @return string
     */
    public function getRedirectUri()
    {
        $url = UrlHelper::actionUrl('facebook/oauth/callback');
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);

            $query = http_build_query($query);

            return $parsedUrl['scheme'].'://'.$parsedUrl['host'].$parsedUrl['path'].'?'.$query;
        }

        return $url;
    }
}
