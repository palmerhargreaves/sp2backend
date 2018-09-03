<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 15:50
 */
?>

<?php if (Yii::$app->session->get('breadcrumb')): ?>
<div class="container">
    <div class="row">
        <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title"><?php echo Yii::$app->session->get('page_title'); ?></h5>

            <ol class="breadcrumbs">
                <?php foreach (Yii::$app->session->get('breadcrumb') as $url => $label): ?>
                    <?php if (!empty($url)): ?>
                        <li><a href="<?php echo $url; ?>"><?php echo $label; ?></a></li>
                    <?php else: ?>
                        <li class="active"><?php echo $label; ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>
<?php endif;
