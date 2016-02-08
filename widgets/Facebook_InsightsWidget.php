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

    public function getBodyHtml()
    {
        $pluginSettings = craft()->plugins->getPlugin('facebook')->getSettings();

        $facebookInsightsObjectId = $pluginSettings['facebookInsightsObjectId'];

        $token = craft()->facebook_oauth->getToken();

        if($token)
        {
            // object
            $response = craft()->facebook_api->get('/'.$facebookInsightsObjectId, ['metadata' => 1, 'fields' => 'name']);

            $object = $response['data'];
            $objectType = $object['metadata']['type'];
            $error = false;

            switch ($objectType)
            {
                case 'page':

                    $response = craft()->facebook_api->get('/'.$facebookInsightsObjectId.'/insights/page_fans', array(
                        'since' => date('Y-m-d', strtotime('-6 day')),
                        'until' => date('Y-m-d', strtotime('+1 day')),
                    ));

                    $insights = $response['data']['data'][0];

                    $weekTotalStart = $insights['values'][0]['value'];
                    $weekTotalEnd = end($insights['values'])['value'];

                    $weekTotal = $weekTotalEnd - $weekTotalStart;

                    $variables['weekTotal'] = $weekTotal;
                    $variables['insights'] = $insights;

                    break;

                default:
                    // throw new \Exception("Insights not available for object type `".$objectType."`");

                    $error = "Insights not available for object type `".$objectType."`";

            }

            $variables['error'] = $error;
            $variables['object'] = $object;
            $variables['objectType'] = $objectType;

            craft()->templates->includeCssResource('facebook/css/stats-widget.css');

            return craft()->templates->render('facebook/_components/widgets/Insights/body', $variables);
        }
        else
        {
            return craft()->templates->render('facebook/_components/widgets/Insights/disabled');
        }
    }

    private function pageInsights()
    {

    }

    private function domainInsights()
    {

    }
}