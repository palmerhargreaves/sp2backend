<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 13:14
 */

use backend\components\helpers\ColorHelper;
use common\models\activity\Activity;
use yii\helpers\Url;

$activities_list = Activity::find()->where([ 'allow_extended_statistic' => true ])->orderBy([ 'id' => SORT_DESC ])->all(); ?>

    <div class="section">
        <div class="divider"></div>

        <div id="card-stats" class="section">
            <h4 class="header">Статистика по заполняемым данным</h4>
            <p>Отображает заполняемую статистику по активностям в разрезе текущего квартала</p>

            <div class="row">
                <?php foreach ($activities_list as $activity): $color = ColorHelper::getColor(); ?>
                    <div class="col s12 m6 l4">
                        <div class="card">
                            <div class="card-content <?php echo $color; ?> white-text">
                                <p class="card-stats-title"><i
                                            class="mdi-action-assessment"></i><?php echo $activity->name; ?></p>
                                <h4 class="card-stats-number"><?php echo $dealers_count = $activity->getExtendedStatisticTotalFieldsValuesFilledCount(); ?>
                                    дилер<?php echo \common\utils\NumbersHelper::numberEnd($dealers_count, [ '', 'а', 'ов' ]); ?> </h4>
                            </div>
                            <div class="card-action <?php echo $color; ?> darken-2">
                                <div id="activity-statistic-bar-<?php echo $activity->id; ?>"
                                     class="activity-statistic-bar center-align"
                                     data-items="<?php echo $activity->getExtendedStatisticInfoYear(); ?>">
                                    <canvas width="227" height="25"
                                            style="display: inline-block; width: 227px; height: 25px; vertical-align: top;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="work-collections" class="seaction">

            <div class="row">
                <div class="col s12 m12 l12">
                    <h4 class="header">Список активностей для статистики</h4>
                    <ul id="projects-collection" class="collection">
                        <li class="collection-item avatar">
                            <i class="mdi-action-group-work circle light-blue"></i>
                            <span class="collection-header">Активности</span>
                            <p>Настройка шагов / полей статистики для выбранной активности</p>
                            <a href="<?php echo Url::to([ 'activity/list' ]); ?>" class="secondary-content"><i
                                        class="mdi-action-list"></i></a>
                        </li>

                        <?php foreach ($activities_list as $activity): ?>
                            <li class="collection-item">
                                <div class="row">
                                    <div class="col s1">
                                        <span class="task-cat yellow darken-3"><?php echo $activity->id; ?></span>
                                    </div>

                                    <div class="col s3">
                                        <p class="collections-title">
                                            <a class="tooltipped"
                                               data-position="top"
                                               data-delay="50"
                                               data-tooltip="<?php echo Yii::t('app', 'Параметры активности'); ?>"
                                               href="<?php echo Url::to([ '/activity/info', 'id' => $activity->id ]); ?>"><?php echo $activity->name; ?></a>
                                        </p>
                                        <p class="collections-content">
                                            <span class="task-cat <?php echo $activity->finished ? "red" : "green" ?>"><?php echo $activity->finished ? "Завершена" : "Активна" ?></span>
                                        </p>
                                    </div>

                                    <div class="col s2">
                                    <span class="task-cat black ">
                                        <a class="tooltipped"
                                           data-position="top"
                                           data-delay="50"
                                           data-tooltip="<?php echo Yii::t('app', 'Конфигурация шагов статистики'); ?>"
                                           style="color: #fff;"
                                           href="<?php echo Url::to([ '/activity-steps/config-steps', 'id' => $activity->id ]); ?>">Шагов: <?php echo $activity->getSteps(true); ?></a></span>
                                    </div>
                                    <div class="col s2">
                                    <span class="task-cat black ">
                                        <a style="color: #fff;"
                                           class="tooltipped"
                                           data-position="top"
                                           data-delay="50"
                                           data-tooltip="<?php echo Yii::t('app', 'Конфигурация полей статистики'); ?>"
                                           href="<?php echo Url::to([ '/activity-fields/config-fields', 'id' => $activity->id ]); ?>">Поля: <?php echo $activity->getFields(true); ?></a></span>
                                    </div>

                                    <div class="col s3">
                                        <div id="activity-statistic-completed-by-quarter-<?php echo $activity->id; ?>"
                                             class="activity-statistic-by-quarter-bar center-align"
                                             data-items="<?php echo $activity->getActivityStatisticCompleteByQ(); ?>"
                                             data-activity-id="<?php echo $activity->id; ?>"
                                             data-url="<?php echo Url::to([ '/activity/export-statistics' ]); ?>"
                                             style="cursor: pointer;">
                                            <canvas width="128" height="25"
                                                    style="display: inline-block; width: 128px; height: 25px; vertical-align: top;"></canvas>
                                        </div>
                                    </div>

                                    <?php if ($activity->getSteps(true)): ?>
                                        <div class="col s1">
                                            <a class="modal-trigger btn-floating btn-flat waves-effect waves-light black accent-2 white-text left tooltipped"
                                               data-position="top"
                                               data-delay="50"
                                               data-tooltip="<?php echo Yii::t('app', 'Параметры экспорта статистики'); ?>"
                                               data-href="<?php echo Url::to([ '/activity/config-export-statistics', 'id' => $activity->id ]); ?>"
                                               href="#modal-dialog"
                                               style="margin-right: 7px;">
                                                <i class="mdi-editor-insert-chart"></i>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
            <!-- Floating Action Button -->
            <!--<div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
                <a class="btn-floating btn-large">
                    <i class="mdi-action-stars"></i>
                </a>
                <ul>
                    <li><a href="css-helpers.   html" class="btn-floating red"><i class="large mdi-communication-live-help"></i></a></li>
                    <li><a href="app-widget.html" class="btn-floating yellow darken-1"><i class="large mdi-device-now-widgets"></i></a></li>
                    <li><a href="app-calendar.html" class="btn-floating green"><i class="large mdi-editor-insert-invitation"></i></a></li>
                    <li><a href="app-email.html" class="btn-floating blue"><i class="large mdi-communication-email"></i></a></li>
                </ul>
            </div>-->
            <!-- Floating Action Button -->
        </div>

        <div id="modal-dialog" class="modal modal-window bottom-sheet" style="max-height: 80%;">
            <div class="modal-content modal-config-statistic-content">

            </div>
        </div>
    </div>


<?php echo $this->registerJs('
    $(".activity-statistic-bar").each(function(i, item) {
        $(item).sparkline($(item).data("items").split(":"), {
            tooltipFormat: \'{{offset:offset}} {{value}}\',
            tooltipValueLookups: {
                \'offset\': {
                    0: \'Янв\',
                    1: \'Фев\',
                    2: \'Март\',
                    3: \'Апр\',
                    4: \'Май\',
                    5: \'Июнь\',
                    6: \'Июль\',
                    7: \'Авг\',
                    8: \'Сен\',
                    9: \'Окт\',
                    10: \'Ноя\',
                    11: \'Дек\',
                }
            },
            type: \'bar\',
            height: \'25\',
            barWidth: 7,
            barSpacing: 4,
            barColor: \'#C7FCC9\',
            negBarColor: \'#81d4fa\',
            zeroColor: \'#81d4fa\',
        }); 
    });
    
    $(".activity-statistic-by-quarter-bar").each(function(i, item) {
        $(item).sparkline($(item).data("items").split(":"), {
            tooltipFormat: \'{{offset:offset}} {{value}} \',
            tooltipValueLookups: {
                \'offset\': {
                    0: \'1 квартал - \',
                    1: \'2 квартал - \',
                    2: \'3 квартал - \',
                    3: \'4 квартал - \',                    
                }
            },
            type: \'bar\',
            height: \'35\',
            barWidth: 15,
            barSpacing: 4,
            barColor: \'#fb1010\',
            negBarColor: \'#81d4fa\',
            zeroColor: \'#81d4fa\',
        }); 
    });
    
    $(".activity-statistic-by-quarter-bar").bind("sparklineClick", function(e) {
        var sparkLine = e.sparklines[0], region = sparkLine.getCurrentRegionFields(), element = $(e.target);
        
        if (region[0].offset != undefined) {
        
            showLoader();
            $.post(element.data("url"), {
                activity_id: element.data("activity-id"),
                q: region[0].offset + 1
            }, function(result) {
                exportResult(result);
            });       
        }
    });
    
    $(document).on("click", ".modal-trigger", function(event) {
        var element = $(event.target).parent();

        $.post(element.data("href"), {}, function(result) {
            $(".modal-config-statistic-content").html(result);
            
            $(\'select\').material_select();
            
            $(\'.tooltipped\').tooltip({
                delay: 50
            });
        });
    });
    
    $(document).on("click", ".js-begin-export", function(event) {
        var element = $(event.target).parent(), 
            checked_steps = $(".ch-step-item").map(function(index, item) {
                if ($(item).is(":checked")) {
                    return $(item).data("id");
                }
               
                return 0;
            }).filter(function(index, value) {
                return value > 0 ? value : 0;
            }), 
            quarter = $("input[name=quarters]").map(function(index, item) {
                return $(item).is(":checked") ? $(item).val() : 0;
            }).filter(function(index, value) {
                return value > 0;
            }), steps_data = [];
            
            if (checked_steps.length == 0) {
                swal({
                    title: "Ошибка",
                    text: "Для продолжения экспорта, выберите шаг(и)!",
                    type: \'error\',
                });
                return;
            }
            
        checked_steps.each(function(ind, value) {
            steps_data.push({
                step_id: value
            });
        });
        
        showLoader();
        $.post(element.data("url"), {
            activity_id: element.data("id"), 
            steps: steps_data,
            q: quarter[0]
            }, 
            function(result) {
                exportResult(result);
        });
        
    });
    
    function exportResult(result) {
        hideLoader();
                
        if (result.success) {
           window.location.href = result.url;
        } else {
            swal({
                title: "Ошибка",
                text: "Нет данных для экспорта!",
                type: \'error\',
            });
        }
       
    }
', \yii\web\View::POS_READY);
