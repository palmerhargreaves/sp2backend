<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 16.11.2017
 * Time: 15:07
 */
use yii\helpers\Url;

$isActiveMenu = function($parent) {
    return $parent == Yii::$app->controller->getUniqueId();
};

?>

<!-- START LEFT SIDEBAR NAV-->
<aside id="left-sidebar-nav">
    <ul id="slide-out" class="side-nav fixed leftside-navigation">
        <li class="user-details cyan darken-2">
            <div class="row">
                <div class="col col s2 m2 l2">
                    <img src="img/profile.png" alt="" class="circle responsive-img valign profile-image">
                </div>
                <div class="col col s9 m9 20">
                    <ul id="profile-dropdown" class="dropdown-content">
                        <li><a href="#"><i class="mdi-action-face-unlock"></i> Профиль</a>
                        </li>
                        <!--<li><a href="#"><i class="mdi-action-settings"></i> Settings</a>
                        </li>
                        <li><a href="#"><i class="mdi-communication-live-help"></i> Help</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="mdi-action-lock-outline"></i> Lock</a>
                        </li>-->
                        <li><a href="<?php echo Url::to(['auth/logout']); ?>"><i class="mdi-hardware-keyboard-tab"></i> Выход</a>
                        </li>
                    </ul>
                    <a class="btn-flat dropdown-button waves-effect waves-light white-text profile-btn" href="#" data-activates="profile-dropdown"><?php echo Yii::$app->user->identity->getFullName(); ?><i class="mdi-navigation-arrow-drop-down right"></i></a>
                    <p class="user-roal">Administrator</p>
                </div>
            </div>
        </li>

        <li class="bold <?php echo $isActiveMenu('site') ? "active" : ""; ?>"><a href="<?php echo Url::to('/admin'); ?>" class="waves-effect waves-cyan"><i class="mdi-action-dashboard"></i><?php echo Yii::t('app', 'Главная'); ?></a></li>

        <li class="no-padding">
            <ul class="collapsible collapsible-accordion">
                <li class="bold <?php echo $isActiveMenu('activity') ? "active" : ""; ?>">
                    <a class="collapsible-header waves-effect waves-cyan <?php echo $isActiveMenu('activity') ? "active" : ""; ?>">
                        <i class="mdi-action-view-carousel"></i> Активности
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="<?php echo $isActiveMenu('activity') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/activity/list']); ?>">Все</a></li>
                            <li class="<?php echo $isActiveMenu('activity-service') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/activity-service']); ?>">Service Clinic</a></li>
                            <li class="<?php echo $isActiveMenu('mandatory-activities') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/mandatory-activities']); ?>">Обязательные</a></li>
                        </ul>
                    </div>
                </li>

                <li class="bold <?php echo $isActiveMenu('models') ? "active" : ""; ?>">
                    <a class="collapsible-header waves-effect waves-cyan <?php echo $isActiveMenu('models') ? "active" : ""; ?>">
                        <i class="mdi-action-view-carousel"></i> Заявки
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="<?php echo $isActiveMenu('activity') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/models/block-inform']); ?>">Блокировка</a></li>
                        </ul>
                    </div>
                </li>

                <li class="bold ">
                    <a class="collapsible-header waves-effect waves-cyan">
                        <i class="mdi-action-view-carousel"></i> Статистика
                    </a>
                    <div class="collapsible-body">
                        <ul>
                            <li class="<?php echo $isActiveMenu('agreement-models-statistic') ? "active" : "" ?>"><a href="<?php echo Url::to(['/agreement-models-statistic/verification-dates-period']); ?>">Срок проверки</a></li>
                            <li class="<?php echo $isActiveMenu('statistic-pre-check') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/activity/statistic-pre-check']); ?>">Согласование</a></li>

                        </ul>
                    </div>
                </li>
            </ul>
        </li>

        <li class="bold <?php echo $isActiveMenu('contacts') ? "active" : ""; ?>"><a href="<?php echo Url::to(['/contacts']); ?>" class="waves-effect waves-cyan"><i class="mdi-action-account-circle"></i><?php echo Yii::t('app', 'Контакты'); ?></a></li>

        <!--<li class="li-hover"><div class="divider"></div></li>
        <li class="li-hover"><p class="ultra-small margin more-text">Daily Sales</p></li>
        <li class="li-hover">
            <div class="row">
                <div class="col s12 m12 l12">
                    <div class="sample-chart-wrapper">
                        <div class="ct-chart ct-golden-section" id="ct2-chart"></div>
                    </div>
                </div>
            </div>
        </li>-->
    </ul>
    <a href="#" data-activates="slide-out" class="sidebar-collapse btn-floating btn-medium waves-effect waves-light hide-on-large-only cyan"><i class="mdi-navigation-menu"></i></a>
</aside>
<!-- END LEFT SIDEBAR NAV-->
