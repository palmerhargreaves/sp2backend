<?php

use yii\db\Migration;

class m170606_124541_add_new_user_column_image extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'image', self::string(255)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'image');

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
