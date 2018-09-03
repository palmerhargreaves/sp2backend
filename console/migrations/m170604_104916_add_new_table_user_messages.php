<?php

use yii\db\Migration;

class m170604_104916_add_new_table_user_messages extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_messages}}', [
            'id' => $this->primaryKey(),
            'message' => $this->text(),
            'from_user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_messages}}');

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
