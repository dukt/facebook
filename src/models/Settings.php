<?php
namespace dukt\facebook\models;

use craft\base\Model;

/**
 * Settings model class.
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Settings extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var
     */
    public $token;
    /**
     * @var
     */
    public $facebookInsightsObjectId;
}
