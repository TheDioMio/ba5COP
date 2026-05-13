<?php

use common\assets\CopMapAsset;
use common\assets\CopMapReadOnlyAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$editAsset = CopMapAsset::register($this);
$viewAsset = CopMapReadOnlyAsset::register($this);

$imageUrl = $editAsset->baseUrl . '/img/img_mapa.jpg';
$mapIconsBaseUrl = $viewAsset->baseUrl . '/img/map-icons';

$idx = Url::to(['/location/map-index']);
$crt = Url::to(['/location/map-create']);
$upd = Url::to(['/location/map-update', 'id' => '__ID__']);
$del = Url::to(['/location/map-delete', 'id' => '__ID__']);

$lcrt = Url::to(['/lodging-site/map-create']);
$lupd = Url::to(['/lodging-site/map-update', 'id' => '__ID__']);
$ldel = Url::to(['/lodging-site/map-delete', 'id' => '__ID__']);

$csrf = Yii::$app->request->csrfToken;

$this->title = '    ';
?>

<div class="container-fluid cop-admin-home">
    <!-- KPI ROW -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="cop-stat-card">
                <div class="cop-stat-card__icon bg-blue">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="cop-stat-card__body">
                    <div class="cop-stat-card__label">Localizações</div>
                    <div class="cop-stat-card__value"><?= Html::encode($locationsCount ?? 0) ?></div>
                    <div class="cop-stat-card__meta">registadas no sistema</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="cop-stat-card">
                <div class="cop-stat-card__icon bg-green">
                    <i class="fas fa-bed"></i>
                </div>
                <div class="cop-stat-card__body">
                    <div class="cop-stat-card__label">Alojamentos</div>
                    <div class="cop-stat-card__value"><?= Html::encode($lodgingSitesCount ?? 0) ?></div>
                    <div class="cop-stat-card__meta">locais configurados</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="cop-stat-card">
                <div class="cop-stat-card__icon bg-yellow">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="cop-stat-card__body">
                    <div class="cop-stat-card__label">Pedidos pendentes</div>
                    <div class="cop-stat-card__value"><?= Html::encode($pendingRequestsCount ?? 0) ?></div>
                    <div class="cop-stat-card__meta">a aguardar decisão</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="cop-stat-card">
                <div class="cop-stat-card__icon bg-red">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="cop-stat-card__body">
                    <div class="cop-stat-card__label">Incidentes</div>
                    <div class="cop-stat-card__value"><?= Html::encode($auditLogsCount ?? 0) ?></div>
                    <div class="cop-stat-card__meta">registados</div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN AREA -->
    <div class="row mb-4">
        <!-- LEFT -->
        <div class="col-xl-8 mb-4">
            <div class="cop-panel">
                <div class="cop-panel__header">
                    <div>
                        <h3 class="cop-panel__title">
                            <i class="fas fa-map-marked-alt mr-2"></i>Gestão do mapa
                        </h3>
                        <div class="cop-panel__subtitle">
                            Criar, editar e remover localizações e alojamentos usados no COP.
                        </div>
                    </div>

                    <button type="button"
                            id="copMapModeToggle"
                            class="cop-map-mode-toggle is-editing"
                            data-mode="edit"
                            title="Alternar modo do mapa">
    <span class="cop-map-mode-toggle__icon">
        <i class="fas fa-edit"></i>
    </span>
                        <span class="cop-map-mode-toggle__label">Modo edição</span>
                    </button>
                </div>

                <?php if (Yii::$app->user->can('map.manage')): ?>
                    <div class="cop-map-switch-wrapper">
                        <div id="map-edit" class="cop-map cop-map-instance"></div>
                        <div id="map-view" class="cop-map cop-map-instance d-none"></div>
                    </div>
                <?php else: ?>
                    <div class="p-4 text-muted">Sem permissões para gerir o mapa.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="col-xl-4 mb-4">
            <div class="cop-panel mb-4">
                <div class="cop-panel__header">
                    <h3 class="cop-panel__title">
                        <i class="fas fa-bolt mr-2"></i>Acesso rápido
                    </h3>
                </div>
                <div class="cop-panel__body">
                    <div class="cop-quick-links">
                        <?php if (Yii::$app->user->can('incident.manage')): ?>
                            <?= Html::a(
                                '<i class="fas fa-exclamation-triangle"></i><span>Incidentes</span>',
                                ['/incident/index'],
                                ['class' => 'cop-quick-link cop-quick-link--red']
                            ) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('request.manage')): ?>
                            <?= Html::a(
                                '<i class="fas fa-hands-helping"></i><span>Pedidos</span>',
                                ['/request/index'],
                                ['class' => 'cop-quick-link cop-quick-link--yellow']
                            ) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('map.manage')): ?>
                            <?= Html::a(
                                '<i class="fas fa-map-marker-alt"></i><span>Localizações</span>',
                                ['/location/index'],
                                ['class' => 'cop-quick-link cop-quick-link--blue']
                            ) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('lodging.manage')): ?>
                            <?= Html::a(
                                '<i class="fas fa-bed"></i><span>Alojamentos</span>',
                                ['/lodging-site/index'],
                                ['class' => 'cop-quick-link cop-quick-link--green']
                            ) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('decisionLog.manage')): ?>
                            <?= Html::a(
                                '<i class="fas fa-clipboard-list"></i><span>Decision Log</span>',
                                ['/decision-log/index'],
                                ['class' => 'cop-quick-link cop-quick-link--orange']
                            ) ?>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->can('audit.view')): ?>
                            <?= Html::a(
                                '<i class="fas fa-history"></i><span>Audit Log</span>',
                                ['/audit-log/index'],
                                ['class' => 'cop-quick-link cop-quick-link--gray']
                            ) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registo no mapa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="loc-id">

                <div class="mb-3">
                    <label for="entity-kind" class="form-label">Tipo de registo</label>
                    <select id="entity-kind" class="form-select">
                        <option value="location">Localização</option>
                        <option value="lodging_site">Alojamento</option>
                    </select>
                </div>

                <div id="location-fields">
                    <div class="mb-3">
                        <label for="loc-name" class="form-label">Nome</label>
                        <input type="text" id="loc-name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="loc-type" class="form-label">Tipo</label>
                        <?= Html::dropDownList(
                            'loc-type',
                            null,
                            $locationTypes,
                            ['id' => 'loc-type', 'class' => 'form-select']
                        ) ?>
                    </div>

                    <div class="mb-3">
                        <label for="loc-status" class="form-label">Estado</label>
                        <select id="loc-status" class="form-select">
                            <option value="1">GREEN</option>
                            <option value="2">YELLOW</option>
                            <option value="3">RED</option>
                        </select>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="loc-is-critical" value="1">
                        <label class="form-check-label" for="loc-is-critical">Local Crítico?</label>
                    </div>

                    <div class="mb-3">
                        <label for="loc-notes" class="form-label">Notas</label>
                        <textarea id="loc-notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div id="lodging-fields" style="display: none;">
                    <div class="mb-3">
                        <label for="lodging-name" class="form-label">Nome do alojamento</label>
                        <input type="text" id="lodging-name" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="lodging-capacity-total" class="form-label">Capacidade total</label>
                        <input type="number" id="lodging-capacity-total" class="form-control" min="0" step="1">
                    </div>

                    <div class="mb-3">
                        <label for="lodging-capacity-available" class="form-label">Capacidade disponível</label>
                        <input type="number" id="lodging-capacity-available" class="form-control" min="0" step="1">
                    </div>

                    <div class="mb-3">
                        <label for="lodging-notes" class="form-label">Notas</label>
                        <textarea id="lodging-notes" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="cancelLocationBtn" class="btn btn-light" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button type="button" id="saveLocationBtn" class="btn btn-primary">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    const editMapEl = document.getElementById('map-edit');
    const viewMapEl = document.getElementById('map-view');
    const modeToggle = document.getElementById('copMapModeToggle');

    if (!editMapEl || !viewMapEl) {
        return;
    }

    if (typeof initCopMap !== 'function') {
        console.error('initCopMap não está disponível.');
        return;
    }

    if (typeof initCopMapReadOnly !== 'function') {
        console.error('initCopMapReadOnly não está disponível.');
        return;
    }

    const copMapEdit = initCopMap({
        elId: 'map-edit',
        mode: 'image',
        imageUrl: '{$imageUrl}',
        imageWidth: 1066,
        imageHeight: 701,
        minZoom: -2,
        maxZoom: 4,
        csrfToken: '{$csrf}',

        locationsIndexUrl: '{$idx}',
        locationsCreateUrl: '{$crt}',
        locationsUpdateUrl: '{$upd}',
        locationsDeleteUrl: '{$del}',

        lodgingCreateUrl: '{$lcrt}',
        lodgingUpdateUrl: '{$lupd}',
        lodgingDeleteUrl: '{$ldel}',
    });

    const copMapView = initCopMapReadOnly({
        elId: 'map-view',
        mode: 'image',
        imageUrl: '{$imageUrl}',
        imageWidth: 1066,
        imageHeight: 701,
        minZoom: -2,
        maxZoom: 4,
        scrollWheelZoom: true,
        locationsIndexUrl: '{$idx}',
        iconsBaseUrl: '{$mapIconsBaseUrl}',
    });

    window.copMapEdit = copMapEdit;
    window.copMapView = copMapView;

    function buildMapModeSwitch() {
        if (!modeToggle) return;

        modeToggle.className = 'cop-map-mode-js-switch';
        modeToggle.innerHTML = `
            <span class="cop-map-mode-js-option cop-map-mode-js-edit">
                <i class="fas fa-edit"></i>
                <span>Edição</span>
            </span>

            <span class="cop-map-mode-js-option cop-map-mode-js-view">
                <i class="fas fa-eye"></i>
                <span>Visualização</span>
            </span>

            <span class="cop-map-mode-js-slider"></span>
        `;
    }

    function syncMapModeSwitch(mode) {
        if (!modeToggle) return;

        const isEditMode = mode === 'edit';

        modeToggle.dataset.mode = mode;
        modeToggle.classList.toggle('is-editing', isEditMode);
        modeToggle.classList.toggle('is-viewing', !isEditMode);

        modeToggle.setAttribute(
            'title',
            isEditMode ? 'Mudar para modo visualização' : 'Mudar para modo edição'
        );
    }

    function refreshMapSize() {
        setTimeout(function () {
            if (copMapEdit && copMapEdit.map) {
                copMapEdit.map.invalidateSize(true);
            }

            if (copMapView && copMapView.map) {
                copMapView.map.invalidateSize(true);
            }
        }, 100);

        setTimeout(function () {
            if (copMapEdit && copMapEdit.map) {
                copMapEdit.map.invalidateSize(true);
            }

            if (copMapView && copMapView.map) {
                copMapView.map.invalidateSize(true);
            }

            if (!viewMapEl.classList.contains('d-none') && copMapView && typeof copMapView.fitContain === 'function') {
                copMapView.fitContain();
            }
        }, 350);
    }

    function setMapMode(mode) {
        const isEditMode = mode === 'edit';

        if (isEditMode) {
            editMapEl.classList.remove('d-none');
            viewMapEl.classList.add('d-none');

            if (copMapEdit && typeof copMapEdit.reload === 'function') {
                copMapEdit.reload();
            }
        } else {
            editMapEl.classList.add('d-none');
            viewMapEl.classList.remove('d-none');

            if (copMapView && typeof copMapView.reload === 'function') {
                copMapView.reload();
            }
        }

        syncMapModeSwitch(mode);
        refreshMapSize();
    }

    buildMapModeSwitch();

    if (modeToggle) {
        modeToggle.addEventListener('click', function () {
            const currentMode = modeToggle.dataset.mode || 'edit';
            const nextMode = currentMode === 'edit' ? 'view' : 'edit';

            setMapMode(nextMode);
        });
    }

    setMapMode('edit');
    refreshMapSize();
});
JS, View::POS_END);
?>