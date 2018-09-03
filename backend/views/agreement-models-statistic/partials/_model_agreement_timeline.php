<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 14.01.2018
 * Time: 4:50
 */
?>

<div class="timeline-start">Начало</div>
<div class="conference-center-line"></div>
<div class="conference-timeline-content">
    <!-- Article -->

    <?php foreach ($timeline_result as $date => $items): ?>
        <div class="timeline-article">
            <div class="content-left-container">
                <?php foreach ($items[ 'left' ] as $left_item_data):  ?>
                    <?php $left_item = $left_item_data['item']; ?>
                    <div class="content-left">
                        <p>
                            <?php echo $left_item['description']; ?>
                            <span class="article-number"><?php echo sprintf('%0d', $left_item_data['position']); ?></span>
                        </p>
                    </div>
                    <span class="timeline-author"><?php echo $left_item['login']; ?></span>
                <?php endforeach; ?>
            </div>

            <div class="content-right-container">
                <?php foreach ($items[ 'right' ] as $right_item_data): ?>
                    <?php $right_item = $right_item_data['item']; ?>
                    <div class="content-right">
                        <p>
                            <?php echo $right_item['description']; ?>
                            <span class="article-number"><?php echo sprintf('%0d', $right_item_data['position']); ?></span>
                        </p>
                    </div>
                    <span class="timeline-author"><?php echo $right_item['login']; ?></span>
                <?php endforeach; ?>
            </div>

            <div class="meta-date">
                <span class="date"><?php echo date('d', strtotime($date)); ?></span>
                <span class="month"><?php echo mb_substr(\common\utils\D::$genetiveRusMonths[date('m', strtotime($date)) - 1], 0, 3); ?></span>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- // Article -->
</div>
<div class="timeline-end"><?php echo sprintf("период: %s д%s", $agreement_days, \common\utils\NumbersHelper::numberEnd($agreement_days, array( 'ень', 'ня', 'ней' ))); ?></div>