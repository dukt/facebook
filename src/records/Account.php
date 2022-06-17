<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\records;

use craft\db\ActiveRecord;

/**
 * Account record.
 *
 * @property int $id
 * @property array $token
 */
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
