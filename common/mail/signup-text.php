<?php

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

$activateLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activate-user-account', 'token' => $user->activation_key]);
?>
Привет <?= $user->getFullName() ?>,

Для активации Вашей учетной записе пройдите по ссылке:

<?= $activateLink ?>
