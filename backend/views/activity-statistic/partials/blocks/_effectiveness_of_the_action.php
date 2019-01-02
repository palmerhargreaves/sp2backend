<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 16:03
 */

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<div class="card-panel">
    <h4 class="header2">Добавление нового поля</h4>
    <div class="row">
        <?php $form = ActiveForm::begin([ 'id' => 'form-new-field-add',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['activity-statistic/add-block-field']),
            'fieldConfig' => [
                'template' => '{input}{error}'
            ], 'options' => [ 'class' => 'col s12', ] ]); ?>
        <div class="row">
            <div class="input-field col s12">
                <?php echo $form->field($model, 'header')->textInput([ 'class' => '', 'placeholder' => 'Название', 'disabled' => false ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="show_in_export" name="ActivityEffectivenessOfTheActionBlock[show_in_export]" value="1">
                <label for="show_in_export" style="text-decoration: none;">Выводить в выгрузке</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="show_in_statistic" name="ActivityEffectivenessOfTheActionBlock[show_in_statistic]" value="1">
                <label for="show_in_statistic" style="text-decoration: none;">Выводить в статистике</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="is_revenue_field" name="ActivityEffectivenessOfTheActionBlock[is_revenue_field]" value="1">
                <label for="is_revenue_field" style="text-decoration: none;">Использовать как параметр (Выручка) для формулы</label>
            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="input-field col s12">
                    <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Добавить
                        <i class="mdi-content-send right"></i>
                    </button>
                </div>
            </div>
        </div>

        <input type="hidden" name="activity_id" value="<?php echo $section->activity_id; ?>" />
        <input type="hidden" name="section_id" value="<?php echo $section->id; ?>" />
        <input type="hidden" name="section_template_id" value="<?php echo $section->section_template_id; ?>" />

        <?php $form->end(); ?>
    </div>
</div>
