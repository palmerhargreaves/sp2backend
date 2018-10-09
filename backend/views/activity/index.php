<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 17:10
 */

use richardfan\sortable\SortableGridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<!--chart dashboard start-->
<div id="chart-dashboard">
    <div class="row">
        <div class="col s12 m12 l12">
            <h4 class="header"><?php echo Yii::t('app', 'Статистика по активностям за тек. месяц / год'); ?></h4>
            <p>Вывод информации о заявках (кол. созданных, согласованных, активных)</p>

            <div class="card">
                <div id="activities-statistics-chart" class="graph"></div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Revenue by Month <i
                            class="mdi-navigation-close right"></i></span>
                    <table class="responsive-table">
                        <thead>
                        <tr>
                            <th data-field="id">ID</th>
                            <th data-field="month">Month</th>
                            <th data-field="item-sold">Item Sold</th>
                            <th data-field="item-price">Item Price</th>
                            <th data-field="total-profit">Total Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1</td>
                            <td>January</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>February</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>March</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>April</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>May</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>June</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>July</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>August</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Septmber</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Octomber</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>November</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>December</td>
                            <td>122</td>
                            <td>100</td>
                            <td>$122,00.00</td>
                        </tr>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>


    </div>
</div>
<!--chart dashboard end-->

<div class="divider"></div>
<div id="card-widgets" class="seaction">
    <div class="row">

        <div class="col s12 m12 l12">
            <h4 class="header"><?php echo Yii::t('app', 'Активности'); ?></h4>
            <p>Список активностей</p>

            <?php $view = $this; ?>
            <div class="row">
                <div class="card">
                    <div class="col s12 m8 l12">
                        <?php Pjax::begin(); ?>
                        <?= SortableGridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchProvider,
                            'sortUrl' => Url::to(['sortItem']),
                            'columns' => [
                                [
                                    'format' => 'raw',
                                    'attribute' => 'id',
                                    'value' => function ($model) {
                                        return $model->id;
                                    },
                                    'options' => [
                                        'width' => '3%'
                                    ]
                                ],
                                [
                                    'format' => 'raw',
                                    'attribute' => 'name',
                                    'value' => function ($model) use($view) {
                                        return $view->render('/activity/partials/_activity_config_info', ['activity' => $model]);
                                    },
                                    'options' => [
                                        'width' => '40%'
                                    ]
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Заявок (создано)',
                                    'value' => function ($model) use($view) {
                                        return $view->render('/activity/partials/_activity', ['activity' => $model]);
                                    },
                                    'options' => [
                                        'width' => '15%'
                                    ]
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => '% активности (тек. мес)',
                                    'value' => function ($model) use($view) {
                                        return $view->render('/activity/partials/_activity_percent', ['activity' => $model]);
                                    },
                                    'options' => [
                                        'width' => '15%'
                                    ]
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Заявок',
                                    'value' => function ($model) {
                                        return sprintf("<a href='' class='tooltipped' data-position=\"top\" data-delay=\"50\" data-tooltip='Список заявок созданных в активности'>%s</a>", $model->getModelsCount());
                                    },
                                    'options' => [
                                        'width' => '5%'
                                    ]
                                ],

                                [
                                    'format' => 'raw',
                                    'label' => 'Дилеров',
                                    'value' => function ($model) {
                                        return sprintf("<a href='' class='tooltipped' data-position=\"top\" data-delay=\"50\" data-tooltip='Список дилеров с заявками в активности'>%s</a>", $model->getDealersCount());
                                    },
                                    'options' => [
                                        'width' => '5%'
                                    ]
                                ],
                                [
                                    'format' => 'raw',
                                    'attribute' => Yii::t('app', 'Действия'),
                                    'value' => function ($model) {
                                        return '
                                            <a href="#modal-config-statistic-by-blocks" data-url="'.Url::to(['/activity-statistic/show-statistic-config', 'id' => $model->id]).'" data-content-container="modal-config-statistic-by-blocks" class="modal-trigger js-show-statistic-config btn-floating waves-effect waves-light green tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Конфигурация параметров статистики') . '"><i class="mdi-action-assessment"></i></a>
                                            <a href="#!" data-url="'.Url::to(['/activity/show-config-options', 'id' => $model->id]).'" class="js-show-config btn-floating waves-effect waves-light grey tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Конфигурация') . '"><i class="mdi-action-settings"></i></a>
                                            <a href="' . Url::to(['/activity/info', 'id' => $model->id]) . '" class="btn-floating waves-effect waves-light blue tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Редактировать') . '"><i class="mdi-editor-mode-edit"></i></a>
                                            <a href="' . Url::to(['/activity/delete', 'id' => $model->id]) . '" class="btn-floating waves-effect waves-light red tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Удалить') . '"><i class="mdi-content-clear"></i></a>';
                                    },
                                    'options' => [
                                        'width' => '15%'
                                    ]
                                ],

                            ],
                            'tableOptions' => [
                                'class' => 'table responsive-table'
                            ],
                            'layout' => "{items}\n{summary}\n{pager}"
                        ]); ?>
                        <?php Pjax::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-config-statistic-settings" class="modal">
        <div class="model-email-content" style="padding-top: 0px;">

        </div>
    </div>

    <div id="modal-config-special-agreement" class="modal">
        <div class="model-email-content" style="padding-top: 0px;">

        </div>
    </div>

    <div id="modal-config-agreement-by-user" class="modal">
        <div class="model-email-content" style="padding-top: 0px;">

        </div>
    </div>

    <div id="modal-config-statistic-by-blocks" class="modal modal-window bottom-sheet" style="min-height: 80%; max-height: 99%;">
        <div class="modal-content model-email-content modal-config-statistic-content">

        </div>
    </div>

    <div id="modal-activity-company-image" class="modal">
        <?php $form = ActiveForm::begin([
            'id' => 'form-activity-company-image',
            'action' => Url::to(['activity/upload-activity-company-type-image']),
            'fieldConfig' => [
            'template' => '{input}{error}'
        ], 'options' => [ 'class' => 'col s12', 'enctype' => 'multipart/form-data' ] ]); ?>

        <div class="modal-content">
            <nav class="red">
                <div class="nav-wrapper">
                    <div class="left col s12 m12 l12">
                        <h4 style="margin-top: 10px; margin-left: 20px;"><?php echo Yii::t('app', 'Добавить изображение'); ?></h4>
                    </div>
                </div>
            </nav>
        </div>

        <div class="model-email-content" style="padding-top: 0px;">
            <div class="row">
                <div class="row">
                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Файл</span>
                            <?php echo $form->field($activity_company_type_image_model, 'path')->fileInput([ 'class' => 'file btn-primary', 'multiple' => false ]); ?>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Загрузите файл">
                        </div>
                    </div>
                </div>

                <?php echo $form->field($activity_company_type_image_model, 'activity_id')->hiddenInput([ 'value' => 0 ])->label(false); ?>
                <?php echo $form->field($activity_company_type_image_model, 'company_type_id')->hiddenInput([ 'value' => 0 ])->label(false); ?>
                <?php echo $form->field($activity_company_type_image_model, 'id')->hiddenInput([ 'value' => 0 ])->label(false); ?>
            </div>

            <div class="row">
                <div class="input-field col s12">
                    <button type="submit" class="btn cyan waves-effect waves-light right">
                        <i class="mdi-content-save right"></i>&nbsp;<?= Yii::t('app', 'Добавить') ?>
                    </button>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $this->registerJs('
    window.activity_statistic = new ActivityStatistic({
        
    }).start();
    
    window.activity_company_type_image = new ActivityCompanyTypeImage({}).start();
    
    $(".activity-active").each(function(index, item) {
        $(item).sparkline($(item).data("items").split(":"), {
            type: \'bar\',
            height: \'40\',
            barWidth: 10,
            barSpacing: 4,
            barColor: \'#ff3737\',
            negBarColor: \'#81d4fa\',
            zeroColor: \'#81d4fa\',
            //tooltipFormat: $.spformat(\'{{value}}\', \'tooltip-class\')
            tooltipFormat: \'{{offset:offset}} {{value}}\',
            tooltipValueLookups: {
                \'offset\': {
                    0: \'Янв - \',
                    1: \'Фев - \',
                    2: \'Март - \',
                    3: \'Апр - \',
                    4: \'Май - \',
                    5: \'Июнь - \',
                    6: \'Июль - \',
                    7: \'Авг - \',
                    8: \'Сен - \',
                    9: \'Окт - \',
                    10: \'Ноя - \',
                    11: \'Дек - \',
                }
            },
        });     
    });
    
    $(document).on("click", ".js-show-config", function(e) {
        var element = $(e.currentTarget);
         
        $("#chat-out").html("");
        $(".chat-collapse").trigger("click");
        setTimeout(function() {
            $.post(getElementData(element, "url"), {}, function(result) {
                $("#chat-out").html(result.content);
                
                $(\'.chat-collapsible\').collapsible({
                    accordion: true // A setting that changes the collapsible behavior to expandable instead of the default accordion style
                }); 
                
                $(\'.chat-close-collapse\').click(function() {
                    $(\'.chat-collapse\').sideNav(\'hide\');
                });
                
                $(\'.tooltipped\').tooltip({
                    delay: 50
                });
            
               $(\'.modal-trigger\').leanModal({
                    dismissible: true, // Modal can be dismissed by clicking outside of the modal
                    opacity: .5, // Opacity of modal background
                    in_duration: 300, // Transition in duration
                    out_duration: 200, // Transition out duration
                    ready: function() { 
                    //alert(\'Ready\'); 
                    }, // Callback for Modal open
                    complete: function() { 
                    //alert(\'Closed\'); 
                    } // Callback for Modal close
               });
            });
        }, 200);
    });
    
    $.post("'.Url::to(['activity/get-statistics']).'", {}, function(result) {
        var data = [];
        
        $.each(result.data, function(ind, item) {
            data.push({
                period: item.date,
                completed: item.completed,
                declined: item.declined,
                in_wait: item.in_wait,
                with_reports: item.with_reports
            }); }
         );
        
        Morris.Area({
            element: \'activities-statistics-chart\',
            data: data,
            xkey: "period",
            ykeys: [\'completed\', \'declined\', \'in_wait\', \'with_reports\'],
            labels: [\'Выполненные\', \'Отмененные\', \'Активные\', \'С отчетами\'],
            pointSize: 2,
            hideHover: \'auto\',
            xLabelFormat: function(d) {
                return ("0" + (d.getMonth() + 1)).slice(-2);
            }
        });       
        		
    });
    
    $(document).on("change", ".activity-specialist", function(event) {
        var element = $(event.target), curr_element = getElementData(element, "activity-id", true);
        
        $.post(getElementData(element, "url"), {
            bind_data: curr_element.is(":checked") ? 1 : 0,
            activity_id: getElementData(element, "activity-id"),
            user_id: getElementData(element, "user-id"),
        }, function() {
            
        });
    });
    
    //Концигурация полей статистики
    $(document).on("click", ".js-show-statistic-fields-config-modal", function(event) {
        var element = $(event.target);

        $.post(getElementData(element, "url"), {
            id: getElementData(element, "activity-id")
        }, function(result) {
            $(".modal-content").html(result);
            
            $("select").material_select();
            makeTableDraggable(".sortable-statistic-fields-table", "activity-id");
        });
    });
    
    $(document).on("click", ".js-show-statistic-config-modal, .js-show-special-agreement-config-modal, .js-show-agreement-by-user-config-modal, .js-show-statistic-config", function(event) {
        var element = $(event.currentTarget);
        
        $.post(getElementData(element, "url"), {
            id: getElementData(element, "activity-id")
        }, function(result) {
            $("#" + element.data("content-container") + " .model-email-content").html(result);
        });
    });
    
    $(document).on("click", "#js-save-activity-statistic-config", function(event) {
        var element = $(event.target), fields = [];
        
        $(".form-config-field-" + getElementData(element, "id")).each(function(ind, el) {
            if ($(el).hasClass("checkbox")) {
                fields.push({
                    field: $(el).data("field"),
                    val: $(el).is(":checked") ? 1 : 0
                });                
            } else {
                        
            }
        });
        
        $.post(getElementData(element, "url"), {
            activity: getElementData(element, "id"),
            fields: fields 
        }, function(result) {
            result.success ? Materialize.toast("Данные успешно сохранены.", 2500) : Materialize.toast("Ошибка сохранения данных.", 2500);
        });
    });
    
    $(document).on("change", ".js-pre-check-user-item, .js-special-agreement-user-item, .js-agreement-by-user-item", function(event) {
        var element = $(event.currentTarget);
        
        $.post(element.closest("table").data("url"), {
            activity_id: element.data("activity-id"),
            user_id: element.data("user-id")
        }, function() {
            
        });
    });
    
    $(document).on("click", "#activity-special-agreement, #activity-agreement-by-user", function(event) {
        var element = $(event.currentTarget);
        
        $.post(element.data("url"), {
            id: element.data("activity-id"),
            allow_deny: element.is(":checked") ? 1 : 0
        }, function() {
            
        });
    });
',
    \yii\web\View::POS_READY);
