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
     * @inheritDoc IComponentType::getName()
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('app', 'Facebook Insights');
    }

    /**
     * @inheritDoc IWidget::getIconPath()
     *
     * @return string
     */
    public function getIconPath()
    {
        return Craft::$app->resources->getResourcePath('facebook/images/widgets/like.svg');
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