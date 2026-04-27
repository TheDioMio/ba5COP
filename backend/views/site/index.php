<?php

use common\assets\CopMapAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$asset = CopMapAsset::register($this);
$imageUrl = $asset->baseUrl . '/img/img_mapa.jpg';

// URLs location
$idx = Url::to(['/location/map-index']);
$crt = Url::to(['/location/map-create']);
$upd = Url::to(['/location/map-update', 'id' => '__ID__']);
$del = Url::to(['/location/map-delete', 'id' => '__ID__']);

// URLs lodging_site
$lcrt = Url::to(['/lodging-site/map-create']);
$lupd = Url::to(['/lodging-site/map-update', 'id' => '__ID__']);
$ldel = Url::to(['/lodging-site/map-delete', 'id' => '__ID__']);

$csrf = Yii::$app->request->csrfToken;

$this->title = 'Starter Page';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <?= \hail812\adminlte\widgets\Alert::widget([
                    'type' => 'success',
                    'body' => '<h3>Congratulations!</h3>',
                ]) ?>
                <?= \hail812\adminlte\widgets\Callout::widget([
                    'type' => 'danger',
                    'head' => 'I am a danger callout!',
                    'body' => 'There is a problem that we need to fix. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.'
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'CPU Traffic',
                    'number' => '10 <small>%</small>',
                    'icon' => 'fas fa-cog',
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Messages',
                    'number' => '1,410',
                    'icon' => 'far fa-envelope',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Bookmarks',
                    'number' => '410',
                    'theme' => 'success',
                    'icon' => 'far fa-flag',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Uploads',
                    'number' => '13,648',
                    'theme' => 'gradient-warning',
                    'icon' => 'far fa-copy',
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Bookmarks',
                    'number' => '41,410',
                    'icon' => 'far fa-bookmark',
                    'progress' => [
                        'width' => '70%',
                        'description' => '70% Increase in 30 Days'
                    ]
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?php $infoBox = \hail812\adminlte\widgets\InfoBox::begin([
                    'text' => 'Likes',
                    'number' => '41,410',
                    'theme' => 'success',
                    'icon' => 'far fa-thumbs-up',
                    'progress' => [
                        'width' => '70%',
                        'description' => '70% Increase in 30 Days'
                    ]
                ]) ?>
                <?= \hail812\adminlte\widgets\Ribbon::widget([
                    'id' => $infoBox->id . '-ribbon',
                    'text' => 'Ribbon',
                ]) ?>
                <?php \hail812\adminlte\widgets\InfoBox::end() ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Events',
                    'number' => '41,410',
                    'theme' => 'gradient-warning',
                    'icon' => 'far fa-calendar-alt',
                    'progress' => [
                        'width' => '70%',
                        'description' => '70% Increase in 30 Days'
                    ],
                    'loadingStyle' => true
                ]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\SmallBox::widget([
                    'title' => '150',
                    'text' => 'New Orders',
                    'icon' => 'fas fa-shopping-cart',
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?php $smallBox = \hail812\adminlte\widgets\SmallBox::begin([
                    'title' => '150',
                    'text' => 'New Orders',
                    'icon' => 'fas fa-shopping-cart',
                    'theme' => 'success'
                ]) ?>
                <?= \hail812\adminlte\widgets\Ribbon::widget([
                    'id' => $smallBox->id . '-ribbon',
                    'text' => 'Ribbon',
                    'theme' => 'warning',
                    'size' => 'lg',
                    'textSize' => 'lg'
                ]) ?>
                <?php \hail812\adminlte\widgets\SmallBox::end() ?>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\SmallBox::widget([
                    'title' => '44',
                    'text' => 'User Registrations',
                    'icon' => 'fas fa-user-plus',
                    'theme' => 'gradient-success',
                    'loadingStyle' => true
                ]) ?>
            </div>

            <?php
            if(Yii::$app->user->can('map.manage')) {
                echo '<div id="map" style="height: calc(80vh - 220px); min-height: 600px;"></div>';
            }
            ?>


            <div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Registo no mapa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- ID genérico da entidade em edição -->
                            <input type="hidden" id="loc-id">

                            <!-- Tipo de entidade a criar/editar -->
                            <div class="mb-3">
                                <label for="entity-kind" class="form-label">Tipo de registo</label>
                                <select id="entity-kind" class="form-select">
                                    <option value="location">Localização</option>
                                    <option value="lodging_site">Alojamento</option>
                                </select>
                            </div>

                            <!-- ========================= -->
                            <!-- BLOCO: LOCATION -->
                            <!-- ========================= -->
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

                            <!-- ========================= -->
                            <!-- BLOCO: LODGING SITE -->
                            <!-- ========================= -->
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
                            <button type="button" id="cancelLocationBtn" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="saveLocationBtn" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    const entityKind = document.getElementById('entity-kind');
    const locationFields = document.getElementById('location-fields');
    const lodgingFields = document.getElementById('lodging-fields');

    function toggleEntityFields() {
        const kind = entityKind ? entityKind.value : 'location';

        if (kind === 'lodging_site') {
            locationFields.style.display = 'none';
            lodgingFields.style.display = 'block';
        } else {
            locationFields.style.display = 'block';
            lodgingFields.style.display = 'none';
        }
    }

    if (entityKind) {
        entityKind.addEventListener('change', toggleEntityFields);
        toggleEntityFields();
    }

    initCopMap({
        elId: 'map',
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
});
JS, View::POS_END);
?>