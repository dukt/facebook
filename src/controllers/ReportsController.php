<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;

class ReportsController extends Controller
{
	// Public Methods
	// =========================================================================

	/**
	 * Returns the insights report
	 *
	 * @return null
	 */
	public function actionGetInsightsReport()
    {
        try
        {
            $report = Facebook::$plugin->facebook_reports->getInsightsReport();

            return $this->asJson($report);
        }
        catch(\Guzzle\Http\Exception\ClientErrorResponseException $e)
        {
            $errorMsg = $e->getMessage();

            $response = $e->getResponse();
            $data = json_decode($response->getBody(), true);

            if(!empty($data['error']['message']))
            {
                $errorMsg = $data['error']['message'];
            }

            return $this->asErrorJson($errorMsg);
        }
        catch(\Exception $e)
        {
            if(method_exists($e, 'getErrors'))
            {
                $errors = $e->getErrors();

                if(isset($errors[0]['message']))
                {
                    return $this->asErrorJson(Craft::t($errors[0]['message']));
                }
            }

            return $this->asErrorJson($e->getMessage());
        }
    }
}
