<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook\models;

use craft\base\Model;

/**
 * Tweet model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  3.0
 */
class Account extends Model
{
    // Properties
    // =========================================================================

    public ?int $id = null;

    public ?array $token = null;
}
