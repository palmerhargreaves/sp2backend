<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

$activateLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activate-user-account', 'token' => $user->activation_key]);
?>
<div class="password-reset">
    <p>Привет <?= Html::encode($user->getFullName()) ?>,</p>
    <p>Для активации Вашей учетной записи пройдите по ссылке:</p>

    <p><?= Html::a(Html::encode($activateLink), $activateLink) ?></p>
</div>
