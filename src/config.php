<?php

return [

    /**
     * Graph API version used to request the Facebook API.
     */
    'apiVersion' => 'v2.8',

    /**
     * The amount of time cache should last.
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     */
    'cacheDuration' => 'PT1H',

    /**
     * Whether request to APIs should be cached or not.
     */
    'enableCache' => true,

    /**
     * OAuth client ID.
     */
    'oauthClientId' => null,

    /**
     * OAuth client secret.
     */
    'oauthClientSecret' => null,

    /**
     * OAuth provider authorization options.
     */
    'oauthAuthorizationOptions' => [],

    /**
     * OAuth scope.
     */
    'oauthScope' => ['public_profile', 'manage_pages', 'read_insights'],
];
