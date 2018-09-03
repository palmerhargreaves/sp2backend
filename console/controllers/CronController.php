<?php
/**
 * Created by PhpStorm.
 * User: kostet
 * Date: 07.06.2017
 * Time: 9:51
 */

namespace console\controllers;

use common\models\user\UserReferralBonuses;
use common\models\user\UserVotes;
use frontend\daemons\WebSocketRoomsServer;
use common\models\rooms\Rooms;
use yii\base\Controller;

class CronController extends Controller
{
    /**
     * Work with rooms
     */
    public function actionRooms() {
        Rooms::workWithRooms();
    }

    public function actionServerStart() {
        $server = new WebSocketRoomsServer();
        $server->start();
    }

    /**
     * Clear users votes by time
     */
    public function actionCleanVotes() {
        //Clean users votes by period
        UserVotes::cleanVotes();

        //Give users bonuses for their referrals
        UserReferralBonuses::giveUsersReferralBonuses();
    }
}
