<?php
namespace Craft;

class Facebook_InsightsWidget extends BaseWidget
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
        return Craft::t('Facebook Insights');
    }

    /**
     * @inheritDoc IWidget::getIconPath()
     *
     * @return string
     */
    public function getIconPath()
    {
        return craft()->resources->getResourcePath('facebook/images/widgets/like.svg');
    }

	/**
	 * @inheritDoc IWidget::getBodyHtml()
	 *
	 * @return string|false
	 */
    public function getBodyHtml()
    {
        if(craft()->facebook_plugin->checkRequirements())
        {
            $widgetId = $this->model->id;

            craft()->templates->includeJsResource('facebook/js/InsightsWidget.js');
            craft()->templates->includeJs('new Craft.FacebookInsightsWidget("widget'.$widgetId.'");');

            craft()->templates->includeCssResource('facebook/css/insights-widget.css');

            return craft()->templates->render('facebook/_components/widgets/Insights/body');
        }
        else
        {
            return craft()->templates->render('facebook/_components/widgets/Insights/disabled');
        }
    }
}