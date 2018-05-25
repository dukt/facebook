<?php

namespace dukt\facebook\migrations;

use craft\db\Migration;

/**
 * m170830_085120_craft3_upgrade migration.
 */
class m170830_085120_craft3_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%widgets}}', ['type' => 'dukt\facebook\widgets\InsightsWidget'], ['type' => 'Facebook_InsightsWidget']);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m170830_085120_craft3_upgrade cannot be reverted.\n";
        return false;
    }
}
