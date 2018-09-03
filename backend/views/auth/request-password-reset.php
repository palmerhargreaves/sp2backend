<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Восстановление пароля');
?>
<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
<div class="panel panel-body login-form">
    <div class="text-center">
        <h3 class="content-group"><?= Html::encode($this->title) ?></h3>
    </div>

    <?= $form->field($model, 'email', [
        'inputTemplate' => '{input}<div class="form-control-feedback"> <i class="icon-mention text-muted"></i></div>'
    ])->textInput(['autofocus' => true, 'placeholder' => $model->getAttributeLabel('email')])->label(false) ?>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block"><?= Yii::t('app', 'Отправить') ?> <i class="icon-envelop5 position-right"></i></button>
    </div>
</div>
<?php ActiveForm::end(); ?>