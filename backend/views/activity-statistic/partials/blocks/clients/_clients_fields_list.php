<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 11:38
 */

use common\models\activity\fields\ActivityExtendedStatisticFields;
use common\models\activity\fields\ActivityExtendedStatisticFieldsCalculated;
use yii\helpers\Url;

?>

<div class="card-panel">
    <h4 class="header2">Список полей</h4>
    <div class="row">
        <div class="col s12 m8 l12">
            <table id="mainTable"
                   data-url="<?php echo Url::to(['activity-fields/sort-fields']); ?>"
                   data-section-id="<?php echo $section->id; ?>"
                   class="table-responsive sortable-fields-table sortable-list-<?php echo $section->id; ?>">
                <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th>Название</th>
                    <th>Выводить в выгрузке</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody class="">
                <?php foreach ($fields as $field): ?>
                    <tr class="sortable-list-items block-field-<?php echo $field->id; ?>"
                        data-id="<?php echo $field->id; ?>"
                        data-section-id="<?php echo $section->id; ?>"
                        data-url="<?php echo Url::to(["activity-statistic/save-field-data"]); ?>">
                        <td class="sortable-item ">
                            <i class="mdi-hardware-gamepad handle"></td>
                        <td style="width: 35%;">
                            <input class="field-item" data-field="header" type="text"
                                   value="<?php echo $field->header; ?>"/>
                        </td>

                        <td style="width: 35%;">
                            <input type="checkbox" class="field-item checkbox"
                                   data-field="show_in_export"
                                   id="ch-field-show-in-export<?php echo $field->id; ?>" <?php echo $field->show_in_export ? "checked" : ""; ?>>
                            <label for="ch-field-show-in-export<?php echo $field->show_in_export; ?>"
                                   style="text-decoration: none;"
                                   class="tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Отображать в выгрузке'); ?>">&nbsp;</a></label>
                        </td>

                        <td>
                            <div class="row">
                                <a class="js-btn-save-field btn-save-field<?php echo $field->id; ?> btn-floating btn-flat waves-effect waves-light blue accent-2 white-text left tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Сохранить изменения'); ?>"
                                   style="margin-right: 7px; display:none;"
                                   href="#!"><i class="mdi-content-save"></i></a>

                                <a class="js-btn-delete-field btn-floating btn-flat waves-effect waves-light red accent-2 white-text left tooltipped"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Удаление поля'); ?>"
                                   data-id="<?php echo $field->id; ?>"
                                   data-section-id="<?php echo $field->parent_id; ?>"
                                   data-url="<?php echo Url::to(['activity-statistic/delete-block-field', 'id' => $field->id]); ?>"
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
    <div class="card-panel">
        <h4 class="header2">Добавление формулы</h4>

        <select id="field-calc-type" name="field-calc-type">
            <?php foreach (ActivityExtendedStatisticFields::getTypes() as $key => $type): ?>
                <option value="<?php echo $key; ?>" <?php echo $field->isCalcField() && $field->getCalcType() == $key ? "selected" : ""; ?>><?php echo $type; ?></option>
            <?php endforeach; ?>
        </select>

        <a id="js-save-calc-value"
           data-url="<?php echo \yii\helpers\Url::to(['activity-statistic/add-new-formula']); ?>"
           style="top: -103px; display: none;" class="btn-floating activator btn-move-up waves-effect waves-light green darken-2 right "
           class="tooltipped"
           data-position="top"
           data-delay="50"
           data-tooltip="<?php echo Yii::t('app', 'Сохранить выбранные поля'); ?>"
           data-id="<?php echo $section->id; ?>">
            <i class="mdi-content-save"></i>
        </a>

        <div class="row">
            <div class="col s12 m12 l12">
                <span class="blue-text text-darken-2">Список добавляемых полей</span>

                <p>Переместите поля для корректного вычисления создаваемого выражения</p>
                <div class="dd" id="checked-calc-field">
                </div>
            </div>
        </div>

        <div class="card-panel">
            <div class="row">
                <h4 class="header2">Список доступных полей</h4>
                <ul id="task-card" class="collection with-header">
                    <?php foreach (ActivityExtendedStatisticFields::find()->where(['parent_id' => $section->id])->all() as $field_item): ?>
                        <li class="collection-item dismissable"
                            style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            <input class="ch-calc-field" type="checkbox"
                                   data-id="<?php echo $field_item->id; ?>"
                                   data-name="<?php echo $field_item->header; ?>"
                                   id="field-id-<?php echo $field_item->id; ?>" <?php //echo in_array($field_item->id, $calc_fields) ? "checked" : ""; ?>>
                            <label for="field-id-<?php echo $field_item->id; ?>"
                                   style="text-decoration: none;"><?php echo $field_item->header; ?>
                                <a href="#" class="secondary-content">
                                    <span class="ultra-small"><?php echo $field_item->isCalcField() ? "Вычисляемое поле" : ""; ?></span></a>
                            </label>

                            <?php if ($field_item->isCalcField()): ?>
                                <span class="task-cat teal"><?php echo $field_item->getCalcFieldsList(); ?> </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>

            <div class="row">
                <h4 class="header2">Список доступных формул</h4>
                <ul id="task-card" class="collection with-header">
                    <?php foreach (ActivityExtendedStatisticFieldsCalculated::find()->where(['section_id' => $section->id])->all() as $item): ?>
                        <li class="collection-item dismissable"
                            style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            <input class="ch-calc-field" type="checkbox"
                                   data-id="<?php echo $item->id; ?>"
                                   data-name="<?php echo strip_tags($item->getCalcFieldsNames()); ?>"
                                   id="field-id-<?php echo $item->id; ?>" <?php //echo in_array($field_item->id, $calc_fields) ? "checked" : ""; ?>>
                            <label for="field-id-<?php echo $item->id; ?>"
                                   style="text-decoration: none;"><?php echo $item->getCalcFieldsNames(); ?>

                                <a href="#" class="secondary-content delete-formula-field"
                                   data-position="top" data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Удаление формулы'); ?>"
                                   data-id="<?php echo $field->id; ?>"
                                   data-section-id="<?php echo $field->parent_id; ?>"
                                   data-url="<?php echo Url::to(['activity-statistic/delete-formula-field', 'id' => $item->id]); ?>"
                                >
                                    <span class="ultra-small"><i class="mdi-action-highlight-remove"></i></span></a>
                            </label>
                        </li>
                    <?php endforeach; ?>

                </ul>
            </div>
        </div>
    </div>


<?php endif; ?>
