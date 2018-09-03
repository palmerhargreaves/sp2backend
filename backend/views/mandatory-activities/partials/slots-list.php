<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 12.12.2017
 * Time: 5:42
 */
use yii\helpers\Url;

?>

<div id="card-widgets">
    <!--work collections start-->
    <div id="work-collections">
        <div class="row">
            <div class="col s12 m12 l12">
                <ul id="projects-collection" class="collection z-depth-1">
                    <li class="collection-item avatar">
                        <i class="mdi-action-tab circle light-blue darken-2"></i>
                        <span class="collection-header">Список слотов</span>
                        <p>Список привязанных слотов</p>
                    </li>
                </ul>

                <ul class="collapsible collapsible-accordion" data-collapsible="expandable">
                    <?php $slot_index = 1;
                    foreach ($slots as $slot): ?>
                        <?php $activities = $slot->activities; ?>
                        <li class="active slot-<?php echo $slot->id; ?>">
                            <div class="collapsible-header <?php echo count($activities) ? "active" : ""; ?>">
                                <?php echo sprintf('Слот №: %s', $slot_index++); ?>
                            </div>
                            <div class="collapsible-body"
                                 style="display: <?php echo count($activities) ? "block" : "none"; ?>">
                                <ul id="projects-collection" class="collection">
                                    <li class="collection-item avatar">
                                        <i class="mdi-image-navigate-next circle light-blue darken-2"></i>
                                        <span class="collection-header">Активности</span>
                                        <p style="padding: 0rem;">Список привязанных активностей</p>
                                        <a href="#modal-slot-config"
                                           class="secondary-content tooltipped-slots modal-trigger" style="right: 33px;"
                                           data-id="<?php echo $slot->id; ?>"
                                           data-url="<?php echo Url::to(['/mandatory-activities/config-slot']); ?>"
                                           data-delay="50"
                                           data-tooltip="<?php echo Yii::t('app', 'Настроить слот'); ?>"><i
                                                    class="mdi-action-settings"></i></a>

                                        <a href="#!" class="secondary-content tooltipped-slots slot-item-remove"
                                           data-id="<?php echo $slot->id; ?>"
                                           data-url="<?php echo Url::to(['/mandatory-activities/remove-slot']); ?>"
                                           data-delay="50"
                                           data-tooltip="<?php echo Yii::t('app', 'Удалить слот'); ?>"><i
                                                    class="mdi-content-remove"></i></a>
                                    </li>
                                </ul>

                                <div id="container-slot-activities-list-<?php echo $slot->id; ?>">
                                    <?php echo $this->render('_slot-activities', ['activities' => $activities, 'slot' => $slot]); ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (count($slots) == 0): ?>
                    <div id="card-alert" class="card red lighten-5">
                        <div class="card-content red-text">
                            <p>Нет привязанных слотов</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

