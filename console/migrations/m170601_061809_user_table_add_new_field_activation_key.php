<?php

use yii\db\Migration;

class m170601_061809_user_table_add_new_field_activation_key extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'activation_key', self::string(255)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'activation_key');

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
