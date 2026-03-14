<?php

use common\assets\CopMapReadOnlyAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'COP';
$this->params['breadcrumbs'] = [];
$this->params['bodyClass'] = 'cop-page-body';

$asset = CopMapReadOnlyAsset::register($this);
$imageUrl = $asset->baseUrl . '/img/img_mapa.jpg';

$copMapOptions = [
    'elId' => 'cop-map',
    'mode' => 'image',
    'imageUrl' => $imageUrl,
    'imageWidth' => 1066,
    'imageHeight' => 701,
    'minZoom' => -2,
    'maxZoom' => 4,
    'scrollWheelZoom' => true,
    'locationsIndexUrl' => Url::to(['/site/cop-data']),
];
?>

    <div class="cop-screen">
        <div class="cop-floating-bar">
            <div class="cop-floating-info">
                <span class="cop-kicker">COMMON OPERATIONAL PICTURE</span>
                <h1>Mapa Operacional</h1>
                <p>Visualização em modo leitura da Base Aérea N.º 5.</p>
            </div>

            <div class="cop-floating-actions">
                <?= Html::a(
                    '<i class="fa-solid fa-house"></i><span>Início</span>',
                    ['/site/index'],
                    ['class' => 'btn btn-ba5-secondary cop-floating-btn']
                ) ?>
            </div>
        </div>

        <div id="cop-map" class="cop-map-canvas"></div>
    </div>

<?php
$this->registerJs(
    'initCopMapReadOnly(' . Json::htmlEncode($copMapOptions) . ');'
);
?>