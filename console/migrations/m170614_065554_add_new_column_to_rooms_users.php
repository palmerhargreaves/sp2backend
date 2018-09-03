<?php

use yii\db\Migration;

class m170614_065554_add_new_column_to_rooms_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room_users}}', 'in_room', self::boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%room_users}}', 'in_room');

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
