<?php
/**
 * Created by PhpStorm.
 * User: kostig51
 * Date: 22.11.2017
 * Time: 21:04
 */

use common\models\activity\ActivityTypeCompanyImages;
use common\models\user\User;
use yii\helpers\Url;

?>

<li class="li-hover">
    <a href="#!" data-activates="chat-out" class="chat-close-collapse right"><i class="mdi-navigation-close"></i></a>
</li>

<li class="li-hover">
    <ul class="chat-collapsible" data-collapsible="expandable">
        <li>
            <div class="collapsible-header teal white-text "><i class="mdi-communication-contacts"></i>Кампании</div>
            <div class="collapsible-body recent-activity">
                <form>
                    <?php foreach (\common\models\activity\ActivityTypeCompany::find()->all() as $company): ?>
                        <?php $company_type_image = ActivityTypeCompanyImages::find()->where(['company_type_id' => $company->id, 'activity_id' => $activity->id])->one(); ?>
                        <div class="chat-out-list row">
                            <div class="col s9">
                                <input name="company-group" type="radio" id="company-<?php echo $company->id; ?>" <?php echo $company->id == $activity->type_company_id ? "checked" : ""; ?>>
                                <label style="font-weight: normal; color: black;" for="company-<?php echo $company->id; ?>"><?php echo $company->name; ?></label>

                                <?php if ($company_type_image): ?>
                                    <img style="max-width: 100px;" src="<?php echo Yii::$app->params['images_container_url']; ?>company_types/<?php echo $company_type_image->path; ?>" />
                                <?php endif; ?>
                            </div>

                            <div class="col s2">
                                <a href="#modal-activity-company-image" style="height: 2px;" class="modal-trigger js-show-activity-company-type-upload-image"
                                   data-position="left"
                                   data-delay="50"
                                   data-activity-id="<?php echo $activity->id; ?>"
                                   data-company-id="<?php echo $company->id; ?>"
                                   data-company-type-image-id="<?php echo !is_null($company_type_image) ? $company_type_image->id : 0; ?>"
                                   data-tooltip="<?php echo Yii::t('app', 'Параметры компании'); ?>"><i class="mdi-action-settings"></i></a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </li>

        <li>
            <div class="collapsible-header teal white-text "><i class="mdi-action-report-problem"></i>Обязательные заявки</div>
            <div class="collapsible-body">
                <form>
                    <?php foreach (\common\models\activity\ActivityModelsTypesNecessarily::find()->where(['activity_id' => $activity->id])->all() as $type_item): ?>
                        <div class="chat-out-list row">
                            <div class="col s9">
                                <?php echo $type_item->type->name; ?>
                            </div>

                            <div class="col s1">
                                <a href="#!" style="height: 2px;" class=""><i class="mdi-action-settings"></i></a>
                            </div>

                            <div class="col s1">
                                <a href="#!" style="height: 2px;" class=""><i class="mdi-content-remove"></i></a>
                            </div>

                            <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                <?php echo $type_item->task->name; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </li>

        <li>
            <div class="collapsible-header teal white-text "><i class="mdi-action-assignment"></i>Задачи</div>
            <div class="collapsible-body">
                <form>
                    <?php foreach (\common\models\activity\ActivityTask::find()->where(['activity_id' => $activity->id])->all() as $task): ?>
                        <div class="task-item-<?php echo $task->id; ?> chat-out-list row">
                            <div class="col s9">
                                <?php echo $task->name; ?>
                            </div>

                            <div class="col s2">
                                <a href="#!" style="height: 2px;" class=""><i class="mdi-content-remove"></i></a>
                            </div>

                            <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                <input name="task-<?php echo $task->id; ?>" type="checkbox" id="task-<?php echo $task->id; ?>" <?php echo $task->is_concept_complete ? "checked" : ""; ?>>
                                <label for="task-<?php echo $task->id; ?>">Выполнение концепции</label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </li>

        <li>
            <div class="collapsible-header teal white-text"><i class="mdi-action-stars"></i>Статистика</div>
            <div class="collapsible-body">
                <div class="chat-out-list row">
                    <form>
                        <?php foreach (\common\models\activity\ActivityVideoRecordsStatistics::find()->where(['activity_id' => $activity->id])->all() as $stat_item): ?>
                            <div class="task-item-<?php echo $task->id; ?> chat-out-list row">
                                <div class="col s9">
                                    <a href="<?php echo \yii\helpers\Url::to(["activity/config-simple-statistic-fields", 'id' => $activity->id]); ?>" style=""
                                       class="js-show-statistic-fields-config-modal"
                                       data-activity-id="<?php echo $activity->id; ?>" data-position="left" data-delay="50"
                                       data-tooltip="<?php echo Yii::t('app', 'Конфигурция полей / формул статистики'); ?>">
                                        <?php echo $stat_item->header; ?>
                                    </a>
                                </div>

                                <div class="col s1">
                                    <a href="#modal-config-statistic-settings"
                                        class="modal-trigger tooltipped js-show-statistic-config-modal"
                                        data-activity-id="<?php echo $activity->id; ?>"
                                        data-url="<?php echo \yii\helpers\Url::to(["activity/config-simple-statistic"]); ?>"
                                        data-content-container="modal-config-statistic-settings"
                                        data-position="top"
                                        data-delay="50"
                                        data-tooltip="<?php echo Yii::t('app', 'Параметры статистики'); ?>">
                                        <i class="mdi-action-settings"></i>
                                    </a>
                                </div>

                                <div class="col s1">
                                    <a href="#!" style="height: 2px;" class=""><i class="mdi-content-remove"></i></a>
                                </div>

                                <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                    <input name="activity-video-statistic-<?php echo $stat_item->id; ?>" type="checkbox" id="activity-video-statistic-<?php echo $stat_item->id; ?>" <?php echo $stat_item->status ? "checked" : ""; ?>>
                                    <label for="activity-video-statistic-<?php echo $stat_item->id; ?>">Активна</label>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </form>
                </div>

            </div>
        </li>

        <li>
            <div class="collapsible-header teal white-text"><i class="mdi-action-account-circle"></i>Дизайнеры</div>
            <div class="collapsible-body">
                <div class="chat-out-list row">
                    <form>
                        <?php foreach (User::find()->where(['group_id' => User::DESIGNER, 'active' => true])->all() as $item): ?>
                            <div class="specialist-item-<?php echo $item->id; ?> chat-out-list row">
                                <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                    <?php $checked = \common\models\activity\ActivitySpecialists::find()->where(['activity_id' => $activity->id, 'user_id' => $item->id])->count() > 0 ? "checked" : ""; ?>

                                    <input class="activity-specialist" name="activity-specialist-<?php echo $item->id; ?>"
                                           type="checkbox" id="activity-specialist-<?php echo $item->id; ?>" <?php echo $checked; ?>
                                           data-url="<?php echo \yii\helpers\Url::to(['/activity/bind-unbind-specialist']); ?>"
                                           data-activity-id="<?php echo $activity->id; ?>"
                                           data-user-id="<?php echo $item->id; ?>">
                                    <label for="activity-specialist-<?php echo $item->id; ?>"><?php echo $item->getFullName(); ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </form>
                </div>

            </div>
        </li>

        <li>
            <div class="collapsible-header teal white-text"><i class="mdi-av-repeat"></i>Согласование концепции</div>
            <div class="collapsible-body">
                <div class="chat-out-list row">
                    <form>
                        <div class="task-item-special-agreement chat-out-list row">
                            <div class="col s9">
                                Согласование регионал
                            </div>

                            <div class="col s1">
                                <a href="#modal-config-special-agreement"
                                   class="modal-trigger tooltipped js-show-special-agreement-config-modal"
                                   data-activity-id="<?php echo $activity->id; ?>"
                                   data-url="<?php echo \yii\helpers\Url::to(["activity/config-special-agreement"]); ?>"
                                   data-content-container="modal-config-special-agreement"
                                   data-position="top"
                                   data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Параметры согласования рег. менеджера'); ?>">
                                    <i class="mdi-action-settings"></i>
                                </a>
                            </div>

                            <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                <input name="activity-special-agreement"
                                    type="checkbox"
                                    id="activity-special-agreement" <?php echo $activity->allow_special_agreement ? "checked" : ""; ?>
                                    data-activity-id="<?php echo $activity->id; ?>"
                                    data-url="<?php echo Url::to(['activity/allow-deny-special-agreement']); ?>"
                                >
                                <label for="activity-special-agreement">Активна</label>
                            </div>
                        </div>

                        <div class="task-item-special-agreement chat-out-list row">
                            <div class="col s9">
                                Согласование импортер
                            </div>

                            <div class="col s1">
                                <a href="#modal-config-agreement-by-user"
                                   class="modal-trigger tooltipped js-show-agreement-by-user-config-modal"
                                   data-activity-id="<?php echo $activity->id; ?>"
                                   data-url="<?php echo \yii\helpers\Url::to(["activity/config-agreement-by-user"]); ?>"
                                   data-content-container="modal-config-agreement-by-user"
                                   data-position="top"
                                   data-delay="50"
                                   data-tooltip="<?php echo Yii::t('app', 'Параметры согласования импортера'); ?>">
                                    <i class="mdi-action-settings"></i>
                                </a>
                            </div>

                            <div class="col s12" style="font-size: 0.8rem; margin-top: 10px;">
                                <input name="activity-special-agreement"
                                       type="checkbox"
                                       id="activity-agreement-by-user" <?php echo $activity->allow_agreement_by_one_user ? "checked" : ""; ?>
                                       data-activity-id="<?php echo $activity->id; ?>"
                                       data-url="<?php echo Url::to(['activity/allow-deny-agreement-by-user']); ?>"
                                >
                                <label for="activity-agreement-by-user">Активна</label>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </li>


    </ul>
</li>
