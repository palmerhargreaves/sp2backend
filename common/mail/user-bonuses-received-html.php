<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\user\User */

?>
<div class="password-reset">
    <p>Привет <?= Html::encode($user->getFullName()) ?>,</p>
    <p>Вы получили <?php echo $bonuses; ?> бонусов от пользователя <?php echo $from_user->getFullname(); ?></p>
</div>
