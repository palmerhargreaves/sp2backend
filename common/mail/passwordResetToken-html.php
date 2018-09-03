<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Привет <?= Html::encode($user->username) ?>,</p>

    <p>Перейдите по ссылке для получения нового пароля:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
