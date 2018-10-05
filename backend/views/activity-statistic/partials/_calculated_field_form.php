<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 09.09.2018
 * Time: 16:19
 */

use common\models\activity\ActivityExtendedStatisticSections;
use common\models\activity\fields\ActivityExtendedStatisticFields;
use yii\widgets\ActiveForm;

?>

<a id="calculated_field_ancor"></a>
<h4 class="header2"><?php echo isset($field) ? "Параметры вычисляемого поля" : "Добавление вычисляемого поля"; ?></h4>

<?php $form = ActiveForm::begin([ 'id' => 'form-new-calculated-field',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'fieldConfig' => [
        'template' => '{input}{error}'
    ], 'options' => [ 'class' => 'col s12', ] ]); ?>

<div class="row">
    <div class="input-field col s12">
        <?php echo $form->field($calculated_model, 'header')
            ->textInput([ 'id' => 'calculated_field_header', 'class' => '', 'placeholder' => 'Название', 'disabled' => false, 'value' => isset($field) ? $field->header : '' ]); ?>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <select id="field-calc-type" name="field-calc-type">
            <?php foreach (ActivityExtendedStatisticFields::getTypes() as $key => $type): ?>
                <option value="<?php echo $key; ?>" <?php echo isset($field) && $field->isCalcField() && $field->getCalcType() == $key ? "selected" : ""; ?>><?php echo $type; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <input type="checkbox" id="show_in_export_calc" name="ActivityCalculatedFieldBlock[show_in_export]"
               <?php echo isset($field) && $field->show_in_export ? "checked" : ""; ?> value="1">
        <label for="show_in_export_calc" style="text-decoration: none;">Выводить в выгрузке</label>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <input type="checkbox" id="show_in_statistic_calc"
                name="ActivityCalculatedFieldBlock[show_in_statistic]"
                <?php echo isset($field) && $field->show_in_statistic ? "checked" : ""; ?> value="1">
        <label for="show_in_statistic_calc" style="text-decoration: none;">Выводить в статистике</label>
    </div>
</div>

<input type="hidden" name="activity_id" value="<?php echo $section->activity_id; ?>"/>
<input type="hidden" name="section_id" value="<?php echo $section->id; ?>"/>

<?php $form->end(); ?>

<div class="card-panel" style="margin-top: 25px; float: left; width: 100%;">
    <div class="row">
        <h4 class="header2">Список полей</h4>

        <ul class="collapsible collapsible-accordion" data-collapsible="expandable">
            <?php foreach (ActivityExtendedStatisticSections::find()->where(['activity_id' => $section->activity_id])->all() as $section_item): ?>
            <li>
                <div class="collapsible-header <?php echo $section->id == $section_item->id ? 'active' : '' ; ?>">
                    <i class="mdi-action-polymer"></i><?php echo sprintf('%s (полей - %d)', $section_item->header, $section_item->getFieldsCount()); ?></div>
                <div class="collapsible-body">
                    <ul id="task-card" class="collection with-header">
                        <?php
                        $calc_fields = [];
                        if (isset($field)) {
                            $calc_fields = $field->getCalcFieldsIds();
                        }
                        ?>
                        <?php foreach (ActivityExtendedStatisticFields::find()->where([ 'parent_id' => $section_item->id ])->andWhere([ '!=', 'value_type', ActivityExtendedStatisticFields::CALC ])->all() as $field_item): ?>
                            <li class="collection-item dismissable"
                                style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                                <input class="ch-calc-field" type="checkbox"
                                       data-id="<?php echo $field_item->id; ?>"
                                       data-name="<?php echo $field_item->header; ?>"
                                       data-section-name="<?php echo $field_item->section->header; ?>"
                                       id="field-id-<?php echo $field_item->id; ?>" <?php echo in_array($field_item->id, $calc_fields) ? "checked" : ""; ?>>
                                <label for="field-id-<?php echo $field_item->id; ?>"
                                       style="text-decoration: none;"><?php echo $field_item->header; ?>
                                </label>

                                <?php if ($field_item->isCalcField()): ?>
                                    <span class="task-cat teal"><?php echo $field_item->getCalcFieldsList(); ?> </span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>


    </div>

    <?php $calc_fields = ActivityExtendedStatisticFields::find()->where([ 'parent_id' => $section->id ])->andWhere([ '=', 'value_type', ActivityExtendedStatisticFields::CALC ])->all(); ?>
    <?php if ($calc_fields): ?>
        <div class="row">
            <h4 class="header2">Список вычисляемых полей</h4>

            <ul id="task-card" class="collection with-header">
                <?php foreach ($calc_fields as $field_item):  ?>
                    <li class="collection-item dismissable"
                        style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                        <input class="ch-calc-field" type="checkbox"
                               data-id="<?php echo $field_item->id; ?>"
                               data-name="<?php echo $field_item->header; ?>"
                               data-section-name="<?php echo $field_item->section->header; ?>"
                               id="field-id-<?php echo $field_item->id; ?>" <?php //echo in_array($field_item->id, $calc_fields) ? "checked" : ""; ?>>
                        <label for="field-id-<?php echo $field_item->id; ?>"
                               style="text-decoration: none;"><?php echo $field_item->header; ?>
                        </label>

                        <?php if ($field_item->isCalcField()): ?>
                            <span class="task-cat teal"><?php echo $field_item->getCalcFieldsList(); ?> </span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col s12 m12 l12">
            <span class="blue-text text-darken-2">Список добавляемых полей</span>

            <p>Переместите поля для корректного вычисления создаваемого выражения</p>
            <div class="dd" id="checked-calc-field">
                <?php if (isset($field)): ?>
                    <?php foreach ($field->getCalcFields() as $calc_field): ?>
                        <li class='dd-item' data-id='<?php echo $calc_field->calc_field; ?>'>
                            <div class='dd-handle'>
                                <?php echo sprintf('[%s] %s', $calc_field->getCalcFieldSectionName(), $calc_field->getCalcFieldName()); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="input-field col s12">
        <button id="js-save-calc-value"
                data-url="<?php echo \yii\helpers\Url::to([ 'activity-statistic/add-new-calculated-field' ]); ?>"
                style="display: <?php echo !isset($field) ? 'none' : ''; ?>"
                class="btn cyan waves-effect waves-light right tooltipped"
                type="submit"
                data-position="top"
                data-delay="50"
                data-tooltip="<?php echo Yii::t('app', isset($field) ? 'Изменить параметры поля' : 'Добавить вычисляемое поле'); ?>"
                <?php if (isset($field)): ?> data-id="<?php echo $field->id; ?>" <?php endif; ?>
                data-section-id="<?php echo $section->id; ?>"><?php echo isset($field) ? 'Сохранить' : 'Добавить'; ?>
            <i class="mdi-content-send right"></i>
        </button>
    </div>
</div>
