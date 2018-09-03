<?php

use yii\db\Migration;

class m170601_092932_user_profile_settings_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%user_settings}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull()->defaultValue(0),
            'field_name' => $this->string(80)->notNull(),
            'field_value' => $this->string(255)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%user_settings');

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
