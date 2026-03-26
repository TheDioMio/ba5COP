<?php

use common\assets\CopMapReadOnlyAsset;
use frontend\assets\DashboardAsset;
use yii\bootstrap5\Modal;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'COP BA5';
$this->params['breadcrumbs'] = [];
$this->params['bodyClass'] = 'dashboard-page-body';

DashboardAsset::register($this);

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

    <div class="cop-dashboard">
        <!--Zona KPI's-->
        <section class="cop-topbar">
            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#securityModal">
                <span class="cop-kpi-label">Segurança</span>
                <div class="cop-kpi-value is-warning"><?= count($securityIncidents) ?></div>
                <p>incidentes relacionados</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Perímetro</span>
                <div class="cop-kpi-value is-success"><?= $perimeterPercentage ?>%</div>
                <p>vedação operacional</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Energia</span>
                <div class="cop-kpi-value is-warning">78%</div>
                <p>base energizada</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#waterModal">
                <span class="cop-kpi-label">Água</span>
                <div class="cop-kpi-value is-warning"><?= count($waterIncidents) ?></div>
                <p>incidentes relacionados</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#mobilityModal">
                <span class="cop-kpi-label">Mobilidade</span>
                <div class="cop-kpi-value is-warning"><?= $openCriticalRoads . '/' . $totalCriticalRoads ?></div>
                <p>corredores críticos</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#bedsModal">
                <span class="cop-kpi-label">Camas</span>
                <div class="cop-kpi-value is-warning"><?= $overallAvailability ?></div>
                <p>disponíveis</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#externalManpower">
                <span class="cop-kpi-label">Efetivos externos</span>
                <div class="cop-kpi-value is-warning"><?= $externalOccupancy ?></div>
                <p>ativos</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#externalRequestsModal">
                <span class="cop-kpi-label">Pedidos externos</span>
                <div class="cop-kpi-value is-warning"><?= count($activeExternalRequests) ?></div>
                <p>pendentes</p>
            </article>

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#criticalTasksModal">
                <span class="cop-kpi-label">WO críticas</span>
                <div class="cop-kpi-value is-warning"><?= count($criticalTasks) ?></div>
                <p>planeadas</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Meteorologia</span>
                <div class="cop-kpi-value is-warning">ALERTA</div>
                <p>rajadas 70–90 km/h</p>
            </article>
        </section>

        <!-- FIM Zona KPI's-->

        <section class="cop-main">

            <aside class="cop-side cop-side-left">

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Habitabilidade</span>
                            <h3>Camas operacionais</h3>
                        </div>
                        <span class="cop-badge">24h</span>
                    </header>

                    <div class="cop-stat-grid">
                        <div class="cop-stat-box">
                            <span>Total</span>
                            <strong>246</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Ocupadas</span>
                            <strong>118</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Disponíveis</span>
                            <strong>72</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Indisponíveis</span>
                            <strong>56</strong>
                        </div>
                    </div>

                    <div class="cop-note-list">
                        <div class="cop-note-item">
                            <strong>Água</strong>
                            <span>18 camas indisponíveis</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Telhado</strong>
                            <span>22 camas indisponíveis</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Energia</strong>
                            <span>16 camas indisponíveis</span>
                        </div>
                    </div>
                </article>

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Outras unidades</span>
                            <h3>Militares alojados</h3>
                        </div>
                        <span class="cop-badge">Ativo</span>
                    </header>

                    <div class="cop-note-list">
                        <div class="cop-note-item">
                            <strong>FA</strong>
                            <span>14 militares · Bloco A</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Exército</strong>
                            <span>10 militares · Bloco C</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Marinha</strong>
                            <span>4 militares · Bloco B</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>GNR</strong>
                            <span>6 militares · Bloco D</span>
                        </div>
                    </div>
                </article>

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Apoios internos</span>
                            <h3>Capacidades essenciais</h3>
                        </div>
                        <span class="cop-badge">Base</span>
                    </header>

                    <div class="cop-note-list">
                        <div class="cop-note-item">
                            <strong>Banhos quentes</strong>
                            <span>96 ontem · 410 acumulado</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Refeições</strong>
                            <span>210 ontem · 980 acumulado</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Lavandaria</strong>
                            <span>12 pendentes · 34 concluídos</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Situação sanitária</strong>
                            <span>1 avaria crítica no setor sul</span>
                        </div>
                    </div>
                </article>

            </aside>

            <section class="cop-center">
                <article class="cop-card cop-map-card">

                    <header class="cop-module-head cop-map-head">
                        <div>
                            <span class="cop-eyebrow">Common Operational Picture</span>
                            <h3>Mapa operacional da unidade</h3>
                        </div>

                        <div class="cop-map-actions">
                            <span class="cop-badge">Leitura</span>
                            <span class="cop-badge">BA5</span>
                            <span class="cop-badge">Atualização <?= date('H:i') ?></span>
                        </div>
                    </header>

                    <div class="cop-map-wrap">
                        <div class="cop-map-overlay cop-map-overlay-left">
                            <span class="cop-eyebrow">Setor prioritário</span>
                            <strong>Infraestruturas</strong>
                            <p>Fuga de água no refeitório e PT2 condicionado.</p>
                        </div>

                        <div class="cop-map-overlay cop-map-overlay-right">
                            <span class="cop-eyebrow">Estado geral</span>
                            <strong>Operação condicionada</strong>
                            <p>Sem impacto crítico na missão.</p>
                        </div>

                        <div id="cop-map" class="cop-map"></div>
                    </div>
                </article>
            </section>

            <aside class="cop-side cop-side-right">

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Pedidos externos</span>
                            <h3>Apoio solicitado</h3>
                        </div>
                        <span class="cop-badge">11 backlog</span>
                    </header>

                    <div class="cop-note-list">
                        <div class="cop-note-item">
                            <strong>REQ-041</strong>
                            <span>Município · Água · Prioridade A</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>REQ-042</strong>
                            <span>ANPC · Alojamento · Prioridade B</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>REQ-043</strong>
                            <span>Empresa · Energia · Prioridade A</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>REQ-044</strong>
                            <span>GNR · Logística · Prioridade B</span>
                        </div>
                    </div>
                </article>

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Apoio prestado</span>
                            <h3>Output da base</h3>
                        </div>
                        <span class="cop-badge">Hoje</span>
                    </header>

                    <div class="cop-stat-grid cop-stat-grid-2">
                        <div class="cop-stat-box">
                            <span>Banhos</span>
                            <strong>96</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Refeições</span>
                            <strong>210</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Horas máquina</span>
                            <strong>14h</strong>
                        </div>
                        <div class="cop-stat-box">
                            <span>Horas equipa</span>
                            <strong>22h</strong>
                        </div>
                    </div>

                    <div class="cop-note-list">
                        <div class="cop-note-item">
                            <strong>Apoio logístico</strong>
                            <span>12 missões externas concluídas</span>
                        </div>
                        <div class="cop-note-item">
                            <strong>Equipas cedidas</strong>
                            <span>3 equipas destacadas</span>
                        </div>
                    </div>
                </article>

            </aside>

        </section>

        <section class="cop-bottom">
            <article class="cop-card cop-bottom-wide">

                <header class="cop-module-head">
                    <div>
                        <span class="cop-eyebrow">Execução</span>
                        <h3>Top 10 tarefas — WO Tracker</h3>
                    </div>
                </header>

                <div class="cop-table-scroll">
                    <table class="cop-table">
                        <thead>
                        <tr>
                            <th>WO</th>
                            <th>Prioridade</th>
                            <th>Responsável</th>
                            <th>Estado</th>
                            <th>Bloqueio</th>
                            <th>ETA</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>WO-101</td>
                            <td>A</td>
                            <td>Sgt. Matos</td>
                            <td>Em Exec.</td>
                            <td>Sem bloqueio</td>
                            <td>18:20</td>
                        </tr>
                        <tr>
                            <td>WO-102</td>
                            <td>B</td>
                            <td>Cb. Silva</td>
                            <td>Validado</td>
                            <td>Aguarda acesso</td>
                            <td>19:00</td>
                        </tr>
                        <tr>
                            <td>WO-103</td>
                            <td>A</td>
                            <td>1Sar. Costa</td>
                            <td>Em Exec.</td>
                            <td>Material parcial</td>
                            <td>18:45</td>
                        </tr>
                        <tr>
                            <td>WO-104</td>
                            <td>C</td>
                            <td>Eq. Infra</td>
                            <td>Novo</td>
                            <td>Sem bloqueio</td>
                            <td>20:15</td>
                        </tr>
                        <tr>
                            <td>WO-105</td>
                            <td>C</td>
                            <td>Eq. Infra</td>
                            <td>Novo</td>
                            <td>Sem bloqueio</td>
                            <td>20:15</td>
                        </tr>
                        <tr>
                            <td>WO-106</td>
                            <td>C</td>
                            <td>Eq. Infra</td>
                            <td>Novo</td>
                            <td>Sem bloqueio</td>
                            <td>20:15</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="cop-card">
                <header class="cop-module-head">
                    <div>
                        <span class="cop-eyebrow">Coordenação</span>
                        <h3>Decisions Log</h3>
                    </div>
                    <span class="cop-badge">Últimas 10</span>
                </header>

                <table class="cop-table">
                    <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Decisão</th>
                        <th>Decisor</th>
                        <th>Impacto</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>15:40</td>
                        <td>Interditar setor C</td>
                        <td>Cmdt BA5</td>
                        <td>Redução de risco</td>
                    </tr>
                    <tr>
                        <td>16:05</td>
                        <td>Priorizar reparação PT1</td>
                        <td>Of. Dia</td>
                        <td>Recuperação energia</td>
                    </tr>
                    <tr>
                        <td>16:18</td>
                        <td>Reforçar vigilância norte</td>
                        <td>Chefe Seg.</td>
                        <td>Mitigar intrusão</td>
                    </tr>
                    </tbody>
                </table>
            </article>

            <article class="cop-card">
                <header class="cop-module-head">
                    <div>
                        <span class="cop-eyebrow">Risco</span>
                        <h3>Riscos do dia</h3>
                    </div>
                    <span class="cop-badge">Top 5</span>
                </header>

                <table class="cop-table">
                    <thead>
                    <tr>
                        <th>Risco</th>
                        <th>Controlo</th>
                        <th>Resp.</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Fuga de água</td>
                        <td>Isolar setor e reparar</td>
                        <td>Infra</td>
                        <td>Ativo</td>
                    </tr>
                    <tr>
                        <td>Rajadas fortes</td>
                        <td>Restringir zonas expostas</td>
                        <td>Ops</td>
                        <td>Monitorizar</td>
                    </tr>
                    <tr>
                        <td>Via norte bloqueada</td>
                        <td>Desvio logístico</td>
                        <td>Mov</td>
                        <td>Crítico</td>
                    </tr>
                    </tbody>
                </table>
            </article>
        </section>
    </div>


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        MODAL - KPI DAS CAMAS                                                           -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'bedsModal',
    'title' => '<span class="cop-modal-title-text">Situação de Habitabilidade</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary cop-modal-summary-3">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas disponíveis</span>
            <strong class="cop-modal-kpi-value is-success"><?= $overallAvailability ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas ocupadas</span>
            <strong class="cop-modal-kpi-value"><?= $occupiedBeds ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas inoperativas</span>
            <strong class="cop-modal-kpi-value is-warning"><?= 'STANDBY' ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Habitabilidade</span>
        <h6>Alojamentos com camas disponíveis</h6>
    </div>

<?php if ($availableLodgingsProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $availableLodgingsProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    [
                        'label' => 'Alojamento',
                        'value' => function ($model) {
                            return $model->name ?? '—';
                        },
                    ],
                    [
                        'label' => 'Local',
                        'value' => function ($model) {
                            return $model->location->name ?? '—';
                        },
                    ],
                    [
                        'label' => 'Capacidade',
                        'value' => function ($model) {
                            return $model->capacity_total ?? '—';
                        },
                    ],
                    [
                        'label' => 'Ocupadas',
                        'value' => function ($model) {
                            return $model->occupancy() ?? 0;
                        },
                    ],
                    [
                        'label' => 'Disponíveis',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->getCurrentCapacity(false);
                        },
                    ],
                ],

            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não existem alojamentos com camas disponíveis neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                     FIM MODAL - KPI DAS CAMAS                                                          -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        MODAL - KPI DA SEGURANÇA                                                        -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'securityModal',
    'title' => '<span class="cop-modal-title-text">KPI - Incidentes relacionados à segurança</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Incidentes ativos</span>
            <strong class="cop-modal-kpi-value is-warning"><?= count($activeSecurityIncidents) ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Incidentes concluídos</span>
            <strong class="cop-modal-kpi-value is-success"><?= count($closedSecurityIncidents) ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Incidentes ativos</span>
    </div>
<?php if (!empty($activeSecurityIncidents)): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $securityIncidentsProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    [
                        'attribute' => 'title',
                        'label' => 'Título',
                    ],
                    [
                        'label' => 'Local',
                        'value' => 'location.name',
                    ],
                    [
                        'label' => 'Prioridade',
                        'value' => 'priority.description',
                        'contentOptions' => ['class' => 'cop-col-priority'],
                    ],
                    [
                        'label' => 'Estado',
                        'value' => 'statusType.description',
                        'contentOptions' => ['class' => 'cop-col-status'],
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não existem incidentes de segurança ativos neste momento.
    </div>
<?php endif; ?>
<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        FIM MODAL - KPI DA SEGURANÇA                                                    -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        MODAL - KPI DA ÀGUA                                                             -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'waterModal',
    'title' => '<span class="cop-modal-title-text">KPI - Incidentes relacionados à àgua</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Incidentes ativos</span>
            <strong class="cop-modal-kpi-value is-warning"><?= count($activeWaterIncidents) ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Incidentes concluídos</span>
            <strong class="cop-modal-kpi-value is-success"><?= count($closedWaterIncidents) ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Incidentes ativos</span>
    </div>

<?php if ($waterIncidentsProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $waterIncidentsProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    [
                        'attribute' => 'title',
                        'label' => 'Título',
                    ],
                    [
                        'label' => 'Local',
                        'value' => 'location.name',
                    ],
                    [
                        'label' => 'Prioridade',
                        'value' => 'priority.description',
                    ],
                    [
                        'label' => 'Estado',
                        'value' => 'statusType.description',
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não existem incidentes de àgua ativos neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>

    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        FIM MODAL - KPI DA ÀGUA                                                         -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                        MODAL - KPI PEDIDOS EXTERNOS                                                    -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'externalRequestsModal',
    'title' => '<span class="cop-modal-title-text">KPI - Pedidos externos</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Pedidos pendentes</span>
            <strong class="cop-modal-kpi-value is-warning"><?= count($activeExternalRequests) ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Pedidos concluídos</span>
            <strong class="cop-modal-kpi-value is-success"><?= count($closedExternalRequests) ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Coordenação externa</span>
        <h6>Pedidos externos ativos neste momento</h6>
    </div>

<?php if ($externalRequestsProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $externalRequestsProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    [
                        'label' => 'Origem',
                        'value' => 'origin'
                    ],
                    [
                        'label' => 'Detalhes',
                        'value' => 'details',
                    ],
                    [
                        'label' => 'Prioridade',
                        'value' => 'priority.description',
                    ],
                    [
                        'label' => 'Estado',
                        'value' => 'statusType.description',
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não existem pedidos externos ativos neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI PEDIDOS EXTERNOS                                                    -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    MODAL - KPI WO'S CRÍTICAS                                                           -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'criticalTasksModal',
    'title' => '<span class="cop-modal-title-text">KPI - Tarefas Críticas</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Tarefas críticas pendentes</span>
            <strong class="cop-modal-kpi-value is-warning"><?= count($activeCriticalTasks) ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Tarefas críticas concluídas</span>
            <strong class="cop-modal-kpi-value is-success"><?= count($closedCriticalTasks) ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Coordenação externa</span>
        <h6>Tarefas críticas ativas</h6>
    </div>

<?php if ($externalRequestsProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $criticalTasksProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    'title',
                    'description',
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não existem tarefas críticas ativas neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI WO'S CRÍTICAS                                                       -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI EFETIVOS EXTERNOS                                                   -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'externalManpower',
    'title' => '<span class="cop-modal-title-text">KPI - Efetivos externos</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary cop-modal-summary-3">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Efetivos agora</span>
            <strong class="cop-modal-kpi-value is-success"><?= $externalOccupancy ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Diferença H24</span>
            <strong class="cop-modal-kpi-value"><?= $externalOccupancyDifference24H ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">manpower atual</span>
    </div>

<?php if ($externalOccupancyProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $externalOccupancyProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                    [
                        'label' => 'Ramo',
                        'value' => 'unit.branch.description'
                    ],
                    [
                        'label' => 'Unidade',
                        'value' => 'unit.name'
                    ],
                    'people_count',
                    [
                        'attribute' => 'checkin_at',
                        'format' => ['date', 'php:dMy'],
                    ]
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não há efetivos externos neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI EFETIVOS EXTERNOS                                                   -->
    <!---------------------------------------------------------------------------------------------------------------------------->


    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    MODAL - KPI MOBILIDADE                                                              -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'mobilityModal',
    'title' => '<span class="cop-modal-title-text">KPI - Mobilidade</span>',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]);
?>

    <div class="cop-modal-summary cop-modal-summary-3">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Corredores Críticos</span>
            <strong class="cop-modal-kpi-value is-success"><?= $openCriticalRoads . '/' . $totalCriticalRoads ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Estacionamentos Críticos</span>
            <strong class="cop-modal-kpi-value"><?= $openCriticalParkings . '/' . $totalCriticalParkings ?></strong>
        </div>
    </div>

<?php if ($externalOccupancyProvider->getCount() > 0): ?>
    <div class="row">
        <div class="col-md-6">
            <div class="cop-modal-table-wrap">
                <div class="cop-modal-section-head">
                    <span class="cop-eyebrow">STATUS DOS CORREDORES</span>
                </div>
                <div class="cop-modal-table-scroll">
                    <?= GridView::widget([
                        'dataProvider' => $criticalRoadsProvider,
                        'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                        'layout' => '{items}',
                        'summary' => '',
                        'columns' => [
                            'name',
                            [
                                'label' => 'Status',
                                'value' => 'statusType.description',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="cop-modal-table-wrap">
                <div class="cop-modal-section-head">
                    <span class="cop-eyebrow">STATUS DOS ESTACIONAMENTOS</span>
                </div>
                <div class="cop-modal-table-scroll">
                    <?= GridView::widget([
                        'dataProvider' => $criticalParkingsProvider,
                        'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                        'layout' => '{items}',
                        'summary' => '',
                        'columns' => [
                            'name',
                            [
                                'label' => 'Status',
                                'value' => 'statusType.description',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
        </div>

    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não há efetivos externos neste momento.
    </div>
<?php endif; ?>
    </div>



<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI MOBILIDADE                                                          -->
    <!---------------------------------------------------------------------------------------------------------------------------->

<?php
$this->registerJs('initCopMapReadOnly(' . Json::htmlEncode($copMapOptions) . ');');
?>