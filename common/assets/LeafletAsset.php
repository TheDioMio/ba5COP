<?php
namespace common\assets;

use yii\web\AssetBundle;

class LeafletAsset extends AssetBundle
{
    public $css = [
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
    ];

    public $js = [
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_END];
}