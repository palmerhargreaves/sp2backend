<?php

use yii\db\Migration;

class m170604_094629_new_table_user_votes extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_votes}}', [
            'id' => $this->primaryKey(),
            'from_user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_votes}}');

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
