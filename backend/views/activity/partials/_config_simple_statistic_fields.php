<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 28.11.2017
 * Time: 16:29
 */

use common\models\activity\ActivityFields;
use yii\helpers\Url;

?>
<h4 class="header">Параметры статистики</h4>

<p>Список полей привязанных к статистике</p>
<div class="col s12 m8 l12">
    <?php foreach ( \common\models\activity\ActivityVideoRecordsStatistics::find()->where([ 'activity_id' => $activity->id ])->all() as $statistic ): ?>
    <?php foreach ( \common\models\activity\ActivityVideoRecordsStatisticsHeaders::find()->where([ 'parent_id' => $statistic->id ])->all() as $statistic_header ): ?>

    <table id=""
           data-url="<?php echo Url::to([ 'activity/sort-statistic-fields' ]); ?>"
           data-activity-id="<?php echo $activity->id; ?>"
           class="table-responsive sortable-statistic-fields-table sortable-list-<?php echo $activity->id; ?>">
        <thead>
        <tr>
            <th style="width: 50px;"></th>
            <th>Поле</th>
            <th>Группа</th>
            <th>Тип</th>
            <th>Контент</th>
            <th>Обязательное</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        </thead>

        <tbody class="">
        <?php $total_fields = 0; ?>
        <?php foreach (\common\models\activity\ActivityFields::find()->where([ 'activity_id' => $activity->id, 'parent_header_id' => $statistic_header->id ])->orderBy([ 'position' => SORT_ASC ])->all() as $field): ?>
            <tr class="sortable-list-items"
                data-id="<?php echo $field->id; ?>"
                data-url="<?php echo Url::to([ "activity/save-statistic-field-data" ]); ?>">
                <td class="sortable-item "><i class="mdi-hardware-gamepad handle"></td>
                <td style="width: 35%;">
                    <input class="field-item" data-field="name" type="text"
                           value="<?php echo $field->name; ?>"/>
                </td>
                <td>
                    <input class="field-item" data-field="description"
                           type="text"
                           value="<?php echo $field->description; ?>"/>
                </td>
                <td style="width: 20%;">
                    <select class="field-item select-config-statistic" data-field="type">
                        <?php foreach (ActivityFields::getFieldTypesList() as $type_key => $type_label): ?>
                            <option value="<?php echo $type_key; ?>" <?php echo $type_key == $field->type ? "selected" : ""; ?>><?php echo $type_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td style="width: 20%;">
                    <select class="field-item" data-field="content">
                        <?php foreach (ActivityFields::getContentTypesList() as $type_key => $type_label): ?>
                            <option value="<?php echo $type_key; ?>" <?php echo $type_key == $field->content ? "selected" : ""; ?>><?php echo $type_label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td style="text-align: center;">
                    <input type="checkbox" class="field-item checkbox"
                           data-field="required"
                           id="ch-field-required<?php echo $field->id; ?>" <?php echo $field->req ? "checked" : ""; ?>>
                    <label for="ch-field-required<?php echo $field->id; ?>"
                           style="text-decoration: none;" class="tooltipped"
                           data-position="top" data-delay="50"
                           data-tooltip="<?php echo Yii::t('app', 'Обязательное поле для заполнения'); ?>">&nbsp;</a></label>
                </td>
                <td style="text-align: center;">
                    <input type="checkbox" class="field-item checkbox"
                           data-field="required"
                           id="ch-status<?php echo $field->id; ?>" <?php echo $field->status ? "checked" : ""; ?>>
                    <label for="ch-status<?php echo $field->id; ?>"
                           style="text-decoration: none;" class="tooltipped"
                           data-position="top" data-delay="50"
                           data-tooltip="<?php echo Yii::t('app', 'Статус'); ?>">&nbsp;</a></label>
                </td>
                <td>
                    <div class="row">
                        <a class="btn-floating btn-flat waves-effect waves-light red accent-2 white-text left tooltipped"
                           data-position="top" data-delay="50"
                           data-tooltip="<?php echo Yii::t('app', 'Удаление поля'); ?>"
                           href="<?php echo Url::to([ 'activity/delete-statistic-field', 'id' => $field->id ]); ?>"><i
                                    class="mdi-action-highlight-remove"></i></a>
                    </div>
                </td>
            </tr>
            <?php $total_fields++; ?>
        <?php endforeach; ?>
        </tbody>
        </tfoot>
    </table>

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
                                <span class="task-cat cyan"><?php echo sprintf('%s: %d', Yii::t('app', 'Все полей в разделе'), $total_fields); ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </td>
        </tr>
    </table>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
