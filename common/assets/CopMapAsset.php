<?php
namespace common\assets;

use yii\web\AssetBundle;

class CopMapAsset extends AssetBundle
{
    public $sourcePath = '@common/web';

    public $js = [
        'js/cop-map.js',
    ];

    public $depends = [
        LeafletGeomanAsset::class,
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
}