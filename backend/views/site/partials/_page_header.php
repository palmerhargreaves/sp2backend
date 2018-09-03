<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 11:55
 */
?>

<?php if (Yii::$app->session->get('breadcrumb')): ?>
    <div class="row">
        <nav>
            <div class="nav-wrapper">
                <div class="col s12">
                    <?php foreach (Yii::$app->session->get('breadcrumb') as $url => $label): ?>
                        <?php if (!empty($url)): ?>
                            <a href="<?php echo $url; ?>" class="breadcrumb"><?php echo $label; ?></a>
                        <?php else: ?>
                            <a href="#1" class="breadcrumb"><?php echo $label; ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </nav>
    </div>
<?php endif; ?>


<div class="row">
    <div class="col s12">
        <div class="page-header">
            <h1>
                <i class="material-icons"><?php echo Yii::$app->session->get('page_icon'); ?></i> <?php echo Yii::$app->session->get('page_header'); ?>
            </h1>

            <p><?php echo Yii::$app->session->get('page_description'); ?></p>
        </div>
    </div>
</div>


