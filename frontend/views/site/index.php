<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;
use yii\helpers\Json;
use common\assets\CopMapReadOnlyAsset;
use yii\helpers\Url;

$this->title = 'BA5 COP';


$asset = CopMapReadOnlyAsset::register($this);
$imageUrl = $asset->baseUrl . '/img/img_mapa.jpg';
$mapIconsBaseUrl = $asset->baseUrl . '/img/map-icons';

$homeMapOptions = [
    'elId' => 'home-map',
    'mode' => 'image',
    'imageUrl' => $imageUrl,
    'imageWidth' => 1066,
    'imageHeight' => 701,
    'minZoom' => -1.5,
    'maxZoom' => 2,
    'scrollWheelZoom' => true,
    'locationsIndexUrl' => Url::to(['/site/cop-data']),
    'iconsBaseUrl' => $mapIconsBaseUrl,
];
?>

<div class="ba5-home">

    <div class="hero-card">

        <div class="hero-copy">
            <span class="hero-eyebrow">COMMON OPERATIONAL PICTURE</span>

            <h1>Bem-vindo ao COP</h1>

            <p class="hero-text">
                A plataforma de apoio à gestão operacional da Base Aérea N.º 5,
                permitindo visualizar e acompanhar informação relevante da unidade através
                de uma representação cartográfica centralizada da base, proporcionando uma
                visão clara e organizada dos principais elementos e atividades da unidade.
            </p>
        </div>

        <div class="hero-image">
            <?= Html::img('@web/img/f16.jpg', [
                'class' => 'f16-hero',
                'alt' => 'F-16'
            ]) ?>
        </div>

    </div>

    <section class="info-card panel-card">
        <div class="panel-head">
            <div>
                <span class="mini-label">Mapa operacional</span>
                <h2>Vista geral da base</h2>
            </div>
            <?= Html::a(
                '<i class="fa-solid fa-up-right-and-down-left-from-center"></i>',
                ['/site/cop'],
                [
                    'class' => 'btn btn-ba5-primary',
                    'title' => 'Abrir mapa em ecrã inteiro'
                ]
            ) ?>
        </div>

        <div id="home-map" class="cop-leaflet-map"></div>
    </section>

    <section class="content-grid content-grid-login">
        <article class="info-card panel-card panel-card-wide">
            <div class="panel-head">
                <div>
                    <span class="mini-label">Sobre a plataforma</span>
                    <h3>Introdução rápida</h3>
                </div>
            </div>

            <div class="timeline-list">
                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div class="timeline-body">
                        <strong>Consciência situacional da unidade</strong>
                        <p>
                            A plataforma COP pretende fornecer uma visão clara e centralizada da Base Aérea N.º 5,
                            permitindo acompanhar informação relevante da unidade através de uma representação
                            cartográfica da base e de elementos associados às suas infraestruturas.
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-clipboard-list"></i></div>
                    <div class="timeline-body">
                        <strong>Apoio à gestão operacional</strong>
                        <p>
                            O sistema foi desenvolvido para apoiar a gestão operacional da base, facilitando
                            a consulta de informação importante e permitindo uma visão organizada do estado
                            geral da unidade.
                        </p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-layer-group"></i></div>
                    <div class="timeline-body">
                        <strong>Centralização da informação</strong>
                        <p>
                            A plataforma reúne diferentes elementos informativos num único ponto de acesso,
                            contribuindo para uma consulta mais rápida e estruturada da informação associada
                            à Base Aérea N.º 5.
                        </p>
                    </div>
                </div>

                <div class="quick-actions">
                    <?= Html::a('Sobre Nós', ['/site/about'], ['class' => 'btn btn-ba5-primary w-100']) ?>
                </div>
            </div>
        </article>

        <article class="info-card panel-card login-panel text-center ba5-identity-card">

            <video autoplay muted loop playsinline class="ba5-bg-video">
                <source src="<?= Yii::getAlias('@web') ?>/video/ba5Cacas.mp4" type="video/mp4">
            </video>

            <div class="ba5-identity-content">

                <?= Html::img('@web/img/BA5_Brasao.png', [
                    'alt' => 'Base Aérea Nº5',
                    'class' => 'ba5-badge-large'
                ]) ?>

                <span class="mini-label mt-3">Base Aérea N.º 5</span>

                <p class="ba5-motto">
                    "Alcança quem não cansa"
                </p>

            </div>

        </article>
    </section>

</div>

<?php
$this->registerJs(
    'initCopMapReadOnly(' . Json::htmlEncode($homeMapOptions) . ');'
);
?>