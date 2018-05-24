<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2018, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;
use yii\web\Response;

/**
 * Class ReportsController
 *
 * @author Dukt <support@dukt.net>
 * @since  2.0
 */
class ReportsController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Returns the insights report.
     *
     * @return Response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionGetInsightsReport()
    {
        $report = Facebook::$plugin->getReports()->getInsightsReport();

        return $this->asJson($report);
    }
}
