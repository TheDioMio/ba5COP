<?php

use common\assets\CopMapReadOnlyAsset;
use frontend\assets\DashboardAsset;
use yii\bootstrap5\Modal;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use common\assets\MeteoAsset;

$this->title = 'COP BA5';
$this->params['breadcrumbs'] = [];
$this->params['bodyClass'] = 'dashboard-page-body';

DashboardAsset::register($this);
MeteoAsset::register($this);

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

            <article class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#perimeterModal">
                <span class="cop-kpi-label">Perímetro</span>
                <div class="cop-kpi-value is-warning"><?= $perimeterPercentage ?>%</div>
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

            <article id="meteoCard" class="cop-kpi-card kpi-clickable"
                     role="region"
                     tabindex="0"
                     data-bs-toggle="modal"
                     data-bs-target="#meteoModal">
                <div class="cop-kpi-head">
                    <span class="cop-kpi-label">Meteorologia</span>
                    <small id="meteoTime" class="cop-kpi-time">--:--</small>
                </div>
                <div id="meteoStatus" class="cop-kpi-value is-success">---</div>
                <p id="meteoSummary">LOADING...</p>
            </article>
        </section>

        <!-- FIM Zona KPI's-->

        <section class="cop-main">

            <aside class="cop-side cop-side-left">

                <article class="cop-card cop-module cop-systems-card">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Estado de Sistemas</span>
                            <h3>NAVAids</h3>
                        </div>
                        <div class="cop-systems-meta">
                            <span class="cop-systems-meta-label">Última atualização</span>
                            <strong><?= date('Y-m-d H:i') ?></strong>
                        </div>
                    </header>

                    <div class="cop-systems-list">
                        <?php if (!empty($navIDsArray)): ?>
                            <?php foreach ($navIDsArray as $navAid): ?>
                                <?php
                                $statusRaw = $navAid->statusType->description ?? null;

                                // Badge + label
                                $badgeClass = 'cop-badge-warning';
                                $statusLabel = '—';

                                switch ($statusRaw) {
                                    case 'GREEN':
                                        $badgeClass = 'cop-badge-success';
                                        $statusLabel = 'OK';
                                        $defaultText = 'Operacional';
                                        break;

                                    case 'YELLOW':
                                        $badgeClass = 'cop-badge-warning';
                                        $statusLabel = 'ALERTA';
                                        $defaultText = 'Condicionado';
                                        break;

                                    case 'RED':
                                        $badgeClass = 'cop-badge-danger';
                                        $statusLabel = 'CRÍTICO';
                                        $defaultText = 'Indisponível';
                                        break;

                                    default:
                                        $defaultText = 'Sem informação';
                                }

                                // Texto final (notes têm prioridade)
                                $statusText = !empty($navAid->notes) ? $navAid->notes : $defaultText;
                                ?>

                                <div class="cop-system-row">
                                    <div class="cop-system-main">
                                        <span class="cop-system-name"><?= $navAid->name ?></span>
                                        <small class="cop-system-detail"><?= $statusText ?></small>
                                    </div>
                                    <span class="cop-badge <?= $badgeClass ?>"><?= $statusLabel ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="cop-empty-state">
                                Não existem sistemas NAVAids registados neste momento.
                            </div>
                        <?php endif; ?>
                    </div>
                </article>

            </aside>

            <section class="cop-center">
                <article class="cop-card cop-map-card">
                    <div class="cop-map-wrap">
                        <div id="cop-map" class="cop-map"></div>
                    </div>
                </article>
            </section>
            <aside class="cop-side cop-side-right">

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Pedidos externos</span>
                        </div>
                        <span class="cop-badge">11 backlog</span>
                    </header>

                    <div class="cop-request-kpis">
                        <div class="cop-request-kpi">
                            <span class="cop-kpi-label">Novos (24h)</span>
                            <div class="cop-kpi-value"><?= count($newExternalRequests) ?></div>
                        </div>

                        <div class="cop-request-kpi">
                            <span class="cop-kpi-label">Em análise</span>
                            <div class="cop-kpi-value is-warning"><?= count($inAnalisisExternalRequests) ?></div>
                        </div>

                        <div class="cop-request-kpi">
                            <span class="cop-kpi-label">Aprovados</span>
                            <div class="cop-kpi-value is-success"><?= count($acceptedExternalRequests) ?></div>
                        </div>

                        <div class="cop-request-kpi">
                            <span class="cop-kpi-label">Recusados</span>
                            <div class="cop-kpi-value is-danger"><?= count($rejectedExternalRequests) ?></div>
                        </div>
                    </div>
                </article>

                <article class="cop-card cop-module">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Apoios</span>
                            <h3>Apoios Internos</h3>
                        </div>

                        <div class="cop-support-switch" role="tablist">
                            <button type="button"
                                    class="cop-support-switch-btn is-active"
                                    data-support-target="internal"
                                    title="Apoios internos">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                            </button>

                            <button type="button"
                                    class="cop-support-switch-btn"
                                    data-support-target="external"
                                    title="Apoios externos">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            </button>

                            <button type="button"
                                    class="cop-support-switch-btn"
                                    data-support-target="combined"
                                    title="Visão geral">
                                <i class="fa-solid fa-globe"></i>
                            </button>
                        </div>
                    </header>

                    <div class="cop-support-panels">
                        <!-- INTERNOS -->
                        <div class="cop-support-panel is-active" data-support-panel="internal">
                            <div class="cop-support-grid">
                                <div class="cop-support-item">
                                    <strong>Banhos</strong>
                                    <div class="cop-support-values">
                                        <span><b><?=$bathsGivenOntInternal ?? 0?></b> ontem</span>
                                        <span><b><?=$bathsGivenHjInternal ?? 0?></b> hoje</span>
                                        <span><b><?=$bathsGivenAccInternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Refeições</strong>
                                    <div class="cop-support-values">
                                        <span><b><?=$mealsGivenOntInternal ?? 0?></b> ontem</span>
                                        <span><b><?=$mealsGivenHjInternal ?? 0?></b> hoje</span>
                                        <span><b><?=$mealsGivenAccInternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Camas</strong>
                                    <div class="cop-support-values">
                                        <span><b><?= $bedsGivenOntInternal ?? 0?></b> ontem</span>
                                        <span><b><?= $bedsGivenHjInternal ?? 0?></b> hoje</span>
                                        <span><b><?= $bedsGivenAccInternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- EXTERNOS -->
                        <div class="cop-support-panel" data-support-panel="external">
                            <div class="cop-support-grid">
                                <div class="cop-support-item">
                                    <strong>Banhos</strong>
                                    <div class="cop-support-values">
                                        <span><b><?=$bathsGivenOntExternal ?? 0?></b> ontem</span>
                                        <span><b><?=$bathsGivenHjExternal ?? 0?></b> hoje</span>
                                        <span><b><?=$bathsGivenAccExternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Refeições</strong>
                                    <div class="cop-support-values">
                                        <span><b><?= $mealsGivenOntExternal ?? 0?></b> ontem</span>
                                        <span><b><?= $mealsGivenHjExternal ?? 0?></b> hoje</span>
                                        <span><b><?= $mealsGivenAccExternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Camas</strong>
                                    <div class="cop-support-values">
                                        <span><b><?= $bedsGivenOntExternal ?? 0?></b> ontem</span>
                                        <span><b><?= $bedsGivenHjExternal ?? 0?></b> hoje</span>
                                        <span><b><?= $bedsGivenAccExternal ?? 0?></b> acumulado</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- GERAL -->
                        <div class="cop-support-panel" data-support-panel="combined">
                            <div class="cop-support-grid">
                                <div class="cop-support-item">
                                    <strong>Banhos</strong>
                                    <div class="cop-support-values">
                                        <span><b><?=$bathsGivenOntOverall ?? 0?></b> ontem</span>
                                        <span><b><?=$bathsGivenHjOverall ?? 0?></b> hoje</span>
                                        <span><b><?=$bathsGivenAccOverall ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Refeições</strong>
                                    <div class="cop-support-values">
                                        <span><b><?=$mealsGivenOntOverall ?? 0?></b> ontem</span>
                                        <span><b><?=$mealsGivenHjOverall ?? 0?></b> hoje</span>
                                        <span><b><?=$mealsGivenAccOverall ?? 0?></b> acumulado</span>
                                    </div>
                                </div>

                                <div class="cop-support-item">
                                    <strong>Camas</strong>
                                    <div class="cop-support-values">
                                        <span><b><?= $bedsGivenOntOverall ?? 0?></b> ontem</span>
                                        <span><b><?= $bedsGivenHjOverall ?? 0?></b> hoje</span>
                                        <span><b><?= $bedsGivenAccOverall ?? 0?></b> acumulado</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </article>

                <article class="cop-card cop-module cop-sanitary-card">
                    <header class="cop-module-head">
                        <div>
                            <span class="cop-eyebrow">Situação sanitária</span>
                            <h3>Estado sanitário e restrições</h3>
                        </div>
                        <span class="cop-badge cop-badge-warning">Atenção</span>
                    </header>

                    <div class="cop-sanitary-grid">

                        <div class="cop-sanitary-item is-danger">
                            <span class="cop-sanitary-label">Avarias críticas</span>
                            <strong class="cop-sanitary-value">1</strong>
                        </div>

                        <div class="cop-sanitary-item is-warning">
                            <span class="cop-sanitary-label">Restrições de uso</span>
                            <strong class="cop-sanitary-value">2</strong>
                            <small>balneários condicionados</small>
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
                            <th>ETC</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($latest10Tasks)): ?>
                            <?php foreach ($latest10Tasks as $task): ?>
                                <tr>
                                    <td><?= $task->id ?? '—' ?></td>

                                    <td>
                                        <?= $task->priority->description ?? '—' ?>
                                    </td>

                                    <td>
                                        <?= $task->assigned_to ?? '—' ?>
                                    </td>

                                    <td>
                                        <?= $task->statusType->description ?? '—' ?>
                                    </td>

                                    <td>
                                        <?= $task->block_reason ?? 'Sem bloqueio' ?>
                                    </td>
                                    <td>
                                        <?= $task->due_at?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Não existem tarefas no momento.
                                </td>
                            </tr>
                        <?php endif; ?>

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
                <div class="cop-table-scroll">
                    <table class="cop-table">
                        <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Decisão</th>
                            <th>Decisor</th>
                            <th>Impacto</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($latest10Decisions)): ?>
                            <?php foreach ($latest10Decisions as $decision): ?>
                                <tr>
                                    <td>
                                        <?= strtoupper(date('dMY H:i', strtotime($decision->decided_at))) ?>
                                    </td>

                                    <td>
                                        <?=$decision->reason?>
                                    </td>

                                    <td>
                                        <?=$decision->decidedBy->username?>
                                    </td>

                                    <td>
                                        <?=$decision->impact?>
                                    </td>

                                    <td>
                                        <?=$decision->statusType->description?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    Não existem decisões no momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
                    <?php if (!empty($dailyRisks)): ?>
                        <?php foreach ($dailyRisks as $risk): ?>
                            <?php
                            $taskTitles = [];
                            $assignedUsers = [];

                            foreach ($risk->tasks as $task) {
                                $taskTitles[] = $task->title;

                                if ($task->assignedTo) {
                                    $assignedUsers[] = $task->assignedTo->username;
                                }
                            }

                            $taskTitlesText = !empty($taskTitles)
                                ? implode(', ', $taskTitles)
                                : '-';

                            $assignedUsersText = !empty($assignedUsers)
                                ? implode(', ', array_unique($assignedUsers))
                                : '-';
                            ?>
                            <tr>
                                <td><?=Html::encode($risk->title) ?></td>

                                <td>
                                    <?=Html::encode($taskTitlesText) ?>
                                </td>

                                <td>
                                    <?=Html::encode($assignedUsersText) ?>
                                </td>

                                <td>
                                    <?=Html::encode($risk->statusType->description) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">
                                Não existem riscos do dia no momento.
                            </td>
                        </tr>
                    <?php endif; ?>

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

    <div class="cop-modal-summary-4">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas operacionais</span>
            <strong class="cop-modal-kpi-value">246</strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas ocupadas</span>
            <strong class="cop-modal-kpi-value">118</strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas disponíveis</span>
            <strong class="cop-modal-kpi-value is-success">128</strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Camas indisponíveis</span>
            <strong class="cop-modal-kpi-value is-warning">56</strong>
        </div>
    </div>

    <div class="cop-beds-dual-grid mb-4">

        <div class="cop-modal-section-card cop-beds-panel">
            <div class="cop-modal-section-head cop-beds-panel-head">
                <span class="cop-eyebrow">Indisponibilidade</span>
                <h6>Motivos atuais</h6>
            </div>

            <div class="cop-breakdown-grid">
                <div class="cop-breakdown-cell">
                    <span class="cop-breakdown-label">Telhado aberto</span>
                    <strong class="cop-breakdown-value">22</strong>
                    <small>camas</small>
                </div>

                <div class="cop-breakdown-cell">
                    <span class="cop-breakdown-label">Falha de água</span>
                    <strong class="cop-breakdown-value">18</strong>
                    <small>camas</small>
                </div>

                <div class="cop-breakdown-cell">
                    <span class="cop-breakdown-label">Falha elétrica</span>
                    <strong class="cop-breakdown-value">10</strong>
                    <small>camas</small>
                </div>

                <div class="cop-breakdown-cell">
                    <span class="cop-breakdown-label">Risco estrutural</span>
                    <strong class="cop-breakdown-value">6</strong>
                    <small>camas</small>
                </div>
            </div>
        </div>

        <div class="cop-modal-section-card cop-beds-panel">
            <div class="cop-modal-section-head cop-beds-panel-head">
                <span class="cop-eyebrow">Tendência 24h</span>
                <h6>Recuperação / perda provável</h6>
            </div>

            <div class="cop-trend-grid-modal">
                <div class="cop-trend-box-modal is-positive">
                    <span>Camas a recuperar</span>
                    <strong>+20</strong>
                    <small>Bloco B após reposição elétrica e limpeza</small>
                </div>

                <div class="cop-trend-box-modal is-negative">
                    <span>Camas a perder se chover</span>
                    <strong>-10</strong>
                    <small>Bloco D com infiltrações ativas</small>
                </div>
            </div>
        </div>

    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Habitabilidade</span>
        <h6>Estado por alojamento</h6>
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
                        'value' => function ($model) {
                            return $model->getCurrentCapacity(false) ?? 0;
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
    <!--                                    MODAL - KPI EFETIVOS EXTERNOS                                                         -->
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

    <div class="cop-modal-summary cop-modal-summary-4">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Efetivos agora</span>
            <strong class="cop-modal-kpi-value is-success"><?= $externalOccupancy ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Entradas totais H24</span>
            <strong class="cop-modal-kpi-value"><?= $externalEntries24H ?? 0 ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Saídas totais H24</span>
            <strong class="cop-modal-kpi-value"><?= $externalExits24H ?? 0 ?></strong>
        </div>

        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">Diferença H24</span>
            <strong class="cop-modal-kpi-value is-warning"><?= $externalOccupancyDifference24H ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Quadro obrigatório</span>
        <h6>Militares de outras unidades alojados</h6>
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
                        'value' => function ($model) {
                            return $model->unit->branch->description ?? '—';
                        },
                    ],
                    [
                        'label' => 'Unidade de origem',
                        'value' => function ($model) {
                            return $model->unit->name ?? '—';
                        },
                    ],
                    [
                        'label' => 'Efetivo alojado',
                        'value' => function ($model) {
                            return $model->people_count ?? 0;
                        },
                    ],
                    [
                        'label' => 'Local de alojamento',
                        'value' => function ($model) {
                            return $model->lodgingSite->name ?? '—';
                        },
                    ],
                ],
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="cop-empty-state">
        Não há efetivos externos alojados neste momento.
    </div>
<?php endif; ?>

<?php Modal::end(); ?>
    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    FIM MODAL - KPI EFETIVOS EXTERNOS                                                     -->
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





    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    MODAL - KPI VEDAÇÃO                                                              -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'perimeterModal',
    'title' => '<span class="cop-modal-title-text">KPI - Vedação</span>',
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

    <div class="cop-modal-summary text-center">
        <div class="cop-modal-kpi">
            <span class="cop-modal-kpi-label">% da vedação operacional</span>
            <strong class="cop-modal-kpi-value is-warning"><?= $perimeterPercentage . '%' ?></strong>
        </div>
    </div>

    <div class="cop-modal-section-head">
        <span class="cop-eyebrow">Incidentes ativos</span>
    </div>

<?php if ($inopPerimeterProvider->getCount() > 0): ?>
    <div class="cop-modal-table-wrap">
        <div class="cop-modal-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $inopPerimeterProvider,
                'tableOptions' => ['class' => 'table cop-modal-table align-middle mb-0'],
                'layout' => '{items}',
                'summary' => '',
                'columns' => [
                        'name',
                    [
                            'label' => 'status',
                        'value' => 'statusType.description'
                    ],
                    'notes'
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
    <!--                                    FIM MODAL - KPI VEDAÇÃO                                                          -->
    <!---------------------------------------------------------------------------------------------------------------------------->





    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    MODAL - KPI METEO                                                              -->
    <!---------------------------------------------------------------------------------------------------------------------------->
<?php Modal::begin([
    'id' => 'meteoModal',
    'title' => '
    <div class="d-flex align-items-center justify-content-between">
        
        <span class="cop-modal-title-text pe-3">KPI - Meteorologia</span>

        <div class="cop-support-switch-meteo" role="tablist">
            <button type="button" class="cop-support-switch-btn is-active" data-support-target="hourly" title="1H">
                <i class="fa-solid fa-clock"></i></i>
            </button>
            <button type="button" class="cop-support-switch-btn" data-support-target="daily" title="24H">
                <i class="fa-solid fa-calendar-day"></i>
            </button>
        </div>
    </div>
',
    'size' => Modal::SIZE_LARGE,
    'centerVertical' => true,
    'options' => ['class' => 'fade cop-modal'],
    'headerOptions' => ['class' => 'cop-modal-header'],
    'bodyOptions' => ['class' => 'cop-modal-body'],
    'closeButton' => [
        'class' => 'btn-close btn-close-white cop-modal-close',
        'aria-label' => 'Fechar',
    ],
]); ?>
<!------------------------------------------HOURLY-------------------------------------------------------------------->
    <div class="is-active cop-support-panel" data-support-panel="hourly">
        <div class="cop-modal-summary cop-modal-summary-4">
            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Estado</span>
                <strong id="meteoModalStatus" class="cop-modal-kpi-value is-success">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Regras de Voo</span>
                <strong id="meteoModalFltCat" class="cop-modal-kpi-value">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Observação</span>
                <strong id="meteoModalReportTime" class="cop-modal-kpi-value">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Estação</span>
                <strong id="meteoModalStation" class="cop-modal-kpi-value">--</strong>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Condição geral</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Teto e Visibilidade</span>
                            <strong id="meteoModalCover" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Visibilidade</span>
                            <strong id="meteoModalVisib" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Tipo de relatório</span>
                            <strong id="meteoModalType" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Vento</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Direção</span>
                            <strong id="meteoModalWdir" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Velocidade</span>
                            <strong id="meteoModalWspd" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Direção (graus)</span>
                            <strong id="meteoModalWdirDeg" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Resumo</span>
                            <strong id="meteoModalWindSummary" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Atmosfera</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Temperatura</span>
                            <strong id="meteoModalTemp" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Ponto de orvalho</span>
                            <strong id="meteoModalDewp" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">QNH</span>
                            <strong id="meteoModalAltim" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Elevação</span>
                            <strong id="meteoModalElev" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Estação</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">ICAO</span>
                            <strong id="meteoModalIcao" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Nome</span>
                            <strong id="meteoModalName" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Latitude</span>
                            <strong id="meteoModalLat" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Longitude</span>
                            <strong id="meteoModalLon" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cop-modal-section-head">
            <span class="cop-eyebrow">METAR bruto</span>
        </div>

        <div class="cop-modal-section-card">
            <code id="meteoModalRawOb" style="display:block; white-space:normal;">--</code>
        </div>
    </div>
<!------------------------------------------ FIM HOURLY---------------------------------------------------------------->



<!------------------------------------------ DAILY--------------------------------------------------------------------->
    <div class="cop-support-panel" data-support-panel="daily">
        <div class="cop-modal-summary cop-modal-summary-4">
            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Risco 24H</span>
                <strong id="meteoForecastStatus" class="cop-modal-kpi-value is-warning">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Emitido</span>
                <strong id="meteoForecastIssued" class="cop-modal-kpi-value">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Validade</span>
                <strong id="meteoForecastValidity" class="cop-modal-kpi-value">--</strong>
            </div>

            <div class="cop-modal-kpi">
                <span class="cop-modal-kpi-label">Aeródromo</span>
                <strong id="meteoForecastStation" class="cop-modal-kpi-value">LPMR</strong>
            </div>
        </div>

        <div class="cop-modal-section-card mb-4">
            <div class="cop-modal-section-head">
                <span class="cop-eyebrow">Síntese operacional</span>
                <h6>Leitura rápida da previsão</h6>
            </div>

            <div class="cop-trend-grid-modal">
                <div class="cop-trend-box-modal">
                    <span>Estado base</span>
                    <strong id="meteoForecastBase">--</strong>
                    <small id="meteoForecastBaseDetail">--</small>
                </div>

                <div class="cop-trend-box-modal is-warning">
                    <span>Principal agravamento</span>
                    <strong id="meteoForecastRisk">--</strong>
                    <small id="meteoForecastRiskDetail">--</small>
                </div>

                <div class="cop-trend-box-modal is-positive">
                    <span>Evolução provável</span>
                    <strong id="meteoForecastTrend">--</strong>
                    <small id="meteoForecastTrendDetail">--</small>
                </div>
            </div>
        </div>

        <div class="cop-modal-section-head">
            <span class="cop-eyebrow">Janela temporal</span>
            <h6>Evolução prevista por períodos</h6>
        </div>

        <div class="cop-modal-section-card mb-4">
            <div id="meteoForecastTimeline" class="cop-systems-list">
                <div class="cop-empty-state">
                    A carregar previsão meteorológica...
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Indicadores principais</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Vento base</span>
                            <strong id="meteoForecastWind" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Rajada máx.</span>
                            <strong id="meteoForecastMaxGust" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Visibilidade min.</span>
                            <strong id="meteoForecastMinVisib" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Fenómeno crítico</span>
                            <strong id="meteoForecastWx" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="cop-modal-section-card h-100">
                    <div class="cop-modal-section-head">
                        <span class="cop-eyebrow">Estação</span>
                    </div>

                    <div class="cop-breakdown-grid">
                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">ICAO</span>
                            <strong id="meteoForecastIcao" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Nome</span>
                            <strong id="meteoForecastName" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Latitude</span>
                            <strong id="meteoForecastLat" class="cop-breakdown-value">--</strong>
                        </div>

                        <div class="cop-breakdown-cell">
                            <span class="cop-breakdown-label">Longitude</span>
                            <strong id="meteoForecastLon" class="cop-breakdown-value">--</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cop-modal-section-head">
            <span class="cop-eyebrow">TAF bruto</span>
        </div>
        <div class="cop-modal-section-card">
            <code id="meteoForecastRaw" style="display:block; white-space:pre-line;">--</code>
        </div>
<!------------------------------------------ FIM DAILY ---------------------------------------------------------------->
    </div>
<?php Modal::end(); ?>

    <!---------------------------------------------------------------------------------------------------------------------------->
    <!--                                    MODAL - KPI METEO                                                              -->
    <!---------------------------------------------------------------------------------------------------------------------------->






<!--JS para correr o mapa-->
<?php
$this->registerJs('initCopMapReadOnly(' . Json::htmlEncode($copMapOptions) . ');');
?>

<!--JS para switch de botões no KPI de apoios (lateral direita)-->
<?php
$this->registerJs(<<<JS
document.querySelectorAll('.cop-support-switch').forEach(function (switchEl) {
    const buttons = switchEl.querySelectorAll('.cop-support-switch-btn');
    const module = switchEl.closest('.cop-module');
    if (!module) return;

    const panels = module.querySelectorAll('.cop-support-panel');
    const titleEl = module.querySelector('h3');

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = btn.dataset.supportTarget;

            buttons.forEach(function (b) {
                b.classList.remove('is-active');
            });

            panels.forEach(function (panel) {
                panel.classList.remove('is-active');
            });

            btn.classList.add('is-active');

            const activePanel = module.querySelector('.cop-support-panel[data-support-panel="' + target + '"]');
            if (activePanel) {
                activePanel.classList.add('is-active');
            }

            if (titleEl) {
                if (target === 'internal') {
                    titleEl.textContent = 'Apoios Internos';
                } else if (target === 'external') {
                    titleEl.textContent = 'Apoios Externos';
                } else if (target === 'combined') {
                    titleEl.textContent = 'Visão Geral';
                }
            }
        });
    });
});
JS);
?>

<!--JS para switch de botões no KPI de meteo-->
<?php
$this->registerJs(<<<JS
document.querySelectorAll('.cop-support-switch-meteo').forEach(function (switchEl) {
    const buttons = switchEl.querySelectorAll('.cop-support-switch-btn');

    // vai ao modal inteiro
    const modal = switchEl.closest('.modal-content, .modal, .cop-modal');
    if (!modal) return;

    // procura os painéis deste modal
    const panels = modal.querySelectorAll('.cop-support-panel[data-support-panel]');

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = btn.dataset.supportTarget;

            buttons.forEach(function (b) {
                b.classList.remove('is-active');
            });

            panels.forEach(function (panel) {
                panel.classList.remove('is-active');
            });

            btn.classList.add('is-active');

            const activePanel = modal.querySelector('.cop-support-panel[data-support-panel="' + target + '"]');
            if (activePanel) {
                activePanel.classList.add('is-active');
            }
        });
    });
});
JS);
?>
