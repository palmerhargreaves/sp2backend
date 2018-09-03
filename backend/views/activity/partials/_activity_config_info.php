<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 18:56
 */

?>
<div class="row">
    <div class="col s12">
        <p class="collections-title"><a href="<?php echo \yii\helpers\Url::to(['activity/info', 'id' => $activity->id]); ?>"><?php echo $activity->name; ?></a></p>

        <div class="row">
            <?php foreach ($activity->getConfigs() as $key => $config): ?>
            <div class="col s4 m4 l4">
                <span style="font-size: 0.7rem;" class="task-cat <?php echo $config['active'] ? "green" : "grey"; ?>"><?php echo $config["label"]; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
