<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 20.11.2017
 * Time: 12:00
 */
use common\models\activity\steps\ActivityExtendedStatisticSteps;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;

?>

<div class="section">
    <div class="divider"></div>

    <div id="card-stats" class="section">

        <div class="row">
            <div class="col s12 m6 l12">
                <h4 class="header">Добавление шага</h4>
                <div class="card-panel">
                    <h4 class="header2">Новый шаг</h4>
                    <div class="row">
                        <?php $form = ActiveForm::begin(['id' => 'form', 'fieldConfig' => [
                            'template' => '{input}{error}'
                        ], 'options' => ['class' => 'col s12']]); ?>
                        <div class="row">
                            <div class="input-field col s12">
                                <?php echo $form->field($model, 'header')->textInput(['class' => '', 'placeholder' => 'Название', 'disabled' => false]); ?>
                                <label for="header"
                                       class="active"><?php echo $model->getAttributeLabel('header'); ?> </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <?php echo $form->field($model, 'description')->textarea(['class' => 'materialize-textarea', 'rows' => 5, 'placeholder' => 'Краткое описание', 'disabled' => false]); ?>
                                <label for="description"
                                       class="active"><?php echo $model->getAttributeLabel('description'); ?> </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <?php echo $form->field($model, 'step_type')->dropDownList(ArrayHelper::map(ActivityExtendedStatisticSteps::getStepsTypes(), 'id', 'name')); ?>
                                <label for="step_type"
                                       class="active"><?php echo $model->getAttributeLabel('step_type'); ?> </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <?php echo $form->field($model, 'action_after')->textInput(['class' => '', 'placeholder' => 'Рассылка писем через n. дней', 'disabled' => false]); ?>
                                <label for="action_after"
                                       class="active"><?php echo $model->getAttributeLabel('action_after'); ?> </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="btn cyan waves-effect waves-light right">
                                    <i class="mdi-content-save right"></i>&nbsp;<?= Yii::t('app', 'Сохранить') ?>
                                </button>
                            </div>
                        </div>

                        <?php echo $form->field($model, 'activity_id')->hiddenInput(['value' => $model->isNewRecord ? $activity_id : $model->activity_id])->label(false); ?>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
