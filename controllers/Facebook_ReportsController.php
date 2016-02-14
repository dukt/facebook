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
}
