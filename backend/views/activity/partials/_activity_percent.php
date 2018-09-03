<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 20:49
 */
$percent = $activity::getActivityPercentByMonth($activity->id);

echo  sprintf('%s %%', $percent);
?>

<div class="progress"><div class="determinate" style="width: <?php echo $percent; ?>%"></div></div>