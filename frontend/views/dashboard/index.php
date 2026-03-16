<?php

use common\assets\CopMapReadOnlyAsset;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Dashboard';
$this->params['breadcrumbs'] = [];

\frontend\assets\DashboardAsset::register($this);

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
];
?>

    <div class="dashboard-shell">
        <section class="dashboard-top">
            <article class="dashboard-kpi-card">
                <span class="mini-label">Ocorrências</span>
                <div class="dashboard-kpi-value">12</div>
                <p>3 nas últimas 24h</p>
            </article>

            <article class="dashboard-kpi-card">
                <span class="mini-label">Pedidos externos</span>
                <div class="dashboard-kpi-value">5</div>
                <p>2 prioritários</p>
            </article>

            <article class="dashboard-kpi-card">
                <span class="mini-label">WO ativas</span>
                <div class="dashboard-kpi-value">8</div>
                <p>6 em execução</p>
            </article>

            <article class="dashboard-kpi-card">
                <span class="mini-label">Capacidade alojamento</span>
                <div class="dashboard-kpi-value">72</div>
                <p>camas disponíveis</p>
            </article>
        </section>

        <section class="dashboard-main">
            <article class="dashboard-card dashboard-side-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Resumo</span>
                        <h3>Situação operacional</h3>
                    </div>
                </div>

                <div class="dashboard-list">
                    <div class="dashboard-list-item">
                        <strong>Perímetro</strong>
                        <span class="status-pill">Estável</span>
                    </div>
                    <div class="dashboard-list-item">
                        <strong>Energia</strong>
                        <span class="status-pill">1 ponto crítico</span>
                    </div>
                    <div class="dashboard-list-item">
                        <strong>Abastecimento</strong>
                        <span class="status-pill">Normal</span>
                    </div>
                    <div class="dashboard-list-item">
                        <strong>Habitabilidade</strong>
                        <span class="status-pill">72 vagas</span>
                    </div>
                </div>
            </article>

            <article class="dashboard-card dashboard-map-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Common Operational Picture</span>
                        <h3>Mapa operacional</h3>
                    </div>
                    <div class="quick-actions">
                        <span class="status-pill">Leitura</span>
                        <span class="status-pill">BA5</span>
                    </div>
                </div>

                <div id="cop-map" class="dashboard-map"></div>
            </article>

            <article class="dashboard-card dashboard-side-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Pedidos</span>
                        <h3>Apoio externo</h3>
                    </div>
                </div>

                <div class="dashboard-list">
                    <div class="dashboard-list-item">
                        <strong>REQ-015</strong>
                        <span class="status-pill">Em análise</span>
                    </div>
                    <div class="dashboard-list-item">
                        <strong>REQ-018</strong>
                        <span class="status-pill">Pendente</span>
                    </div>
                    <div class="dashboard-list-item">
                        <strong>REQ-020</strong>
                        <span class="status-pill">Aprovado</span>
                    </div>
                </div>
            </article>
        </section>

        <section class="dashboard-bottom">
            <article class="dashboard-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Execução</span>
                        <h3>Work Orders</h3>
                    </div>
                </div>
                <div class="dashboard-list">
                    <div class="dashboard-list-item"><strong>WO-001</strong><span>Em execução</span></div>
                    <div class="dashboard-list-item"><strong>WO-003</strong><span>Pendente</span></div>
                    <div class="dashboard-list-item"><strong>WO-007</strong><span>Concluída</span></div>
                </div>
            </article>

            <article class="dashboard-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Coordenação</span>
                        <h3>Decisões recentes</h3>
                    </div>
                </div>
                <div class="dashboard-list">
                    <div class="dashboard-list-item"><strong>15:40</strong><span>Interditar setor C</span></div>
                    <div class="dashboard-list-item"><strong>16:05</strong><span>Priorizar reparação PT1</span></div>
                </div>
            </article>

            <article class="dashboard-card">
                <div class="panel-head">
                    <div>
                        <span class="mini-label">Alertas</span>
                        <h3>Riscos do dia</h3>
                    </div>
                </div>
                <div class="dashboard-list">
                    <div class="dashboard-list-item"><strong>Meteorologia</strong><span>Vento forte</span></div>
                    <div class="dashboard-list-item"><strong>Infraestrutura</strong><span>Árvore caída</span></div>
                </div>
            </article>
        </section>
    </div>

<?php
$this->registerJs('initCopMapReadOnly(' . Json::htmlEncode($copMapOptions) . ');');
?>