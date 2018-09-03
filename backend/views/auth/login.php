<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Вход в админ панель');
?>
<?php $form = ActiveForm::begin(['id' => 'login-form',
        'fieldConfig' => [
            'template' => '{input}{error}'
        ],
        'options' => [
            'class' => 'login-form',
        ]
]); ?>

<div class="col s12 z-depth-4 card-panel">
    <form class="login-form">
        <div class="row">
            <div class="input-field col s12 center">
                <img src="images/logo.png" alt="" class="circle responsive-img valign">
                <p class="center login-form-text">Admin SP 2</p>
            </div>
        </div>
        <div class="row margin">
            <div class="input-field col s12">
                <?= $form->field($model, 'login', [
                    'inputTemplate' => '{input}',
                ])
                    ->textInput([
                        'autofocus' => true,
                        'placeholder' => $model->getAttributeLabel('username'),
                    ])->label(false);
                ?>
            </div>
        </div>
        <div class="row margin">
            <div class="input-field col s12">
                <?= $form->field($model, 'password', [
                    'inputTemplate' => '{input}'
                ])->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label('Password') ?>
            </div>
        </div>
        <div class="row">

        </div>
        <div class="row">
            <div class="input-field col s12">
                <button type="submit" class="btn waves-effect waves-light col s12"><?= Yii::t('app', 'Войти') ?> <i
                            class="icon-circle-right2 position-right"></i></button>
            </div>
        </div>

    </form>
</div>
<?php ActiveForm::end(); ?>
