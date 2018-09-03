<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 13:14
 */

use common\models\activity\fields\ActivityExtendedStatisticFields;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

    <div class="section">
        <div class="divider"></div>

        <div id="work-collections" class="section">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h4 class="header">Список разделов</h4>
                    <p>Список разделов активности</p>

                    <div class="card">
                        <div class="col s12 m8 l12">
                            <table id="mainTable" data-activity-id="<?php echo $activity->id; ?>"
                                   data-url="<?php echo Url::to([ 'activity-fields/sort-sections' ]); ?>"
                                   class="table-responsive sortable-sections-table sortable-sections-list">
                                <thead>
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Название</th>
                                    <th>Базовая категория</th>
                                    <th>Статус</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tbody class="">

                                <?php $sections_total = 0; ?>
                                <?php foreach (\common\models\activity\ActivityExtendedStatisticSections::find()->where([ 'activity_id' => $activity->id ])->orderBy([ 'position' => SORT_ASC ])->all() as $section): ?>
                                    <tr class="sortable-list-items"
                                        data-id="<?php echo $section->id; ?>"
                                        data-url="<?php echo Url::to([ "activity-fields/save-section-data" ]); ?>">
                                        <td class="sortable-item ">
                                            <i class="mdi-hardware-gamepad handle"></td>
                                        <td style="width: 35%;">
                                            <input class="field-item" data-field="header" type="text"
                                                   value="<?php echo $section->header; ?>"/>
                                        </td>
                                        <td>

                                        </td>
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="field-item checkbox"
                                                   data-field="required"
                                                   id="ch-section-status-<?php echo $section->id; ?>" <?php echo $section->status ? "checked" : ""; ?> >
                                            <label for="ch-section-status-<?php echo $section->id; ?>"
                                                   style="text-decoration: none;" class="tooltipped"
                                                   data-position="top" data-delay="50"
                                                   data-tooltip="<?php echo Yii::t('app', 'Статус раздела'); ?>">&nbsp;</a></label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                <a class="js-btn-save-section btn-save-section<?php echo $section->id; ?> btn-floating btn-flat waves-effect waves-light blue accent-2 white-text left tooltipped"
                                                   data-position="top" data-delay="50"
                                                   data-tooltip="<?php echo Yii::t('app', 'Сохранить изменения'); ?>"
                                                   style="margin-right: 7px; display:none;"
                                                   href="#!"><i class="mdi-content-save"></i></a>

                                                <a class="btn-floating btn-flat waves-effect waves-light red accent-2 white-text left tooltipped"
                                                   data-position="top" data-delay="50"
                                                   data-tooltip="<?php echo Yii::t('app', 'Удаление раздела'); ?>"
                                                   href="<?php echo Url::to([ 'activity-fields/delete-section', 'id' => $section->id ]); ?>"><i
                                                            class="mdi-action-highlight-remove"></i></a>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php $sections_total++; endforeach; ?>
                                </tbody>
                            </table>
                            <div class="divider"></div>
                            <table>
                                <tr>
                                    <td colspan="5">
                                        <ul id="projects-collection" class="collection">
                                            <li class="collection-item">
                                                <div class="row">
                                                    <div class="col s6">
                                                        <p class="collections-title"></p>
                                                        <p class="collections-content"></p>
                                                    </div>
                                                    <div class="col s3">
                                                        <span class="task-cat cyan"><?php echo sprintf('%s: %d', Yii::t('app', 'Всего разделов'), $sections_total); ?></span>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <div class="row">
                <div class="col s12 m12 l12">
                    <h4 class="header">Список полей</h4>
                    <p>Список активных полей активности</p>

                    <ul class="collapsible popout" data-collapsible="accordion">
                        <?php $ind = 0; ?>
                        <?php foreach ($activity->getFieldsSections() as $section): ?>
                            <li class="<?php echo $ind == 0 ? "active" : ""; ?>">
                                <div class="collapsible-header" data-id="<?php echo $section->id; ?>"><i
                                            class="mdi-action-label-outline"></i><?php echo $section->header; ?></div>
                                <div class="collapsible-body">
                                    <div class="card">
                                        <div class="col s12 m8 l12">
                                            <table id="mainTable"
                                                   data-url="<?php echo Url::to([ 'activity-fields/sort-fields' ]); ?>"
                                                   data-section-id="<?php echo $section->id; ?>"
                                                   class="table-responsive sortable-fields-table sortable-list-<?php echo $section->id; ?>">
                                                <thead>
                                                <tr>
                                                    <th style="width: 50px;"></th>
                                                    <th>Название</th>
                                                    <th>Описание</th>
                                                    <th>Тип</th>
                                                    <th>Шаг</th>
                                                    <th>Обязательное</th>
                                                    <th>Действие</th>
                                                </tr>
                                                </thead>
                                                <tbody class="">
                                                <?php $sections_total_fields = 0; ?>
                                                <?php foreach (ActivityExtendedStatisticFields::find()->where([ 'parent_id' => $section->id ])->orderBy([ 'position' => SORT_ASC ])->all() as $field): ?>
                                                    <tr class="sortable-list-items"
                                                        data-id="<?php echo $field->id; ?>"
                                                        data-url="<?php echo Url::to([ "activity-fields/save-data" ]); ?>">
                                                        <td class="sortable-item ">
                                                            <i class="mdi-hardware-gamepad handle"></td>
                                                        <td style="width: 35%;">
                                                            <input class="field-item" data-field="header" type="text"
                                                                   value="<?php echo $field->header; ?>"/>
                                                        </td>
                                                        <td>
                                                            <input class="field-item" data-field="description"
                                                                   type="text"
                                                                   value="<?php echo $field->description; ?>"/>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <select class="field-item" data-field="value_type">
                                                                <?php foreach (ActivityExtendedStatisticFields::getFieldTypesList() as $type_key => $type_label): ?>
                                                                    <option value="<?php echo $type_key; ?>" <?php echo $type_key == $field->value_type ? "selected" : ""; ?>><?php echo $type_label; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <select class="field-item" data-field="step_id">
                                                                <option value="">Нет</option>
                                                                <?php foreach (\common\models\activity\steps\ActivityExtendedStatisticSteps::find()->where([ 'activity_id' => $activity->id ])->orderBy([ 'position' => SORT_ASC ])->all() as $step): ?>
                                                                    <option value="<?php echo $step->id; ?>" <?php echo $step->id == $field->step_id ? "selected" : ""; ?>><?php echo $step->header; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <input type="checkbox" class="field-item checkbox"
                                                                   data-field="required"
                                                                   id="ch-field-required<?php echo $field->id; ?>" <?php echo $field->required ? "checked" : ""; ?>>
                                                            <label for="ch-field-required<?php echo $field->id; ?>"
                                                                   style="text-decoration: none;" class="tooltipped"
                                                                   data-position="top" data-delay="50"
                                                                   data-tooltip="<?php echo Yii::t('app', 'Обязательное поле для заполнения'); ?>">&nbsp;</a></label>
                                                        </td>
                                                        <td>
                                                            <div class="row">
                                                                <a class="modal-trigger btn-floating btn-flat waves-effect waves-light black accent-2 white-text left tooltipped"
                                                                   data-position="top"
                                                                   data-delay="50"
                                                                   data-tooltip="<?php echo Yii::t('app', 'Настройка вычисляемых полей'); ?>"
                                                                   data-href="<?php echo Url::to([ '/activity-fields/config-calc-fields', 'id' => $field->id ]); ?>"
                                                                   href="#modal-field-calc-config"
                                                                   style="margin-right: 7px; display:<?php echo $field->isCalc() ? "block" : "none"; ?>">
                                                                    <i class="mdi-action-settings"></i>
                                                                </a>

                                                                <a class="js-btn-save-field btn-save-field<?php echo $field->id; ?> btn-floating btn-flat waves-effect waves-light blue accent-2 white-text left tooltipped"
                                                                   data-position="top" data-delay="50"
                                                                   data-tooltip="<?php echo Yii::t('app', 'Сохранить изменения'); ?>"
                                                                   style="margin-right: 7px; display:none;"
                                                                   href="#!"><i class="mdi-content-save"></i></a>

                                                                <a class="btn-floating btn-flat waves-effect waves-light red accent-2 white-text left tooltipped"
                                                                   data-position="top" data-delay="50"
                                                                   data-tooltip="<?php echo Yii::t('app', 'Удаление поля'); ?>"
                                                                   href="<?php echo Url::to([ 'activity-fields/delete-field', 'id' => $field->id ]); ?>"><i
                                                                            class="mdi-action-highlight-remove"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $sections_total_fields++; ?>
                                                <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                            <div class="divider"></div>
                                            <table>
                                                <tr>
                                                    <td colspan="5">
                                                        <ul id="projects-collection" class="collection">
                                                            <li class="collection-item">
                                                                <div class="row">
                                                                    <div class="col s6">
                                                                        <p class="collections-title"></p>
                                                                        <p class="collections-content"></p>
                                                                    </div>
                                                                    <div class="col s3">
                                                                        <span class="task-cat cyan"><?php echo sprintf('%s: %d', Yii::t('app', 'Все полей в разделе'), $sections_total_fields); ?></span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Floating Action Button -->
            <div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
                <a class="btn-floating btn-large">
                    <i class="mdi-action-stars"></i>
                </a>
                <ul>
                    <li>
                        <a href="#modal-field-add"
                           class="btn-floating green modal-trigger tooltipped"
                           data-position="top"
                           data-delay="50"
                           data-tooltip="<?php echo Yii::t('app', 'Добавление нового поля'); ?>">
                            <i class="small mdi-content-add"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Floating Action Button -->
        </div>
    </div>

    <div id="modal-field-calc-config" class="modal bottom-sheet" style="max-height: 60%;">
        <div class="modal-content">
        </div>
    </div>


    <div id="modal-field-add" class="modal">
        <?php $form = ActiveForm::begin([ 'id' => 'form-new-field-add', 'fieldConfig' => [
            'template' => '{input}{error}'
        ], 'options' => [ 'class' => 'col s12' ] ]); ?>

        <div class="modal-content">
            <nav class="red">
                <div class="nav-wrapper">
                    <div class="left col s12 m5 l5">
                        <ul>
                            <li>
                                <a href="#!" class="email-menu">
                                    <i class="modal-action modal-close  mdi-hardware-keyboard-backspace"></i>
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="col s12 m7 l7 hide-on-med-and-down">
                        <ul class="right">
                            <li><a href="#!" class="js-add-new-field email-type">Добавить</a></li>
                        </ul>
                    </div>

                </div>
            </nav>
        </div>
        <div class="model-email-content" style="padding-top: 0px;">
            <div class="row">
                <div class="row">
                    <div class="input-field col s12">
                        <?php echo $form->field($model, 'header')->textInput([ 'class' => '', 'placeholder' => 'Название', 'disabled' => false ]); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <?php echo $form->field($model, 'parent_id')
                            ->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\activity\fields\ActivityExtendedStatisticSections::find()->where([ 'activity_id' => $activity->id ])->orderBy([ 'id' => SORT_DESC ])->all(), 'id', 'header')); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <?php echo $form->field($model, 'value_type')
                            ->dropDownList(\common\models\activity\fields\ActivityExtendedStatisticFields::getFieldTypesList()); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <?php echo $form->field($model, 'description')->textInput([ 'class' => '', 'placeholder' => 'Краткое описание', 'disabled' => false ]); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <input type="checkbox" id="new-field" name="new-field">
                        <label for="new-field" style="text-decoration: none;">Обязательное поле</label>
                    </div>
                </div>

                <?php echo $form->field($model, 'activity_id')->hiddenInput([ 'value' => $activity->id ])->label(false); ?>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<?php echo $this->registerJs('
    $(document).on("click", ".modal-trigger", function(event) {
        var element = $(event.target).parent();
        
        $.post(element.data("href"), {}, function(result) {
            $(".modal-content").html(result.content);
            
            $("#checked-calc-field").nestable({
                group: 1
            });
        });
    }); 
    
    $(document).on("click", ".js-add-new-field", function(event) {
        $("#form-new-field-add").submit();
    });
    
    $(document).on("input", ".field-item", function(event) {
        onFieldDataChanged($(event.target));
    });
    
    $(document).on("change", ".field-item", function(event) {
        onFieldDataChanged($(event.target));
    });
    
    $(document).on("click", ".js-btn-save-field", function(event) {
        var element = $(event.target).parent(), parent = element.closest("tr"), url = parent.data("url"), data = [];
        
        parent.find("input,select").each(function(index, item) {
            if ($(item).hasClass("checkbox")) {
                data.push({
                    field : $(item).data("field"),
                    value : $(item).is(":checked") ? 1 : 0
                });
            } else if ($(item).data("field") != undefined) {
                data.push({
                    field : $(item).data("field"),
                    value : $(item).val()
                });
            }
        });
        
        $.post(url, {
            data: data,
            field_id: parent.data("id")
        }, function(result) {
            Materialize.toast(result.msg, 2500);
        });
        
        element.fadeOut();
    });
    
    function onFieldDataChanged(element) {
        var parent = element.closest("tr");
        
        $(".btn-save-field" + parent.data("id")).fadeIn();
    }
    
    ////
    var check_fields_list = [];
    
    $(document).on("click", "#js-save-calc-value", function(event) {
        var element = $(event.target).parent(), items = [];

        $("#checked-calc-field .dd-item").each(function(ind, item) {
            items.push({
                id: $(item).data("id")
            });
        });
        
        if (items.length < 2) {
            Materialize.toast("Для продолжения необходимо выбрать поля.", 2500);
            return;
        }
        
        $.post(element.data("url"), {
            data: items,
            field_id: element.data("id"),
            calc_type: $("#field-calc-type").val()
        }, function(result) {
            Materialize.toast(result.msg, 2500);
        });            
    });    
    
    $(document).on("change", ".ch-calc-field", function(event) {
        var checked_calc_fields = getCalcCheckedFields(), element = $(event.target);

        if (element.is(":checked")) {       
            $("#checked-calc-field").append("<li class=\'dd-item\' data-id=\'" + element.data("id") + "\'><div class=\'dd-handle\'>" + element.data("name") + "</div></li>");
        } else {
            $("li[data-id=\'" + element.data("id") + "\']").remove();
        }     
        
        checked_calc_fields.length >= 2 ? $("#js-save-calc-value").fadeIn() : $("#js-save-calc-value").fadeOut();
        
    });
    
    function getCalcCheckedFields() {
        var calc_checked_fields = [];
         
        $(".ch-calc-field").each(function(ind, item) {
            if ($(item).is(":checked")) {
                calc_checked_fields.push({
                    id: $(item).data("id")
                });
            }
        });
        
        return calc_checked_fields;
    }
    
    $(document).on("click", ".collapsible-header", function(event) {
        var table = $(event.target).data("id") != undefined ? $(event.target) : $(event.target).parent();
        
        if (!table.hasClass("has-sort")) {
            var dragger = tableDragger(document.querySelector(".sortable-list-" + table.data("id")), {
                mode: "row",
            });
           
            dragger.on("drop", function(from, to, el) {
                var table = $(el), fields = [];
                
                table.find("tbody > tr").each(function(ind, item) {
                    fields.push($(item).data("id"));
                });
                
                $.post(table.data("url"), {
                    section: table.data("section-id"),
                    fields: fields
                }, function(result) {
                    Materialize.toast("Сортировка выполнена успешно.", 2500);
                });
            });
            
            table.addClass("has-sort");
        }        
    });
    
    var dragger = tableDragger(document.querySelector(".sortable-sections-list"), {
        mode: "row",
    });
    
    dragger.on("drop", function(from, to, el) {
        var table = $(el), sections = [];
        
        table.find("tbody > tr").each(function(ind, item) {
            sections.push($(item).data("id"));
        });
        
        $.post(table.data("url"), {
            activity: table.data("activity-id"),
            sections: sections
        }, function(result) {
            Materialize.toast("Сортировка выполнена успешно.", 2500);
        });
    });
    
    
', \yii\web\View::POS_READY);
