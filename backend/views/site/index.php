<?php
use common\assets\CopMapAsset;
use yii\helpers\Url;
use yii\web\View;

$asset = CopMapAsset::register($this);
$imageUrl = $asset->baseUrl . '/img/img_mapa.jpg';


// urls
$idx = Url::to(['/location/map-index']);
$crt = Url::to(['/location/map-create']);
$upd = Url::to(['/location/map-update', 'id' => '__ID__']);
$del = Url::to(['/location/map-delete', 'id' => '__ID__']);

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
                'id' => $infoBox->id.'-ribbon',
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
                'id' => $smallBox->id.'-ribbon',
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

        <div id="map" style="height: calc(80vh - 220px); min-height: 600px;"></div>

        <div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Localização</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="loc-id">

                        <div class="mb-3">
                            <label for="loc-name" class="form-label">Nome</label>
                            <input type="text" id="loc-name" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="loc-type" class="form-label">Tipo</label>
                            <?= \yii\helpers\Html::dropDownList(
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
    });
});
JS, View::POS_END);
?>