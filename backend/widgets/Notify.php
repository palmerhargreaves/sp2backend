<?php
namespace backend\widgets;

use Yii;

class Notify extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'bg-danger',
        'danger'  => 'bg-danger',
        'success' => 'bg-success',
        'info'    => 'bg-info',
        'warning' => 'bg-warning'
    ];

    public function init()
    {
        parent::init();

        $session = Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $class = $this->alertTypes[$type];
                $data = (array) $data;
                foreach ($data as $i => $message) {

                    $this->view->registerJs("
                    new PNotify({
                        text: '$message',
                        addclass: 'stack-bottom-right $class'
                    });
                    ");
                }

                $session->removeFlash($type);
            }
        }
    }
}
