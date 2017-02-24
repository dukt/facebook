<?php

return [

    /**
     * Graph API version used to request the Facebook API.
     */
    'apiVersion' => 'v2.8',

    /**
     * Whether request to APIs should be cached or not.
     */
    'enableCache' => true,

    /**
     * The amount of time cache should last.
     *
     * @see http://www.php.net/manual/en/dateinterval.construct.php
     */
    'cacheDuration' => 'PT1H',

    /**
     * OAuth scope.
     */
    'oauthScope' => ['public_profile', 'manage_pages', 'read_insights'],

    /**
     * OAuth provider authorization options.
     */
    'oauthAuthorizationOptions' => [],

    /**
     * OAuth provider options.
     */
    'oauthProviderOptions' => [
        'clientId' => '123456789012345',
        'clientSecret' => 'zfo8G8wef8G92fZbkHF83owy7weY2300'
    ],
];
