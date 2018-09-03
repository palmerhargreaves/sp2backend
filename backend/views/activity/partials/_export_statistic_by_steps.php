<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 24.11.2017
 * Time: 18:11
 */
?>

<h4 class="header">Параметры экспорта</h4>

<a id="js-save-calc-value"
   data-url="<?php echo \yii\helpers\Url::to([ 'activity/export-statistics' ]); ?>"
   style="top: -60px;" class="js-begin-export btn-floating activator btn-move-up waves-effect waves-light green darken-2 right "
   class="tooltipped"
   data-position="left"
   data-delay="50"
   data-tooltip="<?php echo Yii::t('app', 'Начать экспорт'); ?>"
   data-id="<?php echo $activity->id; ?>">
    <i class="mdi-file-file-download"></i>
</a>

<div class="card-panel" style="padding-top: 3px;">

    <div class="row">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Выберите шаг(и)</h6>
            </li>

            <?php foreach (\common\models\activity\steps\ActivityExtendedStatisticSteps::find()->where([ 'activity_id' => $activity->id ])->all() as $step): ?>
                <li class="collection-item dismissable"
                    style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <input class="ch-step-item" type="checkbox" id="step-id-<?php echo $step->id; ?>" data-id="<?php echo $step->id; ?>">
                    <label for="step-id-<?php echo $step->id; ?>"
                           style="text-decoration: none;"><?php echo $step->header; ?>
                    </label>
                </li>
            <?php endforeach; ?>

            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Выберите квартал</h6>
            </li>

            <li class="collection-item dismissable"
                style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">

                <input type="radio" id="js-q-0" name="quarters" value="">
                <label for="js-q-0">Все</label>
                
                <?php for ($q = 1; $q <= 4; $q++): ?>
                    <input type="radio" id="js-q-<?php echo $q; ?>" name="quarters" value="<?php echo $q; ?>">
                    <label for="js-q-<?php echo $q; ?>"><?php echo sprintf("%d квартал", $q); ?></label>
                <?php endfor; ?>

            </li>

        </ul>
    </div>
</div>
