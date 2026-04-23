<?php

use yii\helpers\Html;

/** @var string $icon */
/** @var string $eyebrow */
/** @var string $title */
/** @var string $description */
/** @var array $details */
/** @var array $actions */

$this->title = ' '
?>

<div class="error-page-wrapper">

    <div class="error-card">

        <div class="error-icon">
            <i class="fas fa-ban"></i>
        </div>

        <div class="error-content">
            <h1 class="error-title">
                Ação indisponível
            </h1>

            <p class="error-description">
                A funcionalidade a que tentou aceder não está disponível.
            </p>

            <div class="error-actions">
                <?= Html::a(
                    '<i class="fas fa-arrow-left"></i> Página Principal',
                    ['site/index'],
                    ['class' => 'error-btn error-btn-primary']
                ) ?>
            </div>
        </div>

    </div>

</div>