<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 30.11.2017
 * Time: 15:04
 */

use yii\helpers\Url;

?>
    <div id="modal-model-logs-history" class="modal modal-fixed-footer">
        <div class="modal-content">
            <!-- Vertical Timeline -->
            <section id="conference-timeline">
                <div class="loading-progress loading-progress-model-timeline" class="col s12 m8 l9">
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
            </section>
            <!-- // Vertical Timeline -->

            <div class="container" style="margin-top: 50px;">
                <div class="row">
                    <div class="card">
                        <div class="card-move-up teal waves-effect waves-block waves-light">
                            <div class="move-up">
                                <p class="margin white-text">Количество действий при согласовании заявки</p>
                                <canvas id="model-actions-when-agreement" height="102" width="270"
                                        style="width: 270px; height: 102px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h4 class="header2">Параметры фильтра</h4>
                <div class="row">
                    <div class="col s12">
                        <div class="row">
                            <div class="input-field col s3">
                                <input id="filter_start_date" type="date" class="datepicker_new">
                                <label for="filter_start_date" class="">От</label>
                            </div>

                            <div class="input-field col s3">
                                <input id="filter_end_date" type="date" class="datepicker_new">
                                <label for="filter_end_date" class="">До</label>
                            </div>

                            <div class="input-field col s3">
                                <select name="filter_model_type" id="filter_model_type">
                                    <option value="">Все записи</option>
                                    <option value="exclude_video">Исключить видеоролики</option>
                                    <option value="only_video">Только видеоролики</option>
                                </select>
                            </div>

                            <div class="input-field col s4">
                                <select name="filter_models_check" id="filter_models_check">
                                    <option value="agreement_period_by_days">За выбранный период</option>
                                    <option value="agreement_period_days_by_hours">За выбранный период (Часы)</option>
                                    <option value="agreement_period_all_days">Полная проверка</option>
                                    <option value="agreement_period_by_designer">Заявки, просроченные дизайнером</option>
                                </select>
                            </div>

                        </div>
                    </div>

                    <div class="col s12">
                        <div class="row">
                            <div class="input-field col s12">
                                <p>
                                    <input name="group_model_state" type="radio" id="models"
                                           value="<?php echo \common\models\logs\Log::OBJECT_TYPE_MODEL; ?>" checked>
                                    <label for="models">Макеты</label>

                                    <input name="group_model_state" type="radio" id="reports"
                                           value="<?php echo \common\models\logs\Log::OBJECT_TYPE_REPORT; ?>">
                                    <label for="reports">Отчеты</label>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col s12">
                        <div class="input-field col s12">
                            <button id="js_make_filter" class="btn cyan waves-effect waves-light right" type="submit"
                                    name="action">Фильтр
                                <i class="mdi-action-search right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    <div class="loading-progress" class="col s12 m8 l9">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <div id="container-data" style="display: none;">
        <div id="chart-dashboard">
            <div class="row">
                <div class="col s12 m12 l12">
                    <h4 class="header">Статистика по проверкам за выбранный период</h4>

                    <div class="card">
                        <div id="models-statistics-chart" class="graph"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="col s12 m12 l12">
            <div class="card-panel">
                <h4 class="header">Проверки по заявкам за выбранный период</h4>
                <p></p>

                <div id="container-filter-models-list" class="col s12 m8 l12"></div>
            </div>

        </div>
    </div>

    <div class="fixed-action-btn" style="bottom: 50px; right: 100px;">
        <a class="btn-floating btn-large">
            <i class="mdi-action-stars"></i>
        </a>
        <ul>
            <li>
                <a href="#!" id="js_export_models"
                   data-url="<?php echo \yii\helpers\Url::to([ '/agreement-models-statistic/export-models-data' ]); ?>"
                   class="btn-floating red tooltipped" data-position="top"
                   data-delay="50"
                   data-tooltip="<?php echo Yii::t('app', 'Экспорт данных'); ?>"
                   style="transform: scaleY(0.4) scaleX(0.4) translateY(40px) translateX(0px); opacity: 0;">
                    <i class="large mdi-file-file-download"></i>
                </a>
            </li>
        </ul>
    </div>

<?php $this->registerJs('
    var $chart = null, modelActionsChart = null;
    
    $(document).on("click", "#js_export_models", function(event) {
        var element = $(event.currentTarget), 
            model_state = $("#models").is(":checked"),
            report_state = $("#reports").is(":checked"),
            models_type = $("input[name=group_model_state]").map(function(ind, el) {
                if ($(el).is(":checked")) {
                    return $(el).val();
                }
            });
            
        showLoader();
        console.log($("#filter_end_date").parent().find("input[type=hidden]").val());
        $.post(element.data("url"), {
                filter_object_type: models_type[0],
                filter_start_date: $("#filter_start_date").parent().find("input[type=hidden]").val(),
                filter_end_date: $("#filter_end_date").parent().find("input[type=hidden]").val(),
                filter_model_type: $("#filter_model_type").val(),
                filter_models_check: $("#filter_models_check").val()
            }, function(result) {
                if (result.success) {
                    window.location.href = result.url;
                }
                
                hideLoader();                
        });
    });
    
    $(document).on("click", "#js_make_filter", function() {
        var models_type = $("input[name=group_model_state]").map(function(ind, el) {
            if ($(el).is(":checked")) {
                return $(el).val();
            }
        });
        
        var start_date = $("#filter_start_date").parent().find("input[name=_submit]"),
                end_date = $("#filter_end_date").parent().find("input[name=_submit]");        
        
        showProgress();
        $.post("' . Url::to([ 'agreement-models-statistic/models-statistics' ]) . '", {
            filter_object_type: models_type[0],
            filter_start_date: start_date != undefined ? start_date.val() : "",
            filter_end_date: end_date != undefined ? end_date.val() : "",
            filter_model_type: $("#filter_model_type").val(),
            filter_models_check: $("#filter_models_check").val()
        }, function(result) {
            applyModelsList(result);
            makeChart(result);
            
            hideProgress();    
        });        
    });
    
    $(document).on("click", ".js-model-agreement-timeline", function(e) {
        var element = $(e.currentTarget);
        
        $.post(element.data("url"), {
                model_id: element.data("model-id")
            },
            function(result) {
                $("#conference-timeline").html(result.content);
                
                console.log(modelActionsChart);
                if (modelActionsChart == null) {
                    modelActionsChart = createTimeLineRadarChart(result); 
                } else {
                    ///modelActionsChart.update();
                    modelActionsChart.destroy();
                    modelActionsChart = createTimeLineRadarChart(result);
                } 
            }
        );
    });
    
    function createTimeLineRadarChart(result) {
        var timelineChartData = {
            labels: result.chart_data.labels,
            datasets: [
                {
                    label: "",
                    fillColor: "rgba(255,255,255,0.2)",
                    strokeColor: "#fff",
                    pointColor: "#00796b",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "#fff",
                    data: result.chart_data.values
                } ],
        };
                    
        return new Chart(document.getElementById("model-actions-when-agreement").getContext("2d")).Radar(timelineChartData, {
            angleLineColor : "rgba(255,255,255,0.5)",//String - Colour of the angle line		    
            pointLabelFontFamily : "\'Roboto\',\'Helvetica Neue\', \'Helvetica\', \'Arial\', sans-serif",// String - Tooltip title font declaration for the scale label	
            pointLabelFontColor : "#fff",//String - Point label font colour
            pointDotRadius : 4,
            animationSteps:15,
            pointDotStrokeWidth : 2,
            pointLabelFontSize : 12,
            responsive: true
        });
    } 
    

    $.post("' . Url::to([ 'agreement-models-statistic/models-statistics' ]) . '", {}, function(result) {
        $("#container-data").show();
        
        applyModelsList(result);
        makeChart(result);
        
        hideProgress();    
    });
    
    function applyModelsList(result) {
        $("#container-filter-models-list").html(result.model_list);
        
        $(".tooltipped").tooltip({
            delay: 50
        });
        
        /*setTimeout(function() {
            $("#model_list_by_period").floatThead({
                position: "fixed",
                top:65
            });
        }, 1500);*/
        
        $(".modal-trigger").leanModal({
            dismissible: true, // Modal can be dismissed by clicking outside of the modal
            opacity: .5, // Opacity of modal background
            in_duration: 300, // Transition in duration
            out_duration: 200, // Transition out duration
            ready: function() { 
                 $(".loading-progress-model-timeline").show();
            }, // Callback for Modal open
            complete: function() { 
            //alert("Closed"); 
            } // Callback for Modal close
        });
    }       
    
    function makeChart(result) {
        var data = [];
        
        $.each(result.chart_data, function(ind, value) {
            data.push({
                period: ind,
                value: value
            }); }
        );
         
        if ($chart == null) {
            $chart = Morris.Bar({
                element: \'models-statistics-chart\',
                data: data,
                xkey: "period",
                ykeys: [\'value\'],
                labels: [\'Проверок\'],
                pointSize: 2,
                hideHover: \'auto\',
                resize: "auto",
                /*xLabelFormat: function(d) {
                    console.log(d);
                    return ("0" + (d.getMonth() + 1)).slice(-2);
                }*/
            });
        } else {
            $chart.setData(data);
        }
    }   
  
');
