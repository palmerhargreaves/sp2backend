<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 04.09.2018
 * Time: 15:28
 */

use yii\helpers\Url;

?>

<div class="col s12 m12 l12">
    <div class="card section-template-block section-template-block-<?php echo $section_template['section_template']->id; ?> <?php echo $section_template['section'] && $section_template['section']->status ? "white" : ""; ?>"
         style="<?php echo !($section_template['section'] && $section_template['section']->status) ? "background-color: #252525 !important" : ""; ?>">
        <div class="card-content">
            <span class="card-title"
                  style="<?php echo !($section_template['section'] && $section_template['section']->status) ? "color: white !important" : ""; ?>">
                <?php echo $section_template['section_template']->name; ?>
                <?php if ($section_template['section'] && $section_template['section']->status): ?>
                    <i class="js-load-block-settings-and-fields mdi-action-settings tooltipped right " <?php if ($section_template['section'] && $section_template['section']->status): ?>
                        data-position="top" data-delay="50" data-tooltip='Параметры'
                        data-url="<?php echo Url::to(['activity-statistic/load-block-data']); ?>"
                        data-section-id="<?php echo $section_template['section']->id; ?>" style="cursor: pointer;" <?php endif; ?> >
                    </i>
                <?php endif; ?>
            </span>

            <?php if ($section_template['section']): ?>
                <?php
                $fields_count = $section_template['section']->getFieldsCount();
                $calc_fields_count = $section_template['section']->getCalculatedFieldsCount()
                ?>

                <div class="row">
                    <div class="col s10">
                        <p id="section-fields-count-<?php echo $section_template['section']->id; ?>"
                           style="display: <?php echo $fields_count > 0 ? "block" : "none"; ?>"><?php echo sprintf('Полей: %d', $fields_count); ?></p>
                        <p id="section-calc-fields-count-<?php echo $section_template['section']->id; ?>"
                           style="display: <?php echo $calc_fields_count > 0 ? "block" : "none"; ?>"><?php echo sprintf('Вычисляемых полей: %d', $calc_fields_count); ?></p>
                    </div>
                </div>

            <?php endif; ?>
        </div>
        <div class="card-action">
            <div class="row">
                <div class="col s10">
                    <?php if ($section_template['section'] && $section_template['section']->status): ?>
                        <a href="#" data-url="<?php echo Url::to(['activity-statistic/activity-statistic-disable-block']); ?>"
                           style="display: <?php echo $section_template['section'] && $section_template['section']->status ? "block" : "none"; ?>"
                           data-section-template-id="<?php echo $section_template['section_template']->id; ?>"
                           data-activity-id="<?php echo $section_template['activity']->id; ?>"
                           data-section-id="<?php echo $section_template['section']->id; ?>"
                           class="js-disable-activity-static-block disable-block-<?php echo $section_template['section_template']->id; ?> red-text text-accent-1">
                            отключить блок
                        </a>
                    <?php endif; ?>

                    <a href="#" data-url="<?php echo Url::to(['activity-statistic/activity-statistic-activate-block']); ?>"
                       style="display: <?php echo !($section_template['section'] && $section_template['section']->status) ? "block" : "none"; ?>"
                       data-activity-id="<?php echo $section_template['activity']->id; ?>"
                       data-section-template-id="<?php echo $section_template['section_template']->id; ?>"
                       class="js-activate-activity-static-block activate-block-<?php echo $section_template['section_template']->id; ?>  green-text text-accent-1">
                        активировать блок
                    </a>
                </div>
                <div class="col s2">
                    <?php if ($section_template['section']->graph_type != "none"): ?>
                        <img style="width: 24px;" src="img/graphs/<?php echo $section_template['section']->getGraphTypeImg(); ?>" />
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
