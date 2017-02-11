<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace dukt\facebook\controllers;

use craft\web\Controller;
use dukt\facebook\Plugin as Facebook;

/**
 * Facebook Plugin controller
 */
class InstallController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Install Index
     *
     * @return null
     */
    public function actionIndex()
    {
        if(!Facebook::$plugin->facebook->checkDependencies())
        {
            $missingDependencies = Facebook::$plugin->facebook->getMissingDependencies();
            $this->renderTemplate('facebook/_special/install/index', [
                'missingDependencies' => $missingDependencies,
            ]);
        }
        else
        {
            $this->redirect('facebook/settings');
        }
    }
}