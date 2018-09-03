<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use common\models\user\User;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $roles array */
?>
<div class="panel panel-flat">

    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id' => 'form', 'layout' => 'horizontal']); ?>

        <?= $form->field($model, 'username')->textInput() ?>

        <?= $form->field($model, 'firstname')->textInput() ?>

        <?= $form->field($model, 'surname')->textInput() ?>

        <?= $form->field($model, 'password')->textInput() ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'role')->dropDownList($roles) ?>

        <?= $form->field($model, 'status')->dropDownList($model->getUser()->getStatuses()) ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="text-center">
                    <button type="submit" class="btn btn-danger pull-right">
                        <i class="icon-floppy-disk"></i>&nbsp;<?= Yii::t('app', 'Сохранить') ?></button>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
