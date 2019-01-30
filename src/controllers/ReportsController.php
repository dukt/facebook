<?php
/**
 * @link      https://dukt.net/facebook/
 * @copyright Copyright (c) 2019, Dukt
 * @license   https://github.com/dukt/facebook/blob/master/LICENSE.md
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
    public function actionGetInsightsReport(): Response
    {
        $report = Facebook::$plugin->getReports()->getInsightsReport();

        return $this->asJson($report);
    }
}
