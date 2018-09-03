<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 07.07.2017
 * Time: 13:57
 */
use common\models\user\User;
use yii\bootstrap\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<div class="wrapper">
    <div class="row row-wider">
        <div class="panel panel-flat">
            <div class="table-responsive">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                $text = "<div class=\"list-contacts list-contacts-inline\" style=\"width: 160px;\">
                                        <a href='" . Url::to(['/user/update', 'id' => $model->getId()]) . "' class=\"list-contacts-item \">
                                            <img src='" . (empty($model->image) ? "images/user.png" : $model->image) . "' />" .
                                    $model->username
                                    . "</a></div>";

                                return $text;
                            },
                            'label' => '#',
                            'options' => ['width' => '10%']
                        ],
                        [
                            'format' => 'raw',
                            'attribute' => 'name',
                            'value' => function ($model) {
                                return sprintf('%s', $model->getFullname());
                            }
                        ],
                        [
                            'attribute' => 'role',
                            'value' => function ($model) {
                                return $model->getRoleName();
                            },
                            'filter' => $roles,
                        ],
                        'email:email',
                        [
                            'format' => 'raw',
                            'attribute' => 'status',
                            'value' => function ($model) {
                                $label = false;
                                switch ($model->status) {
                                    case 1:
                                    case User::STATUS_ACTIVE:
                                        $label = Html::tag('span', Yii::t('app', 'Активен'), ['class' => "badge badge-success"]);
                                        break;
                                    case 0:
                                        $label = Html::tag('span', Yii::t('app', 'Отключен'), ['class' => "badge badge-flat badge-danger"]);
                                        break;
                                }

                                return $label;
                            },
                            'filter' => $searchModel->getStatuses(),
                            'options' => [
                                'width' => '5%'
                            ]
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'options' => [
                                'width' => '5%'
                            ],
                            'template' => '{update} {delete}'
                        ],
                    ],
                    'tableOptions' => [
                        'class' => 'table'
                    ],
                    'layout' => "{items}\n{summary}\n{pager}"
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>

