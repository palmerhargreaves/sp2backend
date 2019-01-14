<?php use common\utils\D; ?>

<?php
foreach ($quarters_list as $quarter): ?>
    <?php $months = D::getQuarterMonths($quarter); ?>
    <?php foreach ($months as $month): ?>
        <div class="input-field col s1">
            <input type="checkbox" class="months" id="month_<?php echo $month; ?>" data-month="<?php echo $month; ?>" data-month-quarter="<?php echo $quarter; ?>"/>
            <label for="month_<?php echo $month; ?>"><?php echo D::getMonthName($month); ?></label>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>