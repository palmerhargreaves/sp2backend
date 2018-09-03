<?php
namespace backend\components;

use Yii;
use yii\base\Component;
use yii\helpers\Url;
use common\models\User;
use common\models\Client;

class Notificator extends Component
{
    public function getNotifications()
    {
        $notifications = [];

        /*$query = Client::find()->where('remind_datetime >= NOW()');

        if(!Yii::$app->user->can(User::ROLE_ADMIN)) {
            $query->andWhere(['manager_id' => Yii::$app->user->getId()]);
        }

        $clients = $query->all();

        $notifications = [];

        foreach ($clients as $client) {
            $notification = new \StdClass();
            $notification->url = Url::to(['/client/update', 'id' => $client->id]);
            $notification->title = $client->company;
            $notification->count = count($client->notices);
            $notification->message = $client->company;
            $notification->date = $client->formattedRemindDatetime;
            $notifications[] = $notification;
        }*/

        return $notifications;
    }
}
