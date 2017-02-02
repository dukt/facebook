<?php
namespace dukt\facebook\models;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

    public $tokenId;
    public $facebookInsightsObjectId;

    // Public Methods
    // =========================================================================

    public function rules()
    {
        return [
            [['tokenId'], 'number', 'integerOnly' => true],
        ];
    }
}
