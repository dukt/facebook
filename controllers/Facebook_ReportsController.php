<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_ReportsController extends BaseController
{
    public function actionGetInsightsReport()
    {
        try
        {
            $report = craft()->facebook_reports->getInsightsReport();
            $this->returnJson($report);
        }
        catch(\Exception $e)
        {
            if(method_exists($e, 'getErrors'))
            {
                $errors = $e->getErrors();

                if(isset($errors[0]['message']))
                {
                    $this->returnErrorJson(Craft::t($errors[0]['message']));
                }
            }

            $this->returnErrorJson($e->getMessage());
        }
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

                    $response = craft()->facebook_api->get('/'.$facebookInsightsObjectId.'/insights', array(
                        'since' => date('Y-m-d', strtotime('-6 day')),
                        'until' => date('Y-m-d', strtotime('+1 day')),
                    ));

                    $insights = $response['data']['data'];

                    $counts = [];

                    foreach($insights as $insight)
                    {
                        switch ($insight['name'])
                        {
                            case 'page_fans':

                                $weekTotalStart = $insight['values'][0]['value'];
                                $weekTotalEnd = end($insight['values'])['value'];

                                $weekTotal = $weekTotalEnd - $weekTotalStart;

                                $counts[] = [
                                    'label' => 'Total likes',
                                    'value' => end($insight['values'])['value']
                                ];
                                $counts[] = [
                                    'label' => 'Likes this week',
                                    'value' => $weekTotal
                                ];
                                break;

                            case 'page_impressions_unique':

                                switch($insight['period'])
                                {
                                    case 'week':

                                        $counts[] = [
                                            'label' => 'Total Reach this week',
                                            'value' => end($insight['values'])['value']
                                        ];

                                        break;

                                    case 'days_28':

                                        $counts[] = [
                                            'label' => 'Total Reach this month',
                                            'value' => end($insight['values'])['value']
                                        ];

                                        break;
                                }

                                break;
                        }
                    }


                    $variables['counts'] = $counts;

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
}
