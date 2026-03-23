<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class DashboardAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/dashboard.css',
    ];

    public $js = [
        'js/dashboard.js',
    ];

    public $depends = [
        'frontend\assets\AppAsset',
    ];
}