<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 21:04
 */

use common\models\activity\ActivityStatisticPreCheckUsers;
use common\models\activity\ActivityStatisticsPeriods;
use common\models\user\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$activity->checkStatisticExists();
?>

<div class="card-panel">
    <h4 class="header2">Параметры активности</h4>

    <div class="row">
        <?php $form = ActiveForm::begin([ 'id' => 'form-activity-settings',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'action' => Url::to(['activity/settings']),
            'fieldConfig' => [
                'template' => '{input}{error}'
            ], 'options' => [ 'class' => 'col s12', ] ]); ?>
        <div class="row">
            <div class="input-field col s12">
                <?php echo $form->field($activity_model, 'company_target')->textarea([ 'placeholder' => 'Цель кампании', 'disabled' => false, 'value' => $activity->company_target ]); ?>
            </div>

            <div class="input-field col s12">
                <?php echo $form->field($activity_model, 'target_audience')->textarea([ 'placeholder' => 'Целевая аудитория', 'disabled' => false, 'value' => $activity->target_audience ]); ?>
            </div>

            <div class="input-field col s12">
                <?php echo $form->field($activity_model, 'company_mechanics')->textarea([ 'placeholder' => 'Механика кампании', 'disabled' => false, 'value' => $activity->company_mechanics ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="input-field col s12">
                    <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Сохранить
                        <i class="mdi-content-send right"></i>
                    </button>
                </div>
            </div>
        </div>

        <input type="hidden" name="id" value="<?php echo $activity->id; ?>" />

        <?php $form->end(); ?>
    </div>
</div>


<div class="card-panel" style="padding-top: 3px;">
    <div class="row">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Управление параметрами статистики</h6>
            </li>

            <li class="collection-item dismissable">
                <div class="card light-blue lighten-5">
                    <div class="card-content light-blue-text">
                        <p>Привязка статистики по кварталам:</p>
                    </div>
                </div>

                <?php for ($q = 1; $q <= 4; $q++): ?>
                    <input class="js-activity-statistic-quarter checkbox form-activity-statistic-q-<?php echo $q; ?>"
                           type="checkbox"
                           id="ch-activity-statistic-q-<?php echo $q; ?>"
                        <?php echo ActivityStatisticsPeriods::checkQuarter($activity->id, $q, date('Y', strtotime($activity->start_date))) ? "checked" : ""; ?>
                           data-id="<?php echo $activity->id; ?>"
                           data-quarter="<?php echo $q; ?>"
                           data-year="<?php echo date('Y', strtotime($activity->start_date)); ?>"
                           data-url="<?php echo Url::to(['activity-statistic/bind-activity-statistic-quarter']); ?>">
                    <label for="ch-activity-statistic-q-<?php echo $q; ?>"
                           style="text-decoration: none;"><?php echo sprintF('Квартал: %d', $q); ?></label>
                <?php endfor; ?>
            </li>

            <li class="collection-item dismissable">
                <input class="js-not-using-importer-config checkbox form-config-field-<?php echo $activity->id; ?>"
                       type="checkbox"
                       id="ch-not-using-importer"
                    <?php echo $activity->getActivityVideoStatistic() && $activity->getActivityVideoStatistic()->not_using_importer ? "checked" : ""; ?>
                       data-url="<?php echo \yii\helpers\Url::to(['activity/save-statistic-config']); ?>"
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

                <input class="js-pre-check-statistic-config checkbox form-config-field-<?php echo $activity->id; ?>"
                       type="checkbox"
                       id="ch-pre-check-statistic"
                    <?php echo $activity->getActivityVideoStatistic() && $activity->getActivityVideoStatistic()->allow_statistic_pre_check ? "checked" : ""; ?>
                       data-url="<?php echo \yii\helpers\Url::to(['activity/save-statistic-config']); ?>"
                       data-id="<?php echo $activity->id; ?>" data-field="allow_statistic_pre_check">
                <label for="ch-pre-check-statistic" style="text-decoration: none;">Согласование статистики</label>

                <div class="row" style="margin-top: 20px;">
                    <div class="col s10 m10 l10">
                        <p>Пользователи проверяющие статистику</p>
                        <div class="card-reveal">
                            <table class="responsive-table"
                                   data-url="<?php echo Url::to([ 'activity/pre-check-user-statistic' ]); ?>">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Пользователь</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (User::find()->where([ 'in', 'group_id', [ User::ADMIN_GROUP, User::IMPORTER_GROUP ] ])->orderBy([ 'id' => SORT_ASC ])->all() as $user): ?>
                                    <tr>
                                        <td>
                                            <input class="checkbox js-pre-check-user-item" type="checkbox"
                                                   id="ch-pre-check-user-statistic-<?php echo $user->id; ?>"
                                                <?php echo ActivityStatisticPreCheckUsers::find()->where([ 'user_id' => $user->id, 'activity_id' => $activity->id ])->count() ? "checked" : ""; ?>
                                                   data-id="<?php echo $activity->id; ?>"
                                                   data-user-id="<?php echo $user->id; ?>"
                                                   data-activity-id="<?php echo $activity->id; ?>">
                                            <label for="ch-pre-check-user-statistic-<?php echo $user->id; ?>"
                                                   style="text-decoration: none;">&nbsp;</label>
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
