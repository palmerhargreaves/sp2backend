<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 11:38
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>

<div class="card-panel">
    <h4 class="header2">Поля блока</h4>
    <div class="row">
        <?php $form = ActiveForm::begin([ 'id' => 'form-new-field-add',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['activity/add-block-field']),
            'fieldConfig' => [
            'template' => '{input}{error}'
        ], 'options' => [ 'class' => 'col s12', ] ]); ?>
            <div class="row">
                <div class="input-field col s6">
                    <?php echo $form->field($model, 'header')->textInput([ 'class' => '', 'placeholder' => 'Название', 'disabled' => false ]); ?>
                </div>

                <div class="input-field col s6">
                    <?php echo $form->field($model, 'def_value')->textInput([ 'class' => '', 'placeholder' => 'Значение', 'disabled' => false ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s6">
                    <select id="target_dealers_group">
                        <option value="" disabled selected>Выберите группу дилеров</option>
                        <option value="all">PKW</option>
                        <option value="pkw">Для всех дилеров</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Сохранить
                            <i class="mdi-content-send right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <input type="hidden" name="id" value="<?php echo $section->activity_id; ?>" />
            <input type="hidden" name="section_id" value="<?php echo $section->id; ?>" />
            <input type="hidden" name="section_template_id" value="<?php echo $section->section_template_id; ?>" />
        <?php $form->end(); ?>
    </div>
</div>

