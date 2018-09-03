<?php

use yii\db\Migration;

class m170607_064108_add_new_table_rooms extends Migration
{
    public function up()
    {
        $this->createTable('{{%rooms}}', [
            'id' => $this->primaryKey(),
            'status' => $this->boolean()->defaultValue(true),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $this->createTable('{{%room_users}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'last_user_activity_in_room' => $this->timestamp()
        ]);

        $this->createTable('{{%room_users_messages}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->timestamp()
        ]);

        $this->createTable('{{%logs}}', [
            'id' => $this->primaryKey(),
            'object_id' => $this->integer()->notNull(),
            'action' => $this->string(80)->notNull(),
            'message' => $this->text()->notNull(),
            'data' => $this->string(255),
            'created_at' => $this->timestamp()
        ]);


    }

    public function down()
    {
        $this->dropTable('{{%rooms}}');
        $this->dropTable('{{%room_users}}');
        $this->dropTable('{{%room_users_messages}}');
        $this->dropTable('{{%logs}}');

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
