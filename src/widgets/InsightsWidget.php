<?php
namespace dukt\facebook\widgets;

use Craft;
use dukt\facebook\Plugin as Facebook;
use dukt\facebook\web\assets\insightswidget\InsightsWidgetAsset;

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

            Craft::$app->getView()->registerAssetBundle(InsightsWidgetAsset::class);
            Craft::$app->getView()->registerJs('new Craft.FacebookInsightsWidget("widget'.$widgetId.'");');


            return Craft::$app->getView()->renderTemplate('facebook/_components/widgets/Insights/body');
        }
        else
        {
            return Craft::$app->getView()->renderTemplate('facebook/_special/plugin-not-configured');
        }
    }
}