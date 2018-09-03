<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\Notify;
use frontend\models\SearchForm;
use yii\helpers\Html;

AppAsset::register($this);
$identity = Yii::$app->user->identity;

$model = new SearchForm();

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
</head>
<body class="">
<?php $this->beginBody() ?>

<!-- Start Page Loading -->
<div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>
<!-- End Page Loading -->

<?php echo $this->render('/site/partials/_header'); ?>
<!-- START MAIN -->
<div id="main">
    <!-- START WRAPPER -->
    <div class="wrapper">
        <?php echo $this->render('/site/partials/_sidebar_left'); ?>

        <!-- START CONTENT -->
        <section id="content">
            <!--start container-->
            <div class="container">
                <?php if (Yii::$app->session->hasFlash('success')): ?>

                    <div id="card-alert" class="card green">
                        <div class="card-content white-text">
                            <span class="card-title white-text darken-1"><i class="mdi-social-notifications"></i>Успешно.</span>
                            <p><?php echo Yii::$app->session->getFlash('success'); ?></p>
                        </div>
                        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <?php echo Yii::$app->session->getFlash('error'); ?>
                    </div>
                <?php endif; ?>


                <?php echo $this->render('/site/partials/_breadcrumbs'); ?>

                <?php echo $content; ?>
            </div>
        </section>

        <?php echo $this->render('/site/partials/_sidebar_right'); ?>
    </div>
</div>

<?php //echo $this->render('/site/partials/_footer'); ?>

<?php Notify::widget() ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
