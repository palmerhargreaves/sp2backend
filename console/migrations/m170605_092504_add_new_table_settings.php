<?php

use yii\db\Migration;

class m170605_092504_add_new_table_settings extends Migration
{
    public function up()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'field' => $this->string(80)->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');

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
