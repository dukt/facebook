<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\services;

use Craft;
use craft\base\Component;
use dukt\facebook\Plugin as Facebook;


/**
 * Class Reports service
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class Reports extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the Insights report.
     *
     * @return array|mixed
     */
    public function getInsightsReport()
    {
        $pluginSettings = Craft::$app->getPlugins()->getPlugin('facebook')->getSettings();

        $facebookInsightsObjectId = $pluginSettings['facebookInsightsObjectId'];

        $report = Facebook::$plugin->getCache()->get(['insightsReport', $facebookInsightsObjectId]);

        if(!$report)
        {
            $token = Facebook::$plugin->getOauth()->getToken();

            if($token)
            {
                // Object

                $object = Facebook::$plugin->getApi()->get('/'.$facebookInsightsObjectId, ['metadata' => 1, 'fields' => 'name']);;
                $objectType = $object['metadata']['type'];
                $message = false;

                $counts = [];

                switch ($objectType)
                {
                    case 'page':

                        // Insights for objects of type `page`

                        $supportedObject = true;

                        $insights = Facebook::$plugin->getApi()->get('/'.$facebookInsightsObjectId.'/insights', array(
                            'metric' => 'page_fans,page_impressions_unique',
                            'since' => date('Y-m-d', strtotime('-6 day')),
                            'until' => date('Y-m-d', strtotime('+1 day')),
                        ));

                        foreach($insights['data'] as $insight)
                        {
                            switch ($insight['name'])
                            {
                                case 'page_fans':

                                    $weekTotalStart = $insight['values'][0]['value'];
                                    $weekTotalEnd = end($insight['values'])['value'];

                                    $weekTotal = $weekTotalEnd - $weekTotalStart;

                                    $counts[] = [
                                        'label' => Craft::t('facebook', 'Total likes'),
                                        'value' => end($insight['values'])['value']
                                    ];
                                    $counts[] = [
                                        'label' => Craft::t('facebook', 'Likes this week'),
                                        'value' => $weekTotal
                                    ];
                                    break;

                                case 'page_impressions_unique':

                                    switch($insight['period'])
                                    {
                                        case 'week':

                                            $counts[] = [
                                                'label' => Craft::t('facebook', 'Total Reach this week'),
                                                'value' => end($insight['values'])['value']
                                            ];

                                            break;

                                        case 'days_28':

                                            $counts[] = [
                                                'label' => Craft::t('facebook', 'Total Reach this month'),
                                                'value' => end($insight['values'])['value']
                                            ];

                                            break;
                                    }

                                    break;
                            }
                        }

                        break;

                    default:
                        $supportedObject = false;
                        $message = 'Insights only supports pages, please choose a different Facebook Page ID in <a href="'.UrlHelper::getUrl('facebook/settings').'">Facebook’s settings</a>.';
                        Craft::trace("Insights not available for object type `".$objectType."`, only pages are supported.", __METHOD__);
                }

                $report = [
                    'supportedObject' => $supportedObject,
                    'message' => $message,
                    'object' => $object,
                    'objectType' => $objectType,
                    'counts' => $counts,
                ];

                Facebook::$plugin->getCache()->set(['insightsReport', $facebookInsightsObjectId], $report);
            }
            else
            {
                throw new Exception("Not authenticated");
            }
        }

        return $report;
    }
}
