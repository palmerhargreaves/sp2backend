<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles array */

$this->title = Yii::t('app', 'Новый пользователь');
?>
<!-- page title -->
<div class="page-title">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="page-subtitle">
        <p>Новый пользователь</p>
    </div>

    <ul class="breadcrumb">
        <li><?= Html::a('<i class="icon-home4 position-left"></i><span>' . Yii::t('app', 'Панель управления') . '</span>', ['/'], []) ?></li>
        <li class="active"><?= Html::encode($this->title) ?></li>
    </ul>
</div>
<!-- ./page title -->


<div class="wrapper">
    <div class="row row-wider">
        <?= $this->render('_form', [
            'model' => $model,
            'roles' => $roles
        ]) ?>
    </div>
</div>
