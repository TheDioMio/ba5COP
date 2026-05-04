<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Sobre';
?>

<div class="ba5-about">

    <section class="hero-card about-hero-card">
        <div class="hero-copy">
            <span class="hero-eyebrow">BASE AÉREA N.º 5</span>

            <h1>Sobre o COP</h1>

            <p class="hero-text">
                Esta plataforma foi desenvolvida no âmbito de um estágio académico, com o objetivo de
                criar uma solução web de apoio à organização, consulta e apresentação de
                informação relevante no contexto da Base Aérea N.º 5.
            </p>
        </div>

        <div class="hero-image">
            <?= Html::img('@web/img/badgeWithBackground.png', [
                'class' => 'f16-hero about-hero-image',
                'alt' => 'Imagem do projeto'
            ]) ?>
        </div>
    </section>

    <section class="content-grid about-media-grid">
        <article class="info-card panel-card ba5-identity-card about-badge-card text-center">
            <div class="ba5-identity-content">
                <?= Html::img('@web/img/ecsi.png', [
                    'alt' => 'ECSI',
                    'class' => 'ba5-badge-large'
                ]) ?>

                <span class="mini-label mt-3">Desenvolvido pela Esquadra de Comunicações e Sistemas de Informação da BA5</span>

                <p class="ba5-motto">
                    "Tomando sempre novas qualidades"
                </p>
            </div>
        </article>
        <article class="info-card panel-card ba5-identity-card about-badge-card text-center">
            <div class="ba5-identity-content">
                <?= Html::img('@web/img/BA5_Brasao.png', [
                    'alt' => 'Base Aérea Nº5',
                    'class' => 'ba5-badge-large'
                ]) ?>
                <span class="mini-label mt-3">   </span>

                <p class="ba5-motto">

                </p>
            </div>
        </article>
    </section>
</div>