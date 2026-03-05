<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LeafletDrawAsset extends AssetBundle
{
    public $css = [
        'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css',
    ];

    public $js = [
        'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js',
    ];

    public $depends = [
        LeafletAsset::class,
    ];

    public $jsOptions = ['position' => View::POS_END];
}