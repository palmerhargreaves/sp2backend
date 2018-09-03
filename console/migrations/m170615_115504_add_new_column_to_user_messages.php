<?php

use yii\db\Migration;

class m170615_115504_add_new_column_to_user_messages extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_messages}}', 'has_read', self::boolean()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('{{%user_messages}}', 'has_read');

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
