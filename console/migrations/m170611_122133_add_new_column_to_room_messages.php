<?php

use yii\db\Migration;

class m170611_122133_add_new_column_to_room_messages extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room_users_messages}}', 'room_id', self::integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%room_users_messages}}', 'room_id');

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
