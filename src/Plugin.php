<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
 */

namespace dukt\facebook;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;
use dukt\facebook\base\PluginTrait;
use dukt\facebook\models\Settings;
use dukt\facebook\widgets\InsightsWidget;
use yii\base\Event;

/**
 * Facebook plugin.
 *
 * @method Settings getSettings()
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Plugin extends \craft\base\Plugin
{
    // Traits
    // =========================================================================

    use PluginTrait;

    // Properties
    // =========================================================================

    /**
     * @var bool
     */
    public bool $hasCpSettings = true;

    /**
     * @var \dukt\facebook\Plugin The plugin instance.
     */
    public static $plugin;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'accounts' => \dukt\facebook\services\Accounts::class,
            'api' => \dukt\facebook\services\Api::class,
            'cache' => \dukt\facebook\services\Cache::class,
            'oauth' => \dukt\facebook\services\Oauth::class,
            'reports' => \dukt\facebook\services\Reports::class,
        ]);

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event): void {
            $event->types[] = InsightsWidget::class;
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, function(RegisterUrlRulesEvent $event): void {
            $rules = [
                'facebook/settings' => 'facebook/settings/index',
                'facebook/settings/oauth' => 'facebook/settings/oauth',
            ];

            $event->rules = array_merge($event->rules, $rules);
        });
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    public function getSettingsResponse(): mixed
    {
        $url = UrlHelper::cpUrl('facebook/settings');

        Craft::$app->controller->redirect($url);

        return '';
    }
}
