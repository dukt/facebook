<?php

namespace dukt\facebook\migrations;

use craft\db\Migration;
use dukt\facebook\widgets\InsightsWidget;

/**
 * m180910_124715_craft3_upgrade migration.
 */
class m180910_124715_craft3_upgrade extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Widgets
        $this->update('{{%widgets}}', [
            'type' => InsightsWidget::class
        ], ['type' => 'Facebook_Insights']);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180910_124715_craft3_upgrade cannot be reverted.\n";
        return false;
    }
}
