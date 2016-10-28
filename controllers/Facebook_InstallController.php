<?php
/**
 * @link      https://dukt.net/craft/facebook/
 * @copyright Copyright (c) 2016, Dukt
 * @license   https://dukt.net/craft/facebook/docs/license
 */

namespace Craft;

/**
 * Facebook Plugin controller
 */
class Facebook_InstallController extends BaseController
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
        if(!craft()->facebook->checkDependencies())
        {
            $missingDependencies = craft()->facebook->getMissingDependencies();
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