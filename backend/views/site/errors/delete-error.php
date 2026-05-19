<?php

use yii\helpers\Html;

/** @var string|null $icon */
/** @var string|null $eyebrow */
/** @var string|null $title */
/** @var string|null $description */
/** @var array|null $details */
/** @var array|null $actions */

$this->title = ' ';

$title = $title ?? 'Não é possível eliminar este registo';
$description = $description ?? 'Este registo não pode ser eliminado porque ainda existem dados associados a ele. Para proteger a integridade da informação, remova ou altere primeiro os registos dependentes.';
$details = $details ?? [
    'O registo que tentou eliminar ainda está a ser utilizado noutros dados do sistema.',
    'A eliminação direta poderia deixar informação incompleta ou inconsistente.',
    'Verifique os registos associados antes de tentar eliminar novamente.',
];

?>

<div class="error-page-wrapper">

    <div class="error-card">

        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div

        <div class="error-content">

            <h1 class="error-title">
                <?= Html::encode($title) ?>
            </h1>

            <p class="error-description">
                <?= Html::encode($description) ?>
            </p>

            <?php if (!empty($details)): ?>
                <div class="error-details">
                    <?php foreach ($details as $detail): ?>
                        <div class="error-detail-item">
                            <i class="fas fa-info-circle"></i>
                            <span><?= Html::encode($detail) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="error-actions">
                <?= Html::button('<i class="fas fa-arrow-left"></i> Voltar', [
                    'class' => 'btn btn-default',
                    'onclick' => 'history.back();',
                ]) ?>
            </div>

        </div>

    </div>

</div>