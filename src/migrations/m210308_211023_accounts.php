<?php

namespace dukt\facebook\migrations;

use Craft;
use craft\db\Migration;
use craft\helpers\Json;
use dukt\facebook\Plugin;

/**
 * m210308_211023_accounts migration.
 */
class m210308_211023_accounts extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (Craft::$app->db->schema->getTableSchema('{{%facebook_accounts}}') !== null) {
            return null;
        }

        $this->createTable(
            '{{%facebook_accounts}}',
            [
                'id'                => $this->primaryKey(),
                'token'             => $this->text(),
                'dateCreated'       => $this->dateTime()->notNull(),
                'dateUpdated'       => $this->dateTime()->notNull(),
                'uid'               => $this->uid()
            ]
        );

        // Don't try to port the token to the new accounts table since the OAuth scope has changed in Facebook 2.0.4

        // Reset the token on the settings since it will now be stored in the accounts table
        $settings = Plugin::$plugin->getSettings();
        $settings->token = null;
        $plugin = Craft::$app->getPlugins()->getPlugin('facebook');
        Craft::$app->getPlugins()->savePluginSettings($plugin, $settings->toArray());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m210308_211023_accounts cannot be reverted.\n";
        return false;
    }
}
