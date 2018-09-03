<?php

use yii\db\Migration;

class m170604_073803_new_table_user_friends extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_friends}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'friend_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_fiends}}');

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
