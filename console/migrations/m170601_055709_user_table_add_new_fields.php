<?php

use yii\db\Migration;

class m170601_055709_user_table_add_new_fields extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'description', self::text()->null());
        $this->addColumn('{{%user}}', 'vk_url', self::string(255)->null());
        $this->addColumn('{{%user}}', 'face_url', self::string(255)->null());
        $this->addColumn('{{%user}}', 'classmates_url', self::string(255)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'description');
        $this->dropColumn('{{%user}}', 'vk_url');
        $this->dropColumn('{{%user}}', 'face_url');
        $this->dropColumn('{{%user}}', 'classmates_url');

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
