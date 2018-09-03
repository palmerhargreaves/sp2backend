<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.12.2017
 * Time: 16:59
 */
use yii\helpers\Url;

?>

<h4 class="header">Настройка слота</h4>

<div class="card-panel" style="padding-top: 3px;">
    <div class="row">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Привязка активностей к слоту</h6>
            </li>

            <?php $slot_activities = $slot->activities; ?>

            <?php foreach ($activities as $activity): ?>
                <li class="collection-item dismissable"
                    style="touch-action: pan-y; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <input class="ch-slot-config-activity-item" type="checkbox"
                           data-id="<?php echo $activity->id; ?>"
                           data-slot-id="<?php echo $slot->id; ?>"
                           data-url="<?php echo Url::to(['/mandatory-activities/slot-activity-set-unset']); ?>"
                           id="activity-id-<?php echo $activity->id; ?>" <?php echo array_key_exists($activity->id, $slot_activities) ? "checked" : ""; ?>>
                    <label for="activity-id-<?php echo $activity->id; ?>"
                           style="text-decoration: none;"><?php echo $activity->name; ?>
                    </label>

                    <span class="task-cat teal"><?php echo $activity->id; ?> </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
