<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 09.09.2018
 * Time: 21:35
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                <select name="ActivityOtherIndicatorsBlock[value_type]" class="block-selectbox">
                    <?php foreach (array('dig' => Yii::t('app', 'Число'), 'text' => Yii::t('app', 'Текст')) as $key => $value): ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="show_in_export" name="ActivityOtherIndicatorsBlock[show_in_export]" value="1">
                <label for="show_in_export" style="text-decoration: none;">Выводить в выгрузке</label>
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


