<?php
namespace dukt\facebook\models;

use craft\base\Model;

/**
 * Settings model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Settings extends Model
{
    // Properties
    // =========================================================================
    /**
     * Graph API version used to request the Facebook API.
     *
     * @var string
     */
    public $apiVersion = 'v8.0';

    /**
     * The amount of time cache should last.
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     *
     * @var string
     */
    public $cacheDuration = 'PT1H';

    /**
     * Whether request to APIs should be cached or not.
     *
     * @var bool
     */
    public $enableCache = true;

    /**
     * OAuth client ID.
     *
     * @var string|null
     */
    public $oauthClientId;

    /**
     * OAuth client secret.
     *
     * @var string|null
     */
    public $oauthClientSecret;

    /**
     * OAuth provider authorization options.
     *
     * @var array
     */
    public $oauthAuthorizationOptions = [];

    /**
     * OAuth scope.
     *
     * @var array
     */
    public $oauthScope = ['public_profile', 'read_insights'];

    /**
     * @var mixed|null The OAuth token
     * @deprecated in 2.1.5
     */
    public $token;

    /**
     * Facebook Insights Object ID
     *
     * @var string|null
     */
    public $facebookInsightsObjectId;

    // Public Methods
    // =========================================================================

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['facebookInsightsObjectId'], 'string'],
        ];
    }
}
