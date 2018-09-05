<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 12:38
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="card-panel">
    <h4 class="header2">Параметры блока</h4>
    <div class="row">
        <?php $form = ActiveForm::begin([ 'id' => 'form-block-settings',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to([ 'activity-statistic/edit-settings', 'id' => $section->id ]),
            'fieldConfig' => [
                'template' => '{input}{error}'
            ], 'options' => [ 'class' => 'col s12', ] ]); ?>
        <div class="row">
            <div class="input-field col s12">
                <?php echo $form->field($model, 'description')->textarea([ 'rows' => 6, 'placeholder' => 'Краткое описание блока', 'value' => $section->description ]); ?>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Сохранить
                        <i class="mdi-content-send right"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php echo $form->field($model, 'id')->hiddenInput([ 'value' => $section->id ])->label(false); ?>
        <?php $form->end(); ?>
    </div>
</div>

