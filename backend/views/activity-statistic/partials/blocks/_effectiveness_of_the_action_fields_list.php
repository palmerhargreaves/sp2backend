<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 16:03
 */

use yii\helpers\Url;

?>

<div class="card-panel">
    <h4 class="header2">Список полей</h4>
    <div class="row">
        <div class="col s12 m8 l12">
            <table id="mainTable"
                   data-url="<?php echo Url::to([ 'activity-fields/sort-fields' ]); ?>"
                   data-section-id="<?php echo $section->id; ?>"
                   class="table-responsive sortable-fields-table sortable-list-<?php echo $section->id; ?>">
                <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th>Название</th>
                    <th>Выводить в выгрузке</th>
                    <th>Выводить в статистике</th>
                    <th>Использовать как параметр</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody class="">
                <?php foreach ($fields as $field): ?>
                    <tr class="sortable-list-items block-field-<?php echo $field->id; ?>"
                        data-id="<?php echo $field->id; ?>"
                        data-section-id="<?php echo $section->id; ?>"
                        data-url="<?php echo Url::to([ "activity-statistic/save-field-data" ]); ?>">
                        <td class="sortable-item ">
                            <i class="mdi-hardware-gamepad handle"></td>
                        <td style="width: 35%;">
                            <input class="field-item" data-field="header" type="text"
                                   value="<?php echo $field->header; ?>"/>
                        </td>

                        <td style="width: 25%;">
                            <input type="checkbox" class="field-item checkbox"
                                   data-field="show_in_export"
                                   id="ch-field-show-in-export<?php echo $field->id; ?>" <?php echo $field->show_in_export ? "checked" : ""; ?>>
                            <label for="ch-field-show-in-export<?php echo $field->id; ?>"
                                   style="text-decoration: none;"
                                   class="tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Отображать в выгрузке'); ?>">&nbsp;</a></label>
                        </td>

                        <td style="width: 25%;">
                            <input type="checkbox" class="field-item checkbox"
                                   data-field="show_in_statistic"
                                   id="ch-field-show-in-statistic<?php echo $field->id; ?>" <?php echo $field->show_in_statistic ? "checked" : ""; ?>>
                            <label for="ch-field-show-in-statistic<?php echo $field->id; ?>"
                                   style="text-decoration: none;"
                                   class="tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Отображать в статистике'); ?>">&nbsp;</a></label>
                        </td>

                        <td style="width: 25%;">
                            <input type="checkbox" class="field-item checkbox"
                                   data-field="is_revenue_field"
                                   id="ch-field-is-revenue_field<?php echo $field->id; ?>" <?php echo $field->is_revenue_field ? "checked" : ""; ?>>
                            <label for="ch-field-is-revenue_field<?php echo $field->id; ?>"
                                   style="text-decoration: none;"
                                   class="tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Использовать как параметр'); ?>">&nbsp;</a></label>
                        </td>

                        <td>
                            <div class="row">
                                <a class="js-btn-save-field btn-save-field<?php echo $field->id; ?> btn-floating btn-flat waves-effect waves-light blue accent-2 white-text left tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Сохранить изменения'); ?>"
                                   style="margin-right: 7px; display:none;"
                                   href="#!"><i class="mdi-content-save"></i></a>

                                <?php if ($field->isCalcField()): ?>
                                    <a class="js-btn-edit-field btn-edit-field<?php echo $field->id; ?> btn-floating btn-flat waves-effect waves-light blue accent-2 white-text left tooltipped"
                                       data-position="top" data-delay="50"
                                       data-tooltip="<?php echo Yii::t('app', 'Редактировать поле'); ?>"
                                       style="margin-right: 7px;"
                                       data-id="<?php echo $field->id; ?>"
                                       data-section-id="<?php echo $field->parent_id; ?>"
                                       data-url="<?php echo Url::to([ 'activity-statistic/edit-calculated-field', 'id' => $field->id ]); ?>"
                                       href="#!"><i class="mdi-content-create"></i></a>
                                <?php endif; ?>

                                <a class="js-btn-delete-field btn-floating btn-flat waves-effect waves-light red accent-2 white-text left tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Удаление поля'); ?>"
                                   data-id="<?php echo $field->id; ?>"
                                   data-section-id="<?php echo $field->parent_id; ?>"
                                   data-url="<?php echo Url::to([ 'activity-statistic/delete-block-field', 'id' => $field->id ]); ?>"
                                   href="#!">
                                    <i class="mdi-action-highlight-remove"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (count($fields) > 0): ?>
    <div id="container-calculated-field" class="card-panel">
        <?php echo Yii::$app->controller->renderPartial('partials/_calculated_field_form', [ 'calculated_model' => $calculated_model, 'section' => $section ]); ?>
    </div>

<?php endif; ?>
