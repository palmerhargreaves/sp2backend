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
        </div>

        <div class="row">
            <div class="input-field col s12">
                <select name="ActivitySettingsBlock[graph_type]" class="block-selectbox">
                    <?php foreach (array('none' => 'Без диграммы', 'waterfall' => 'Воронка', 'pie' => 'Круговая', 'linear' => 'Линейная', 'bars' => 'Гистограмма' ) as $type_key => $type_label): ?>
                        <option value="<?php echo $type_key; ?>" <?php echo ($type_key != "none" && $section->graph_type == $type_key) ? "selected" : ""; ?>><?php echo $type_label; ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="help-block help-block-error"></p>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="allow_use_fields_in_others_blocks" name="ActivitySettingsBlock[allow_use_fields_in_others_blocks]" <?php echo $section->allow_use_fields_in_others_blocks == 1 ? "checked" : ""; ?> value="1">
                <label for="allow_use_fields_in_others_blocks" style="text-decoration: none;">Использовать поля в др. блоках</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="checkbox" id="status" name="ActivitySettingsBlock[status]" <?php echo $section->status == 1 ? "checked" : ""; ?> value="1">
                <label for="status" style="text-decoration: none;">Выводить в статистике</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Сохранить
                    <i class="mdi-content-send right"></i>
                </button>
            </div>
        </div>

        <?php echo $form->field($model, 'id')->hiddenInput([ 'value' => $section->id ])->label(false); ?>
        <?php $form->end(); ?>
    </div>
</div>

