<?php

use yii\db\Migration;

/**
 * Handles adding ne to table `room_users`.
 */
class m170611_065957_add_ne_column_to_room_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%room_users}}', 'status', self::boolean()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%room_users}}', 'status');
    }
}
