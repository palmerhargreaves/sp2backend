<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use backend\assets\AppAsset;
use backend\widgets\Notify;

$bundle = AppAsset::register($this);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

    <link href="css/page-center.css" type="text/css" rel="stylesheet" media="screen,projection">
</head>

<body class="grey">
<?php $this->beginBody() ?>

<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

<!-- End Page Loading -->
<div id="login-page" class="row">
    <?php echo $content; ?>
</div>

<?php Notify::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
