<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 19.02.2018
 * Time: 2:48
 */

use richardfan\sortable\SortableGridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<div id="card-widgets" class="seaction">
    <div class="row">

        <div class="col s12 m12 l12">
            <h4 class="header"><?php echo Yii::t('app', 'Согласование статистики'); ?></h4>
            <p>Список согласования данных по статистике</p>

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
                                    'label' => 'Активность',
                                    'value' => function ($model) {
                                        return $model->activity->name;
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Дилер',
                                    'value' => function ($model) {
                                        return $model->dealer->name;
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Квартал',
                                    'value' => function ($model) {
                                        return $model->quarter;
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Год',
                                    'value' => function ($model) {
                                        return $model->year;
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Кто проверил',
                                    'value' => function ($model) {
                                        return $model->user ? $model->user->name : '';
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'label' => 'Статус',
                                    'value' => function ($model) {
                                        return $model->getCheckStatus();
                                    },
                                ],
                                [
                                    'format' => 'raw',
                                    'attribute' => Yii::t('app', 'Действия'),
                                    'value' => function ($model) {
                                        return '<a href="#modal-config-statistic-settings" data-id="'.$model->activity_id.'" data-url="'.Url::to(['/activity/show-history-pre-check', 'id' => $model->id]).'" class="js-show-config-modal modal-trigger btn-floating waves-effect waves-light grey tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'История проверки') . '"><i class="mdi-action-history"></i></a>';
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
        <div class="modal-content" style="padding-top: 0px;">

        </div>
    </div>
</div>

<?php $this->registerJs('
    $(document).on("click", ".js-show-config-modal", function(event) {
        var element = $(event.currentTarget);
        
        $.post(getElementData(element, "url"), {
            id: element.data("id")
        }, function(result) {
            $(".modal-content").html(result.content);
        });
    });  
',
    \yii\web\View::POS_READY);
