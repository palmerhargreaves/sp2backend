<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 12.06.2018
 * Time: 10:16
 */
?>
<!--card stats start-->
<div id="card-stats">
    <div class="row">
        <h4>Статус блокировки</h4>
        <p>Процесс блокировки заявок</p>

        <div class="divider"></div>

        <div class="col s12 m6 l4">
            <div class="card">
                <div class="card-content green lighten-3 white-text">
                    <p class="card-stats-title"><i class="mdi-navigation-arrow-drop-down"></i>10 дней</p>
                    <h4 class="card-stats-number"><?php echo \common\models\activity\Activity::find()->where([ 'finished' => false ])->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>

            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="card">
                <div class="card-content green lighten-1 white-text">
                    <p class="card-stats-title"><i class="mdi-navigation-arrow-drop-down"></i>2 дня</p>
                    <h4 class="card-stats-number"><?php echo \common\models\activity\Activity::find()->where([ 'finished' => true ])->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>

            </div>
        </div>
        <div class="col s12 m6 l4">
            <div class="card">
                <div class="card-content red white-text">
                    <p class="card-stats-title"><i class="mdi-action-lock"></i>Заблокировано</p>
                    <h4 class="card-stats-number">
                        <?php
                        echo \common\models\activity\Activity::find()->count(); ?></h4>
                    <p class="card-stats-compare">
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
<!--card stats end-->

<!--card widgets start-->
<div id="card-widgets">

    <!--work collections start-->
    <div id="work-collections">
        <div class="row">
            <div class="col s12 m12 l12">
                <ul id="projects-collection" class="collection">
                    <li class="collection-item avatar">
                        <i class="mdi-file-folder circle light-blue darken-2"></i>
                        <span class="collection-header">Процесс блокировки заявки</span>
                        <p>Список заявок в процессе блокировки</p>
                        <a href="<?php echo ''; ?>" class="secondary-content"><i
                                class="mdi-action-list"></i></a>
                    </li>

                    <?php foreach (\common\models\activity\Activity::find()->where([ 'finished' => 0 ])->orderBy([ 'position' => SORT_ASC ])->all() as $activity): ?>
                        <li class="collection-item">
                            <div class="row">
                                <div class="col s1">
                                    <?php echo $activity->id; ?>
                                </div>
                                <div class="col s4">
                                    <p class="collections-title"><a
                                            href="<?php echo \yii\helpers\Url::to([ "activity/info", "id" => $activity->id ]); ?>"><?php echo $activity->name; ?></a>
                                    </p>
                                    <p style="font-size: 0.8rem;"
                                       class="collections-content"><?php echo sprintf('%s - %s', $activity->start_date, $activity->end_date); ?></p>
                                </div>
                                <div class="col s1">
                                    <span class="task-cat <?php echo $activity->hide ? "cyan" : "grey"; ?>"><?php echo sprintf("скрыта: %s", $activity->hide ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat <?php echo $activity->mandatory_activity ? "green" : "grey"; ?>"><?php echo sprintf("обязательная: %s", $activity->mandatory_activity ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat <?php echo $activity->is_limit_run ? "red" : "grey"; ?>"><?php echo sprintf("вып.только раз: %s", $activity->is_limit_run ? "Да" : "Нет"); ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="task-cat blue-grey"><?php echo sprintf("%s", $activity->company->name); ?></span>
                                </div>

                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>
</div>

