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
                <div class="input-field col s6">
                    <?php echo $form->field($model, 'header')->textInput([ 'class' => '', 'placeholder' => 'Название', 'disabled' => false ]); ?>
                </div>

                <div class="input-field col s6">
                    <?php echo $form->field($model, 'def_value')->textInput([ 'class' => '', 'type' => 'number', 'placeholder' => 'Значение', 'disabled' => false ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <input id="js-search-by-dealer-number" type="text" data-url="<?php echo Url::to(['activity-statistic/search-dealer-by-number']); ?>" />
                    <label for="js-search-by-dealer-number" class="">Введите номер дилера</label>
                </div>

                <div class="col s12">
                    <div id="dealer-search-result"></div>
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

            <input type="hidden" name="activity_id" value="<?php echo $section->activity_id; ?>" />
            <input type="hidden" name="section_id" value="<?php echo $section->id; ?>" />
            <input type="hidden" name="section_template_id" value="<?php echo $section->section_template_id; ?>" />

        <?php $form->end(); ?>
    </div>
</div>

