<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;

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
     * @return \yii\web\Response
     */
    public function actionGetInsightsReport()
    {
        $report = Facebook::$plugin->getReports()->getInsightsReport();

        return $this->asJson($report);
    }
}
