<?php

use yii\db\Migration;

class m170618_102733_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%room_user_give_bonuses_to_user}}', 'is_used', self::boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%room_user_give_bonuses_to_user}}', 'is_used');

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
