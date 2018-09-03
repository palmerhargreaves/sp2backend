<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 06.07.2017
 * Time: 15:06
 */

namespace backend\components\Traits;


use common\models\user\User;
use common\models\user\UserHistory;
use common\models\user\UserReferralRegistration;

trait StatisticsTrait
{
    public function getTotalUsers() {
        return User::find()->count();
    }

    public function getTotalActiveUsers() {
        return User::find()->where(['status' => User::STATUS_ACTIVE])->count();
    }

    public function getTotalInactiveUsers() {
        return User::find()->where(['status' => User::STATUS_IN_WAIT])->count();
    }

    public function getTotalUsersWithHelpLabel() {
        return User::find()->where(['label_help' => true])->count();
    }

    public function getTotalUsersWithSpeedHelpLabel() {
        return User::find()->where(['label_speedhelp' => true])->count();
    }

    public function getTotalUsersRegisteredByReferral() {
        return UserReferralRegistration::find()->groupBy('user_referral_id')->count();
    }
}
