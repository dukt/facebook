<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2015, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

class Facebook_ReportsService extends BaseApplicationComponent
{
    public function getInsightsReport()
    {
        $pluginSettings = craft()->plugins->getPlugin('facebook')->getSettings();

        $facebookInsightsObjectId = $pluginSettings['facebookInsightsObjectId'];

        $report = craft()->facebook_cache->get(['insightsReport', $facebookInsightsObjectId]);

        if(!$report)
        {
            $token = craft()->facebook_oauth->getToken();

            if($token)
            {
                // object
                $response = craft()->facebook_api->get('/'.$facebookInsightsObjectId, ['metadata' => 1, 'fields' => 'name']);

                $object = $response['data'];
                $objectType = $object['metadata']['type'];

                $counts = [];

                switch ($objectType)
                {
                    case 'page':

                        $response = craft()->facebook_api->get('/'.$facebookInsightsObjectId.'/insights', array(
                            'since' => date('Y-m-d', strtotime('-6 day')),
                            'until' => date('Y-m-d', strtotime('+1 day')),
                        ));

                        $insights = $response['data']['data'];

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

                        break;

                    default:
                        throw new Exception("Insights not available for object type `".$objectType."`");

                }

                $report = [
                    'object' => $object,
                    'objectType' => $objectType,
                    'counts' => $counts,
                ];

                craft()->facebook_cache->set(['insightsReport', $facebookInsightsObjectId], $report);
            }
            else
            {
                throw new Exception("Not authenticated");
            }
        }

        return $report;
    }
}
