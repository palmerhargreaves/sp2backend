<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.03.2018
 * Time: 10:42
 */
use common\models\activity\ActivitySpecialAgreementUsersList;
use common\models\user\User;
use yii\helpers\Url;

?>
<h4 class="header">Параметры согласования</h4>

<div class="card-panel" style="padding-top: 3px;">
    <div class="row">
        <ul id="task-card" class="collection with-header">
            <li class="collection-header blue-grey">
                <h6 class="task-card-title">Управление параметрами согласования</h6>
            </li>

            <li class="collection-item dismissable">
                <div class="row" style="margin-top: 20px;">
                    <div class="col s10 m10 l10">
                        <p>Пользователи (рег. менеджер) проверяющий концепции</p>
                        <div class="card-reveal">
                            <table class="responsive-table" data-url="<?php echo Url::to(['activity/special-agreement-user']); ?>">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Пользователь</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach (User::find()->where(['in', 'group_id', [User::REG_MANAGER_GROUP]])->orderBy(['id' => SORT_ASC])->all() as $user): ?>
                                    <tr>
                                        <td>
                                            <input class="checkbox js-special-agreement-user-item" type="checkbox"
                                                   id="ch-special-agreement-user-<?php echo $user->id; ?>"
                                                <?php echo ActivitySpecialAgreementUsersList::find()->where(['user_id' => $user->id, 'activity_id' => $activity->id])->count() ? "checked" : ""; ?>
                                                   data-id="<?php echo $activity->id; ?>" data-user-id="<?php echo $user->id; ?>" data-activity-id="<?php echo $activity->id; ?>">
                                            <label for="ch-special-agreement-user-<?php echo $user->id; ?>" style="text-decoration: none;">&nbsp;</label>
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
