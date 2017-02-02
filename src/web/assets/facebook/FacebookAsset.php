<?php
namespace dukt\facebook\web\assets\facebook;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class FacebookAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@dukt/facebook/resources';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/InsightsWidget.js',
        ];

        $this->css = [
            'css/insights-widget.css',
        ];

        parent::init();
    }
}