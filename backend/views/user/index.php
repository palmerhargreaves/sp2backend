<?php

use common\models\user\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $roles array */

$this->title = Yii::t('app', 'Пользователи');
?>

<!-- page title -->
<div class="page-title">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="page-subtitle">
        <p><?= Html::a('<i class="fa fa-user-plus"></i><span>' . Yii::t('app', 'Новый пользователь') . '</span>', ['create'], ['class' => "btn btn-link"]) ?></p>
    </div>

    <ul class="breadcrumb">
        <li><?= Html::a('<i class="icon-home4 position-left"></i><span>' . Yii::t('app', 'Панель управления') . '</span>', ['/'], []) ?></li>
        <li class="active"><?= Html::encode($this->title) ?></li>
    </ul>
</div>
<!-- ./page title -->

<?php echo $this->render('_items', ['roles' => $roles, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider]); ?>
