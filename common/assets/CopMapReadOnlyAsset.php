<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class CopMapReadOnlyAsset extends AssetBundle
{
    public $sourcePath = '@common/web';

    public $js = [
        'js/cop-map-readonly.js',
    ];

    public $depends = [
        LeafletAsset::class,
    ];

    public $jsOptions = [
        'position' => View::POS_END,
    ];
}