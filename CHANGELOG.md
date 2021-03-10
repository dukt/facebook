Changelog
=========

## 2.0.4 - 2021-03-10

### Added
- Added environment variables support for the OAuth client ID and secret.
- Added `pages_read_engagement` to the list of default OAuth permissions.

### Changed
- OAuth tokens are now stored in a `facebook_accounts` table instead of in the plugin’s settings.
- Deprecated `\dukt\facebook\models\Settings::$token`.
- Updated the API version to v8.0.
- Bumped the schema version to 1.0.2.

### Fixed
- Fixed the icon of the Insights widget.
- Fixed a bug where the Facebook Insights widget was not able to pull reporting data due to an OAuth permission issue.
- Fixed a bug where the plugin was missing OAuth permissions to function properly with Facebook Pages.
- Fixed a bug where the Insights widget could show an error when the Facebook Page ID was not configured.

## 2.0.3 - 2019-02-12

### Fixed
- Fixed a bug where the settings page was throwing an error when the OAuth token got expired.

## 2.0.2 - 2018-09-10

### Added
- Added the Facebook user picture to the plugin’s settings.

### Changed
- Updated default Facebook API version to v3.0.

### Fixed
- Fixed Craft 3 upgrade migration.

## 2.0.1 - 2018-05-25

### Added
- Added profile picture to the Insights widget.

### Changed
- Updated to require craftcms/cms `^3.0.0-RC1`.

### Fixed
- Fixed a bug where the Insights widget would throw an error because insight data was not requested using a page access token.

## 2.0.0 - 2017-12-05

### Added 
- Craft 3 compatibility.

### Fixed
- Fixed encoding bug with Facebook’s redirect URI.

### Removed
- Removed `FacebookVariable`.

## 2.0.0-beta.3 - 2017-09-01

### Added
- Added support informations to composer.json.

### Improved
- Facebook now requires Craft 3.0.0 beta 20 or above.
- Updated widget types for Craft 3.

### Fixed
- Fixed encoding bug with Facebook’s redirect URI.

## 2.0.0-beta.2 - 2017-04-05

### Added

- Added reference generation instructions.
- Added docs.

### Improved

- Updated config settings for Craft 3.0.0-beta.8+.
- Renamed `$hasSettings` to `$hasCpSettings`.

### Removed

- Removed `FacebookVariable`.

## 2.0.0-beta.1 - 2017-03-09

### Added
- Craft 3 compatibility.
- Added `craftcms/cms` dependency.
- Added `league/oauth2-client` dependency.
- Added `league/oauth2-facebook` dependency.

### Removed
- Removed `dukt/craft-oauth` dependency.


## 1.1.1 - 2016-11-08

### Improved
-  Updated requirements to OAuth 2.0.2.
-  The plugin is now using Facebook API v2.8.
-  Improved installation process.
-  API improvements.
-  Improved exception handling for `Facebook_ReportsController::actionGetInsightsReport()`.

### Fixed
-  Fixed CSS for the Insights widget.


## 1.1.0 - 2016-08-09

### Added
-  Added an `apiVersion` config to customize the Graph API version used to perform requests to the Facebook API.

### Improved
-  Facebook plugin now requires OAuth 2.0+.
-  The plugin is now using Graph API 2.7.

### Fixed
-  Fixed a bug where Facebook_PluginService::checkRequirements() which was checking if Google was configured instead of Facebook.


## 1.0.12087141 - 2016-02-14

### Added
-  Facebook Insights widget for the dashboard..