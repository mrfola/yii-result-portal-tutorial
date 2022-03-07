<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <?php
        $session = Yii::$app->session;
        $errors = $session->getFlash('errorMessages');
        $success = $session->getFlash('successMessage');
        if(isset($errors) && (count($errors) > 0))
        {
            foreach($session->getFlash('errorMessages') as $error)
            {
                echo "<div class='alert alert-danger' role='alert'>$error[0]</div>";
            }
        }

        if(isset($success))
        {
            echo "<div class='alert alert-success' role='alert'>$success</div>";
        }
    ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
            'inputOptions' => ['class' => 'col-lg-3 form-control'],
            'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
        ],
    ]); ?>

        <?= $form->field($user, 'email')->input('email') ?>

        <?= $form->field($user, 'password')->passwordInput() ?>

        <?= $form->field($user, 'rememberMe')->checkbox([
            'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>

        <div class="form-group">
            <div class="offset-lg-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="offset-lg-1" style="color:#999;">
        You may login with <strong>admin@gmail.com/password</strong><br>
    </div>
</div>
