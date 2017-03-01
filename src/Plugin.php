<?php

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
    public $hasSettings = true;

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
            'api' => \dukt\facebook\services\Api::class,
            'cache' => \dukt\facebook\services\Cache::class,
            'oauth' => \dukt\facebook\services\Oauth::class,
            'reports' => \dukt\facebook\services\Reports::class,
        ]);

        Event::on(Dashboard::class, Dashboard::EVENT_REGISTER_WIDGET_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = InsightsWidget::class;
        });

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, [$this, 'registerCpUrlRules']);
    }

    /**
     * @param RegisterUrlRulesEvent $event
     */
    public function registerCpUrlRules(RegisterUrlRulesEvent $event)
    {
        $rules = [
            'facebook/settings' => 'facebook/settings/index',
            'facebook/settings/oauth' => 'facebook/settings/oauth',
        ];

        $event->rules = array_merge($event->rules, $rules);
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    public function getSettingsResponse()
    {
        $url = UrlHelper::cpUrl('facebook/settings');

        Craft::$app->controller->redirect($url);

        return '';
    }
}
