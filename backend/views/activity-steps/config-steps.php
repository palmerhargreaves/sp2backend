<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 20.11.2017
 * Time: 10:12
 */
use common\models\activity\steps\ActivityExtendedStatisticSteps;
use richardfan\sortable\SortableGridView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<div class="section">
    <div class="divider"></div>

    <div id="card-stats" class="section">
        <div class="row">
            <div class="col s12 m6 l12">
                <h4 class="header">Шаги активности</h4>
                <p>Список привязанных шагов к активности</p>

                <div class="row">
                    <div class="card">
                        <div class="col s12 m8 l12">
                            <?php Pjax::begin(); ?>
                            <?= SortableGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'sortUrl' => Url::to(['sortItem']),
                                'columns' => [
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'header',
                                        'value' => function ($model) {
                                            return $model->header;
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'description',
                                        'value' => function ($model) {
                                            return $model->description;
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'step_type',
                                        'value' => function ($model) {
                                            return ActivityExtendedStatisticSteps::getStepTypeLabel($model->step_type);
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => 'action_after',
                                        'value' => function ($model) {
                                            return $model->action_after;
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'attribute' => Yii::t('app', 'Действия'),
                                        'value' => function ($model) {
                                            return '
                                            <a href="' . Url::to(['/activity-steps/edit-step', 'id' => $model->id]) . '" class="btn-floating waves-effect waves-light tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Редактировать') . '"><i class="mdi-editor-mode-edit"></i></a>
                                            <a href="' . Url::to(['/activity-steps/delete-step', 'id' => $model->id]) . '" class="btn-floating waves-effect waves-light tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Удалить') . '"><i class="mdi-content-clear"></i></a>';
                                        }
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
    </div>
</div>

<div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
    <a class="btn-floating btn-large">
        <i class="mdi-communication-live-help"></i>
    </a>
    <ul>
        <li><a href="<?php echo Url::to(['/activity-steps/add-step', 'id' => $activity_id]); ?>"
               class="btn-floating red"
               style="transform: scaleY(0.4) scaleX(0.4) translateY(40px) translateX(0px); opacity: 0;">
                <i class="large mdi-content-add"></i>
            </a>
        </li>
    </ul>
</div>
