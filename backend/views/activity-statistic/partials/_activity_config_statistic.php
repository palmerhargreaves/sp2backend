<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 03.09.2018
 * Time: 14:22
 */

?>

<a id="activity-statistic-params"></a>
<h4 class="header">Настройка параметров статистики</h4>

<a id="js-config-statistic-params"
   style="position: absolute; top: 35px; right: 10px; display: none;"
   data-url="<?php echo \yii\helpers\Url::to([ 'activity-statistic/show-config' ]); ?>"
   class="btn-floating activator btn-move-up waves-effect waves-light green darken-2 right tooltipped"
   data-position="top"
   data-delay="50"
   data-tooltip="<?php echo Yii::t('app', 'Параметры статистики'); ?>"
   data-id="<?php echo $activity->id; ?>">

    <i class="mdi-action-settings"></i>
</a>

<div class="row">
    <div class="col s12 m4 l3">
        <table class="blocks-rows table-responsive sortable-fields-table"
               data-activity-id="<?php echo $activity->id; ?>"
               data-url="<?php echo \yii\helpers\Url::to(['activity-statistic/sort-blocks']); ?>">
            <tbody>
            <?php foreach ($section_templates as $section_template): ?>
                <tr data-id="<?php echo $section_template[ 'section' ] ? $section_template[ 'section' ]->id : 0 ?>">
                    <td>
                        <div class="row block-row-item-<?php echo $section_template[ 'section_template' ]->id; ?> <?php echo ( $section_template[ 'section' ] && $section_template[ 'section' ]->status ) ? "block-row" : ""; ?>">
                            <?php echo Yii::$app->controller->renderPartial('partials/blocks/_block_item', [ 'section_template' => $section_template ]); ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>

    <div id="container-activity-statistic-data" class="col s12 m8 l9">
        <?php echo $statistic_config_content; ?>
    </div>

    <div id="container-activity-statistic-fields-list" class="col s12 m8 l9"></div>
</div>
