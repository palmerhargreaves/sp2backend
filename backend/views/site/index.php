<?php

use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<p class="caption">Краткая информация об основных данных (активности, заявки, сообщения)</p>

<div class="divider"></div>

<!--card stats start-->
<div id="card-stats">
    <div class="row">
        <h4>Информация по активностям</h4>
        <p>Всего активностей, активных, завершенных, скрытых</p>

        <div class="divider"></div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content  green white-text">
                    <p class="card-stats-title"><i class="mdi-action-group-work"></i> Активностей</p>
                    <h4 class="card-stats-number">
                        <?php
                        echo \common\models\activity\Activity::find()->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action  green darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content pink lighten-1 white-text">
                    <p class="card-stats-title"><i class="mdi-action-trending-up"></i> Активных</p>
                    <h4 class="card-stats-number"><?php echo \common\models\activity\Activity::find()->where([ 'finished' => false ])->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action  pink darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content blue-grey white-text">
                    <p class="card-stats-title"><i class="mdi-navigation-expand-less"></i> Завершенных</p>
                    <h4 class="card-stats-number"><?php echo \common\models\activity\Activity::find()->where([ 'finished' => true ])->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action blue-grey darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content purple white-text">
                    <p class="card-stats-title"><i class="mdi-action-visibility-off"></i>Скрытых</p>
                    <h4 class="card-stats-number"><?php echo \common\models\activity\Activity::find()->where([ 'hide' => true ])->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action purple darken-2">

                </div>
            </div>
        </div>
    </div>
</div>
<!--card stats end-->

<!-- //////////////////////////////////////////////////////////////////////////// -->

<!--card widgets start-->
<div id="card-widgets">

    <!--work collections start-->
    <div id="work-collections">
        <div class="row">
            <div class="col s12 m12 l12">
                <ul id="projects-collection" class="collection">
                    <li class="collection-item avatar">
                        <i class="mdi-file-folder circle light-blue darken-2"></i>
                        <span class="collection-header">Активности</span>
                        <p>Список текущих активностей</p>
                        <a href="<?php echo \yii\helpers\Url::to([ '/activity/list' ]); ?>" class="secondary-content"><i
                                    class="mdi-action-list"></i></a>
                    </li>

                    <?php foreach (\common\models\activity\Activity::find()->where([ 'finished' => 0 ])->orderBy([ 'position' => SORT_ASC ])->all() as $activity): ?>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s1">
                                    <?php echo $activity->id; ?>
                                </div>
                                <div class="col s4">
                                    <p class="collections-title"><a
                                                href="<?php echo \yii\helpers\Url::to([ "activity/info", "id" => $activity->id ]); ?>"><?php echo $activity->name; ?></a>
                                    </p>
                                    <p style="font-size: 0.8rem;"
                                       class="collections-content"><?php echo sprintf('%s - %s', $activity->start_date, $activity->end_date); ?></p>
                                </div>
                                <div class="col s1">
                                    <span class="task-cat <?php echo $activity->hide ? "cyan" : "grey"; ?>"><?php echo sprintf("скрыта: %s", $activity->hide ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat <?php echo $activity->mandatory_activity ? "green" : "grey"; ?>"><?php echo sprintf("обязательная: %s", $activity->mandatory_activity ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat <?php echo $activity->is_limit_run ? "red" : "grey"; ?>"><?php echo sprintf("вып.только раз: %s", $activity->is_limit_run ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat blue-grey"><?php echo sprintf("%s", $activity->company->name); ?></span>
                                </div>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>
</div>

<div class="divider"></div>

<div id="card-stats">
    <div class="row">
        <h4>Информация по заявкам</h4>
        <p>Отображается информация по заявкам за <?php echo date('Y-m'); ?> </p>

        <div class="divider"></div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content  green white-text">
                    <p class="card-stats-title"><i class="mdi-action-group-work"></i> Заявок</p>
                    <h4 class="card-stats-number"><?php echo \common\models\agreement_model\AgreementModel::getModelsCount(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action  green darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content pink lighten-1 white-text">
                    <p class="card-stats-title"><i class="mdi-action-trending-up"></i> Завершенных</p>
                    <h4 class="card-stats-number"><?php echo \common\models\agreement_model\AgreementModel::getModelsCount(null, \common\models\activity\utils\ActivitiesStatistics::MODEL_COMPLETED); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action  pink darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content blue-grey white-text">
                    <p class="card-stats-title"><i class="mdi-navigation-expand-less"></i> Активных</p>
                    <h4 class="card-stats-number"><?php echo \common\models\agreement_model\AgreementModel::getModelsCount(null, [ \common\models\activity\utils\ActivitiesStatistics::MODEL_IN_WAIT, \common\models\activity\utils\ActivitiesStatistics::MODEL_IN_WAIT_SPECIALIST ]); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action blue-grey darken-2">

                </div>
            </div>
        </div>
        <div class="col s12 m6 l3">
            <div class="card">
                <div class="card-content purple white-text">
                    <p class="card-stats-title"><i class="mdi-action-visibility-off"></i>Отмененных</p>
                    <h4 class="card-stats-number"><?php echo \common\models\agreement_model\AgreementModel::getModelsCount(null, \common\models\activity\utils\ActivitiesStatistics::MODEL_DECLINED); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>
                <div class="card-action purple darken-2">

                </div>
            </div>
        </div>
    </div>
</div>

<!--card stats end-->
<div class="row">
    <div class="col s12 m12 l12">
        <ul id="issues-collection" class="collection">
            <li class="collection-item avatar">
                <i class="mdi-action-bug-report circle red darken-2"></i>
                <span class="collection-header">Последние заявки</span>
                <p>Список заявок созданных за <?php echo date('Y-m'); ?></p>
                <!--<a href="#" class="secondary-content"><i class="mdi-action-grade"></i></a>-->
            </li>
        </ul>
    </div>

    <div class="col s12 m12 l12">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataModelsProvider,
            'filterModel' => $searchModelsProvider,
            'columns' => [
                [
                    'format' => 'raw',
                    'attribute' => 'id',
                    'value' => function ( $model ) {
                        return $model->id;
                    },
                    'options' => [
                        'width' => '3%'
                    ]
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'name',
                    'value' => function ( $model ) {
                        return $model->name;
                    },
                    'options' => [
                        'width' => '20%'
                    ]
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'activity_id',
                    'value' => function ( $model ) {
                        return $model->activity->name;
                    },
                    'options' => [
                        'width' => '20%'
                    ],
                    'filter' => \yii\helpers\ArrayHelper::map(\common\models\activity\Activity::find()->where(['finished' => false])->orderBy(['id' => SORT_DESC])->all(), 'id', 'name')

                ],
                [
                    'format' => 'raw',
                    'attribute' => 'dealer_id',
                    'value' => function ( $model ) {
                        return $model->dealer->name;
                    },
                    'options' => [
                        'width' => '20%'
                    ],
                    'filter' => \yii\helpers\ArrayHelper::map(\common\models\dealers\Dealers::find()->where(['status' => true])->orderBy(['id' => SORT_ASC])->all(), 'id', 'name')
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'model_category_id',
                    'value' => function ( $model ) {
                        return $model->model_category_id;
                    },
                    'options' => [
                        'width' => '5%'
                    ]
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'period',
                    'value' => function ( $model ) {
                        return $model->period;
                    },
                    'options' => [
                        'width' => '5%'
                    ]
                ],
                [
                    'format' => 'raw',
                    'attribute' => 'cost',
                    'value' => function ( $model ) {
                        return $model->cost;
                    },
                    'options' => [
                        'width' => '5%'
                    ]
                ],

                [
                    'format' => 'raw',
                    'attribute' => Yii::t('app', 'Действия'),
                    'value' => function ( $model ) {
                        return '<a href="' . Url::to([ '/activity/info', 'id' => $model->id ]) . '" class="btn-floating waves-effect waves-light blue tooltipped" data-position="top" data-delay="50" data-tooltip="' . Yii::t('app', 'Редактировать') . '"><i class="mdi-editor-mode-edit"></i></a>';
                    },
                    'options' => [
                        'width' => '10%'
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
<!--work collections end-->

<!-- Floating Action Button -->
<!--<div class="fixed-action-btn" style="bottom: 50px; right: 19px;">
    <a class="btn-floating btn-large">
        <i class="mdi-action-stars"></i>
    </a>
    <ul>
        <li><a href="css-helpers.html" class="btn-floating red"><i
                        class="large mdi-communication-live-help"></i></a></li>
        <li><a href="app-widget.html" class="btn-floating yellow darken-1"><i
                        class="large mdi-device-now-widgets"></i></a></li>
        <li><a href="app-calendar.html" class="btn-floating green"><i
                        class="large mdi-editor-insert-invitation"></i></a></li>
        <li><a href="app-email.html" class="btn-floating blue"><i class="large mdi-communication-email"></i></a>
        </li>
    </ul>
</div>-->
<!-- Floating Action Button -->


</div>

<?php $this->registerJs('
    $(document).on("pjax:success", function() {
        $(\'select\').material_select();
    });
', \yii\web\View::POS_READY);