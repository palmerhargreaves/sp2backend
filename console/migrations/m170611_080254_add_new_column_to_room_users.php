<?php

use yii\db\Migration;

class m170611_080254_add_new_column_to_room_users extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room_users}}', 'bonus_in_reserve', self::integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%room_users}}', 'bonus_in_reserve');

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
