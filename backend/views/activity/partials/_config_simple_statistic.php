<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 28.11.2017
 * Time: 16:29
 */
use common\models\activity\ActivityStatisticPreCheckUsers;
use common\models\user\User;
use yii\helpers\Url;

?>
<h4 class="header">Параметры статистики</h4>
<a id="js-save-activity-statistic-config"
   style="top: -50px;" class="btn-floating activator btn-move-up waves-effect waves-light green darken-2 right "
   class="tooltipped"
   data-url="<?php echo \yii\helpers\Url::to(['activity/save-statistic-config']); ?>"
   data-position="top"
   data-delay="50"
   data-tooltip="<?php echo Yii::t('app', 'Сохранить параметры статистики'); ?>"
   data-id="<?php echo $activity->id; ?>">
    <i class="mdi-content-save"></i>
</a>

<div class="card-panel" style="padding-top: 3px;">
    <div class="row">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Управление параметрами статистики</h6>
            </li>

            <li class="collection-item dismissable">
                <input class="checkbox form-config-field-<?php echo $activity->id; ?>" type="checkbox"
                       id="ch-not-using-importer"
                    <?php echo $activity->getActivityVideoStatistic()->not_using_importer ? "checked" : ""; ?>
                       data-id="<?php echo $activity->id; ?>" data-field="not_using_importer">
                <label for="ch-not-using-importer" style="text-decoration: none;">Статистика не отправляется
                    импортеру</label>
            </li>

            <li class="collection-item dismissable">
                <div class="card light-blue lighten-5">
                    <div class="card-content light-blue-text">
                        <p>Статистика считаеться выполненной после проверки администратором или импортером.</p>
                    </div>
                </div>

                <input class="checkbox form-config-field-<?php echo $activity->id; ?>" type="checkbox"
                       id="ch-pre-check-statistic"
                    <?php echo $activity->getActivityVideoStatistic()->allow_statistic_pre_check ? "checked" : ""; ?>
                       data-id="<?php echo $activity->id; ?>" data-field="allow_statistic_pre_check">
                <label for="ch-pre-check-statistic" style="text-decoration: none;">Согласование статистики</label>

                <div class="row" style="margin-top: 20px;">
                    <div class="col s10 m10 l10">
                        <p>Пользователи проверяющие статистику</p>
                        <div class="card-reveal">
                            <table class="responsive-table" data-url="<?php echo Url::to(['activity/pre-check-user-statistic']); ?>">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Пользователь</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (User::find()->where(['in', 'group_id', [User::ADMIN_GROUP, User::IMPORTER_GROUP]])->orderBy(['id' => SORT_ASC])->all() as $user): ?>
                                <tr>
                                    <td>
                                        <input class="checkbox js-pre-check-user-item" type="checkbox"
                                               id="ch-pre-check-user-statistic-<?php echo $user->id; ?>"
                                            <?php echo ActivityStatisticPreCheckUsers::find()->where(['user_id' => $user->id, 'activity_id' => $activity->id])->count() ? "checked" : ""; ?>
                                               data-id="<?php echo $activity->id; ?>" data-user-id="<?php echo $user->id; ?>" data-activity-id="<?php echo $activity->id; ?>">
                                        <label for="ch-pre-check-user-statistic-<?php echo $user->id; ?>" style="text-decoration: none;">&nbsp;</label>
                                    </td>
                                    <td><?php echo $user->getFullName(); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>
