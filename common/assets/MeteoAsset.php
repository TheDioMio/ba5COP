<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class MeteoAsset extends AssetBundle
{
    public $sourcePath = '@common/web';

    public $js = [
        'js/script-meteo.js',
    ];
}