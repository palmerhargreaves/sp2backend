<?php

use yii\db\Migration;

class m170617_124743_add_new_table_history_of_operation extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'header' => $this->string(255)->notNull(),
            'msg' => $this->text(),
            'type' => $this->string(80)->notNull(),
            'date' => $this->string(255),
            'created_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_history}}');

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
