# Configuration

Facebook comes with a bunch of config settings that give you control over various aspects of its behavior.

## apiVersion

Graph API version used to request the Facebook API.

    'apiVersion' => 'v2.7',

## enableCache

Whether request to APIs should be cached or not.

    'enableCache' => true,

## cacheDuration

The amount of time cache should last.

See [http://www.php.net/manual/en/dateinterval.construct.php](http://www.php.net/manual/en/dateinterval.construct.php)

    'cacheDuration' => 'PT1H',

## oauthScope

OAuth scope.

    'oauthScope' => ['public_profile', 'manage_pages', 'read_insights'],

## oauthAuthorizationOptions

OAuth authorization options.

    'oauthAuthorizationOptions' => [],