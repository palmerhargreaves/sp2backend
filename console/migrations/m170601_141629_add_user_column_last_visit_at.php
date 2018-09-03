<?php

use yii\db\Migration;

class m170601_141629_add_user_column_last_visit_at extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'last_visit_at', self::integer()->defaultValue(time()));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'last_visit_at');

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
