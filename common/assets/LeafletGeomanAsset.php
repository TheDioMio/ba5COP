<?php
namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LeafletGeomanAsset extends AssetBundle
{
    public $css = [
        'https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css',
    ];

    public $js = [
        'https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js',
    ];

    public $depends = [
        LeafletAsset::class,
    ];

    public $jsOptions = ['position' => View::POS_END];
}