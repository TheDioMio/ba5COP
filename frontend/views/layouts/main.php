<?php

/** @var \yii\web\View $this */
/** @var string $content */

use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
$currentRoute = Yii::$app->controller->route;
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
´´

        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

    </head>
    <body class="frontend-shell d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>

        <?php
        NavBar::begin([
            'brandLabel' => Html::img('@web/img/BA5_Brasao.png', [
                    'alt' => 'BA5_brasao',
                    'class' => 'ba5-logo'
                ]) . '<span class="brand-text">COP</span>',
            'brandUrl' => Yii::$app->homeUrl,
            'brandOptions' => [
                'class' => 'navbar-brand d-flex align-items-center'
            ],
            'options' => [
                'class' => 'navbar navbar-expand-lg navbar-dark ba5-navbar fixed-top',
            ],
            'innerContainerOptions' => ['class' => 'container-fluid px-3 px-lg-4'],
        ]);

        $menuItems = [
            ['label' => 'Dashboard', 'url' => ['/dashboard/index'], 'active' => $currentRoute === 'site/cop'],
            ['label' => 'COP', 'url' => ['/site/cop'], 'active' => $currentRoute === 'site/cop'],
            ['label' => 'Sobre', 'url' => ['/site/about'], 'active' => $currentRoute === 'site/about'],
            ['label' => 'Contacto', 'url' => ['/site/contact'], 'active' => $currentRoute === 'site/contact'],
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center'],
            'items' => $menuItems,
            'encodeLabels' => false,
        ]);

        echo '<div class="d-flex align-items-center gap-2 ms-lg-3">';

        if (Yii::$app->user->isGuest) {
            echo Html::a('Entrar', ['/site/login'], ['class' => 'btn btn-sm btn-ba5-primary']);
        } else {
            echo Html::a('Abrir COP', ['/site/cop'], ['class' => 'btn btn-sm btn-ba5-secondary']);
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'm-0']);
            echo Html::submitButton(
                'Sair · ' . Html::encode(Yii::$app->user->identity->username),
                ['class' => 'btn btn-sm btn-outline-light logout']
            );
            echo Html::endForm();
        }

        echo '</div>';

        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <?php
        $isHome = $currentRoute === 'site/index';
        $isDashboard = $currentRoute === 'dashboard/index';
        $isFluid = $isHome || $isDashboard;
        ?>
        <div class="<?= $isFluid ? 'container-fluid px-3 px-lg-4' : 'container' ?>">
            <?php if (!$isHome): ?>
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]) ?>
            <?php endif; ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container-fluid px-3 px-lg-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="m-0">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="m-0 footer-note">Common Operational Picture · Base Aérea N.º 5</p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
