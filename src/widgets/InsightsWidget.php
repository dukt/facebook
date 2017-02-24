<?php
namespace dukt\facebook\widgets;

use Craft;
use dukt\facebook\web\assets\facebook\FacebookAsset;
use dukt\facebook\Plugin as Facebook;

class InsightsWidget extends \craft\base\Widget
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('facebook', 'Facebook Insights');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias('@dukt/facebook/icons/like.svg');
    }

    /**
     * @inheritDoc IWidget::getBodyHtml()
     *
     * @return string|false
     */
    public function getBodyHtml()
    {
        if(Facebook::$plugin->getFacebook()->checkPluginRequirements())
        {
            $widgetId = $this->id;

            /*Craft::$app->getView()->includeJsResource('facebook/js/InsightsWidget.js');
            Craft::$app->getView()->includeCssResource('facebook/css/insights-widget.css');*/

            Craft::$app->getView()->registerAssetBundle(FacebookAsset::class);
            Craft::$app->getView()->registerJs('new Craft.FacebookInsightsWidget("widget'.$widgetId.'");');


            return Craft::$app->getView()->renderTemplate('facebook/_components/widgets/Insights/body');
        }
        else
        {
            return Craft::$app->getView()->renderTemplate('facebook/_special/plugin-not-configured');
        }
    }
}