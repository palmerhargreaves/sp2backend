<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 11:38
 */

use common\models\activity\ActivityExtendedStatisticSections;
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
                    <th>Значение</th>
                    <th>Дилер</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody class="">
                <?php foreach ($fields as $field): ?>
                    <tr class="sortable-list-items block-field-<?php echo $field->id; ?>"
                        data-id="<?php echo $field->id; ?>"
                        data-url="<?php echo Url::to(["activity-fields/save-data"]); ?>">
                        <td class="sortable-item ">
                            <i class="mdi-hardware-gamepad handle"></td>
                        <td style="width: 35%;">
                            <input class="field-item" data-field="header" type="text"
                                   value="<?php echo $field->header; ?>"/>

                            <?php
                            //Если вычисляемое поле выводим секцию и поле к которому поле привязано
                            if (ActivityExtendedStatisticFieldsCalculated::find()->where(['calc_field' => $field->id])->count()) {
                                $parent_field = ActivityExtendedStatisticFields::find(['id' => $field->parent_id])->one();
                                if ($parent_field) {
                                    $section = ActivityExtendedStatisticSections::find(['id' => $parent_field->parent_id])->one();

                                    if ($section) {
                                        echo sprintf('[%s]: %s', $section->header, $parent_field->header);
                                    }
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <input class="field-item" data-field="def_value"
                                   data-type="number"
                                   data-regexp="/^[0-9.]+$/"
                                   type="text"
                                   value="<?php echo $field->def_value; ?>"/>

                            <input class="field-item" data-field="editable"
                                   type="hidden"
                                   value="<?php echo $field->editable; ?>"/>
                        </td>
                        <td>
                            <?php echo $field->dealer ? $field->dealer->name : ''; ?>
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
