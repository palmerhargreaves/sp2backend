<?php

use yii\db\Migration;

class m170609_060035_add_new_field_in_room_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room_users}}', 'hash', self::string(255));
    }

    public function down()
    {
        $this->dropColumn('{{%room_users}}');

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
