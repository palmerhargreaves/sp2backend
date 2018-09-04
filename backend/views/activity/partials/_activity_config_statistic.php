<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 14:22
 */

use yii\helpers\Url;

$colors = ["blue", "green", "red", "orange", "yellow"];
?>

<a id="activity-statistic-params"></a>
<h4 class="header">Настройка параметров статистики</h4>

<div class="row">
    <div class="col s12 m4 l3">
        <?php foreach ($section_templates as $section_template): ?>
        <div class="row block-row-item-<?php echo $section_template['section_template']->id; ?> <?php echo ($section_template['section'] && $section_template['section']->status) ? "block-row" : ""; ?>">
            <?php echo Yii::$app->controller->renderPartial('partials/blocks/_block_item', ['section_template' => $section_template]); ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="container-activity-statistic-data" class="col s12 m8 l9">

    </div>
</div>
