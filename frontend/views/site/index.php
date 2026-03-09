<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'BA5 COP';

$kpis = [
    ['label' => 'Ocorrências ativas', 'value' => '03', 'meta' => '2 operacionais · 1 logística'],
    ['label' => 'Pedidos em curso', 'value' => '11', 'meta' => '4 com prioridade elevada'],
    ['label' => 'Equipas destacadas', 'value' => '06', 'meta' => 'ECSI · Infra · Segurança'],
    ['label' => 'Estado da base', 'value' => 'Estável', 'meta' => 'Sem constrangimentos críticos'],
];

$alerts = [
    ['time' => 'Agora', 'title' => 'Fuga de água assinalada', 'text' => 'Bloco B com intervenção em curso e isolamento parcial do setor.'],
    ['time' => 'Há 12 min', 'title' => 'Pedido validado pela cadeia de comando', 'text' => 'Substituição de equipamento de rede encaminhada para execução.'],
    ['time' => 'Há 27 min', 'title' => 'Atualização de perímetro', 'text' => 'Patrulha concluída sem anomalias no setor sul.'],
];

$tasks = [
    ['title' => 'Reparar fuga no Bloco B', 'owner' => 'Infraestruturas', 'priority' => 'Alta', 'state' => 'Em execução'],
    ['title' => 'Validar switch de redundância', 'owner' => 'ECSI', 'priority' => 'Alta', 'state' => 'Aguarda janela técnica'],
    ['title' => 'Inspecionar acesso secundário', 'owner' => 'Segurança', 'priority' => 'Média', 'state' => 'Planeado'],
];
?>

<div class="ba5-home">
    <section class="hero-panel">
        <div class="hero-copy">
            <span class="hero-eyebrow">Common Operational Picture</span>
            <h1>Consciência situacional centralizada para a BA5.</h1>
            <p class="hero-text">
                Um frontend pensado para concentrar mapa, incidentes, pedidos, equipas e estado operacional
                numa vista clara, rápida e preparada para evolução no Yii2.
            </p>

            <div class="hero-actions">
                <?= Html::a('Abrir COP', ['/site/cop'], ['class' => 'btn btn-ba5-primary btn-lg']) ?>
                <?= Html::a('Entrar no sistema', ['/site/login'], ['class' => 'btn btn-ba5-secondary btn-lg']) ?>
            </div>

            <div class="hero-tags">
                <span>Mapa central</span>
                <span>Incidentes e pedidos</span>
                <span>Fluxo operacional</span>
                <span>Yii2 + Leaflet</span>
            </div>
        </div>

        <div class="hero-map-card">
            <div class="map-card-top">
                <div>
                    <p class="mini-label">Vista principal</p>
                    <h2>Pré-visualização COP</h2>
                </div>
                <span class="status-pill">Online</span>
            </div>

            <div class="map-preview">
                <div class="map-grid"></div>
                <div class="map-overlay map-overlay-a"></div>
                <div class="map-overlay map-overlay-b"></div>
                <div class="map-marker marker-1">Hangar</div>
                <div class="map-marker marker-2">Torre</div>
                <div class="map-marker marker-3">Bloco B</div>
                <div class="map-marker marker-4">Portão Sul</div>
            </div>

            <div class="map-card-footer">
                <div>
                    <span class="mini-label">Modo atual</span>
                    <strong>Planta operacional</strong>
                </div>
                <div>
                    <span class="mini-label">Pronto para</span>
                    <strong>Leaflet + camadas dinâmicas</strong>
                </div>
            </div>
        </div>
    </section>

    <section class="kpi-grid">
        <?php foreach ($kpis as $item): ?>
            <article class="info-card kpi-card">
                <span class="mini-label"><?= Html::encode($item['label']) ?></span>
                <div class="kpi-value"><?= Html::encode($item['value']) ?></div>
                <p><?= Html::encode($item['meta']) ?></p>
            </article>
        <?php endforeach; ?>
    </section>

    <section class="content-grid">
        <article class="info-card panel-card panel-card-wide">
            <div class="panel-head">
                <div>
                    <span class="mini-label">Feed operacional</span>
                    <h3>Últimas atualizações</h3>
                </div>
                <?= Html::a('Ver COP', ['/site/cop'], ['class' => 'panel-link']) ?>
            </div>

            <div class="timeline-list">
                <?php foreach ($alerts as $alert): ?>
                    <div class="timeline-item">
                        <div class="timeline-time"><?= Html::encode($alert['time']) ?></div>
                        <div class="timeline-body">
                            <strong><?= Html::encode($alert['title']) ?></strong>
                            <p><?= Html::encode($alert['text']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>

        <article class="info-card panel-card">
            <div class="panel-head">
                <div>
                    <span class="mini-label">Quick actions</span>
                    <h3>Acessos rápidos</h3>
                </div>
            </div>

            <div class="quick-actions">
                <?= Html::a('Abrir mapa operacional', ['/site/cop'], ['class' => 'quick-action']) ?>
                <?= Html::a('Consultar contacto', ['/site/contact'], ['class' => 'quick-action']) ?>
                <?= Html::a('Sobre o sistema', ['/site/about'], ['class' => 'quick-action']) ?>
                <?= Html::a('Autenticação', ['/site/login'], ['class' => 'quick-action']) ?>
            </div>
        </article>

        <article class="info-card panel-card panel-card-wide">
            <div class="panel-head">
                <div>
                    <span class="mini-label">Execução</span>
                    <h3>Tarefas prioritárias</h3>
                </div>
            </div>

            <div class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <div class="task-row">
                        <div>
                            <strong><?= Html::encode($task['title']) ?></strong>
                            <p><?= Html::encode($task['owner']) ?></p>
                        </div>
                        <div class="task-badges">
                            <span><?= Html::encode($task['priority']) ?></span>
                            <span><?= Html::encode($task['state']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </section>
</div>
