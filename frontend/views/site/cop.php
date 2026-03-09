<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'COP';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="cop-page">
    <div class="cop-header">
        <div>
            <span class="mini-label">Vista operacional</span>
            <h1>Common Operational Picture</h1>
            <p>Esta página fica pronta para receber o teu mapa Leaflet real, overlays, layers e widgets vivos.</p>
        </div>
        <?= Html::a('Voltar ao início', ['/site/index'], ['class' => 'btn btn-ba5-secondary']) ?>
    </div>

    <div class="cop-shell">
        <aside class="cop-sidebar info-card">
            <h3>Painel lateral</h3>
            <p>Zona reservada para filtros, layers, incidentes, pedidos, equipas e detalhe de seleção.</p>
            <ul class="cop-feature-list">
                <li>Camadas do mapa</li>
                <li>Ocorrências ativas</li>
                <li>Pedidos recentes</li>
                <li>Detalhe do objeto selecionado</li>
            </ul>
        </aside>

        <section class="cop-map-stage info-card">
            <div class="cop-map-placeholder">
                <div class="map-grid"></div>
                <div class="map-overlay map-overlay-a"></div>
                <div class="map-overlay map-overlay-b"></div>
                <div class="map-marker marker-1">Hangar</div>
                <div class="map-marker marker-2">Torre</div>
                <div class="map-marker marker-3">Bloco B</div>
                <div class="map-marker marker-4">Portão Sul</div>
            </div>
        </section>
    </div>
</div>
