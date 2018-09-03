<?php

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

$activateLink = Yii::$app->urlManager->createAbsoluteUrl(['user/activate-user-account', 'token' => $user->activation_key]);
?>
Привет <?= $user->getFullName() ?>,

Вы получили <?php echo $bonuses; ?> бонусов от пользователя <?php echo $from_user->getFullname(); ?>

<?= $activateLink ?>
