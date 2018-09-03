<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 20.11.2017
 * Time: 23:04
 */

use common\models\activity\fields\ActivityExtendedStatisticFields;

if ($field): ?>
    <h4 class="header">Настройка вычисляемого поля</h4>

    <select id="field-calc-type" name="field-calc-type" style="display: block;">
        <?php foreach (ActivityExtendedStatisticFields::getTypes() as $key => $type): ?>
            <option value="<?php echo $key; ?>" <?php echo $field->isCalcField() && $field->getCalcType() == $key ? "selected" : ""; ?>><?php echo $type; ?></option>
        <?php endforeach; ?>
    </select>

    <a id="js-save-calc-value"
       data-url="<?php echo \yii\helpers\Url::to(['activity-fields/save-calc-fields']); ?>"
       style="top: -110px;" class="btn-floating activator btn-move-up waves-effect waves-light green darken-2 right "
       class="tooltipped"
       data-position="top"
       data-delay="50"
       data-tooltip="<?php echo Yii::t('app', 'Сохранить выбранные поля'); ?>"
       data-id="<?php echo $field->id; ?>">
        <i class="mdi-content-save"></i>
    </a>

    <div class="card-panel" style="padding-top: 3px;">
        <div class="row">
            <div class="col s12 m12 l12">
                <span class="blue-text text-darken-2">Список добавляемых полей</span>

                <p>Переместите поля для корректного вычисления создаваемого выражения</p>
                <div class="dd" id="checked-calc-field">
                    <?php foreach ($field->getCalcFields() as $calc_field): ?>
                        <li class='dd-item' data-id='<?php echo $calc_field->id; ?>'><div class='dd-handle'><?php echo $calc_field->getCalcFieldName(); ?></div></li>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <ul id="task-card" class="collection with-header">
                <?php $calc_fields = $field->getCalcFieldsIds(); ?>

                <?php foreach (\common\models\activity\ActivityExtendedStatisticSections::find()
                                   ->where([ 'activity_id' => $field->activity_id])
                                   ->orderBy(['id' => SORT_DESC])->all() as $section): ?>
                    <li class="collection-header blue-grey">
                        <h6 class="task-card-title"><?php echo $section->header; ?></h6>
                    </li>

                    <?php foreach (ActivityExtendedStatisticFields::find()->where([ 'parent_id' => $section->id ])->andWhere(['!=', 'id', $field->id])->all() as $field_item): ?>
                        <li class="collection-item dismissable"
                            style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                            <input class="ch-calc-field" type="checkbox"
                                   data-id="<?php echo $field_item->id; ?>"
                                   data-name="<?php echo $field_item->header; ?>"
                                   id="field-id-<?php echo $field_item->id; ?>" <?php echo in_array($field_item->id, $calc_fields) ? "checked" : ""; ?>>
                            <label for="field-id-<?php echo $field_item->id; ?>" style="text-decoration: none;"><?php echo $field_item->header; ?>
                                <a href="#" class="secondary-content">
                                    <span class="ultra-small"><?php echo $field_item->isCalcField() ? "Вычисляемое поле" : ""; ?></span></a>
                            </label>

                            <?php if ($field_item->isCalcField()): ?>
                                <span class="task-cat teal"><?php echo $field_item->getCalcFieldsList(); ?> </span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php
else:
    echo "Поле не найдено";
endif;
