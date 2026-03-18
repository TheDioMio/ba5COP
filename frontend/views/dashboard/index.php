<?php

use common\assets\CopMapReadOnlyAsset;
use frontend\assets\DashboardAsset;
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
            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Segurança</span>
                <div class="cop-kpi-value is-warning"><?=count($securityIncidents)?></div>
                <p>incidentes</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Perímetro</span>
                <div class="cop-kpi-value is-success">92%</div>
                <p>vedação operacional</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Energia</span>
                <div class="cop-kpi-value is-warning">78%</div>
                <p>base energizada</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Água</span>
                <div class="cop-kpi-value is-warning"><?=count($waterIncidents)?></div>
                <p>fugas ativas</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Mobilidade</span>
                <div class="cop-kpi-value is-warning">4/6</div>
                <p>corredores críticos</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Camas</span>
                <div class="cop-kpi-value is-warning"><?=$overallAvailability?></div>
                <p>disponíveis</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Efetivos externos</span>
                <div class="cop-kpi-value is-success">34</div>
                <p>outras unidades</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">Pedidos backlog</span>
                <div class="cop-kpi-value is-warning">11</div>
                <p>pendentes</p>
            </article>

            <article class="cop-kpi-card">
                <span class="cop-kpi-label">WO críticas</span>
                <div class="cop-kpi-value is-warning">6/10</div>
                <p>concluídas vs planeadas</p>
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

<?php
$this->registerJs('initCopMapReadOnly(' . Json::htmlEncode($copMapOptions) . ');');
?>