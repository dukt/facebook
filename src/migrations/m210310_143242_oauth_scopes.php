<?php

namespace dukt\facebook\migrations;

use Craft;
use craft\db\Migration;
use dukt\facebook\models\Settings;
use dukt\facebook\Plugin;

/**
 * m210310_143242_oauth_scopes migration.
 */
class m210310_143242_oauth_scopes extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $settings = Plugin::$plugin->getSettings();
        $settingsModel = new Settings();

        $settings->oauthScope = $settingsModel->oauthScope;

        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');
        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->toArray());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210310_143242_oauth_scopes cannot be reverted.\n";
        return false;
    }
}
