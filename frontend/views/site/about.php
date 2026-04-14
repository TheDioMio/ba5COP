<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Sobre';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ba5-about">

    <section class="hero-card about-hero-card">
        <div class="hero-copy">
            <span class="hero-eyebrow">BASE AÉREA N.º 5</span>

            <h1>Sobre o COP</h1>

            <p class="hero-text">
                Esta plataforma foi desenvolvida no âmbito de um projeto académico/estágio, com o objetivo de
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
    </section>

    <section class="content-grid about-bottom-grid">
        <article class="info-card panel-card panel-card-wide">
            <div class="panel-head">
                <div>
                    <span class="mini-label">Tecnologias</span>
                    <h3>Base tecnológica utilizada</h3>
                </div>
            </div>

            <div class="timeline-list">
                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-server"></i></div>
                    <div class="timeline-body">
                        <strong>Backend, frontend, e estrutura</strong>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. PHP, framework web, organização MVC e base de dados relacional.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-map"></i></div>
                    <div class="timeline-body">
                        <strong>Visualização cartográfica</strong>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integração de mapa, elementos visuais e representação de informação espacial.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-time"><i class="fa-solid fa-palette"></i></div>
                    <div class="timeline-body">
                        <strong>Interface e experiência visual</strong>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Construção de uma interface coerente com o contexto visual e funcional do projeto.</p>
                    </div>
                </div>
            </div>
        </article>

        <article class="info-card panel-card ba5-identity-card about-badge-card text-center">
            <div class="ba5-identity-content">
                <?= Html::img('@web/img/BA5_Brasao.png', [
                    'alt' => 'Base Aérea Nº5',
                    'class' => 'ba5-badge-large'
                ]) ?>
            </div>
        </article>
    </section>

</div>