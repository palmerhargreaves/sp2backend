<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 20:00
 */
?>

<div id="activity-active-<?php echo $activity->id; ?>" class="activity-active" data-items="<?php echo $activity->getActiveStats(); ?>">
    <canvas width="200" height="30" style="display: inline-block; width: 200px; height: 30px; vertical-align: top;"></canvas>
</div>
