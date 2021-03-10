<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) 2021, Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\records;

use craft\db\ActiveRecord;

class Account extends ActiveRecord
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the name of the associated database table.
     *
     * @return string
     */
    public static function tableName(): string
    {
        return '{{%facebook_accounts}}';
    }
}
