<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.12.2017
 * Time: 17:08
 */
use yii\helpers\Url;

?>

<div class="card">
    <div class="row">
        <div class="col s12 m12 l12">
            <table id="mainTable" class="table-responsive">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Название</th>
                </tr>
                </thead>
                <tbody class="">

                <?php foreach ($activities as $activity): ?>
                    <tr class="sortable-list-items slot-activity-<?php $activity->id; ?>"
                        data-id="<?php echo $activity->id; ?>"
                        data-slot-id="<?php echo $slot->id; ?>">

                        <td style="width: 5%;">
                            <?php echo $activity->id; ?>
                        </td>
                        <td style="">
                            <?php echo $activity->name; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <div class="divider"></div>
            <table>
                <tr>
                    <td colspan="5">
                        <ul id="projects-collection" class="collection">
                            <li class="collection-item">
                                <div class="row">
                                    <div class="col s6">
                                        <p class="collections-title"></p>
                                        <p class="collections-content"></p>
                                    </div>
                                    <div class="col s3">
                                        <span class="task-cat cyan"><?php echo sprintf('%s: %d', Yii::t('app', 'Всего активностей'), count($activities)); ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
