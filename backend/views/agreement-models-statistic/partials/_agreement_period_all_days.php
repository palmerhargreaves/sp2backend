<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 25.01.2018
 * Time: 10:44
 */

?>

<table id="model_list_by_period" class="responsive-table bordered striped">
    <thead style="background: #00bcd4;">
    <tr>
        <th style="width: 100px;"><?php echo Yii::t('app', 'Номер заявки'); ?></th>

        <?php $col_span_offset = []; ?>
        <?php foreach ($models_count_by_days as $day => $count): ?>
            <?php $col_span_offset[$day] = count($col_span_offset) + 1; ?>
            <th style="text-align: left; "><?php echo sprintf("%s д%s", $day, \common\utils\NumbersHelper::numberEnd($day, array( 'ень', 'ня', 'ней' ))); ?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody class="">

    <?php $prev_model = 0; ?>
    <?php foreach ($models as $model_id => $steps_models_list): ?>
        <?php $step_index = 1; ?>
        <?php foreach ($steps_models_list as $step_key => $step_data): ?>
            <tr style="border-left: 3px solid rgba(<?php echo $step_data[ 'color' ]; ?>);">
                <td>
                    <p style="font-weight: 500; font-size: 1rem;">
                        <a class="modal-trigger tooltipped js-model-agreement-timeline"
                           data-position="top"
                           data-delay="50"
                           data-tooltip="История выполнения заявки"
                           data-model-id="<?php echo $model_id; ?>"
                           data-url="<?php echo \yii\helpers\Url::to([ 'agreement-models-statistic/model-agreement-timeline' ]); ?>"
                           href="#modal-model-logs-history"># <?php echo $model_id; ?></a>
                    </p>
                    <p style="font-size: 0.8rem;"><?php echo sprintf('проверка: %s', $step_index++); ?></p>
                </td>

                <td colspan="<?php echo !empty($col_span_offset) ? $col_span_offset[$step_data['days']] : $step_data[ 'days' ]; ?>">
                    <div style="display: inline-block; float: left; width: 100%;">
                    <span style="float: left;">
                        <?php echo !empty($step_data[ 'steps_action' ][ 'first' ][ 'data' ]) ? $step_data[ 'steps_action' ][ 'first' ][ 'data' ][ 'status' ] : ''; ?>
                    </span>
                        <div class="model-time-period-arrow-line">&nbsp;</div>
                        <span style="float: right; margin-right: 30px;">
                        <?php echo !empty($step_data[ 'steps_action' ][ 'last' ][ 'data' ]) ? $step_data[ 'steps_action' ][ 'last' ][ 'data' ][ 'status' ] : ''; ?>
                    </span>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>

    <tfoot style="border-top: 1px solid #00bcd4;">
    <tr>
        <td>Итого:</td>
        <?php if (!empty($models_count_by_days)): ?>
            <?php foreach ($models_count_by_days as $day => $count): ?>
                <td><?php echo isset($models_count[ $day ]) ? $models_count[ $day ][ 'count' ] : 0; ?></td>
            <?php endforeach; ?>
        <?php else: ?>
            <?php foreach (range($min_days, $max_days) as $day): ?>
                <td><?php echo isset($models_count[ $day ]) ? $models_count[ $day ][ 'count' ] : 0; ?></td>
            <?php endforeach; ?>
        <?php endif; ?>
    </tr>
    </tfoot>
</table>

