<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model backend\models\UserForm */
/* @var $roles array */

$this->title = $model->getUser()->getFullName();
?>

<!-- page title -->
<div class="page-title">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="page-subtitle">
        <p>Редактирование параметров пользователя</p>
    </div>

    <ul class="breadcrumb">
        <li><?= Html::a('<i class="icon-home4 position-left"></i><span>' . Yii::t('app', 'Панель управления') . '</span>', ['/'], []) ?></li>
        <li><?= Html::a('<i class="icon-home4 position-left"></i><span>' . Yii::t('app', 'Пользователи') . '</span>', ['/user'], []) ?></li>
        <li class="active"><?= Html::encode($this->title) ?></li>
    </ul>
</div>
<!-- ./page title -->

<div class="wrapper">
    <div class="row row-wider">
        <?php $form = ActiveForm::begin(['id' => 'form', 'fieldConfig' => [
                'template' => '{input}{error}'
        ]]); ?>
        <div class="col-md-3">
            <div class="profile margin-bottom-0">
                <div class="profile-image">
                    <img src="<?php echo empty($user->image) ? "/images/user.png" : $user->image; ?>">
                </div>
                <div class="profile-info">
                    <h4><?php echo $user->getFullname(); ?></h4>
                </div>
            </div>

            <div class="list-group">

            </div>
        </div>

        <div class="col-md-9">
            <div class="page-subtitle margin-bottom-0">
                <h3>Параметры авторизации</h3>
                <p>Обязательные параметры</p>
            </div>
            <div class="form-group-one-unit margin-bottom-40">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-group-custom">
                            <label>Логин</label>
                            <?php echo $form->field($model, 'username')->textInput(['class' => 'form-control', 'disabled' => true]); ?>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group form-group-custom">
                            <label>Email адрес</label>
                            <?php echo $form->field($model, 'email')->textInput(['class' => 'form-control', 'disabled' => true]); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-custom">
                            <label>Новый пароль</label>
                            <?php echo $form->field($model, 'password')->textInput(['class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="page-subtitle margin-bottom-0">
                <h3>Персональная информация</h3>
                <p></p>
            </div>
            <div class="form-group-one-unit margin-bottom-40">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-custom">
                            <label>ФИО</label>
                            <?php echo $form->field($model, 'surname')->textInput(['class' => 'form-control']); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-custom">
                            <label>Телефон</label>
                            <?php echo $form->field($model, 'phone')
                                ->widget(MaskedInput::className(), [
                                    'mask' => Yii::$app->params['phonePattern'],
                                    'options' => [
                                        'class' => 'form-control'
                                    ]
                                ]); ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-custom">
                            <label>Описание</label>
                            <?php echo $form->field($model, 'description')->textarea(['class' => 'form-control']); ?>
                        </div>
                    </div>
                </div>

            </div>

            <div class="page-subtitle margin-bottom-0">
                <h3>Настройки профиля</h3>
                <p>Управление статусом пользователя</p>
            </div>
            <div class="form-group-one-unit margin-bottom-40">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group " style="padding: 10px;">
                            <label>Права</label>
                            <?= $form->field($model, 'role')->dropDownList($roles, ['class' => 'selectpicker from-control']) ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-group-custom">
                            <label>Аккаунт разрешен</label>
                            <?php echo $form->field($model, 'status', [
                                'template' => '<label class="switch">{input}<span></span></label>'
                            ])->checkbox([], false); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-danger pull-right">Сохранить</button>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
