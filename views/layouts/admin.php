<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => "ResultPortal Admin",
        'brandUrl' => "ResultPortal Admin",
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);

         echo Nav::widget([
             'options' => ['class' => 'navbar-nav'],
             'items' => [
                 ['label' => 'Home', 'url' => ['/admin/index']],
                 Yii::$app->user->isGuest ? '' : (['label' => 'Dashboard', 'url' => ['/admin/dashboard']]),
                 Yii::$app->user->isGuest ? (
                     ['label' => 'Login', 'url' => ['/admin/login']]
                 ) : (
                     '<li>'
                     . Html::beginForm(['/admin/logout'], 'post', ['class' => 'form-inline'])
                     . Html::submitButton(
                         'Logout (' . Yii::$app->user->identity->email . ')',
                         ['class' => 'btn btn-link logout']
                     )
                     . Html::endForm()
                     . '</li>'
                 )
             ],
         ]);

    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Result Portal <?= date('Y') ?></p>
        <p class="float-right"><a href="https://mrfola.hashnode.dev/">Powered By Mr Fola</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>