<?php

use yii\db\Migration;

class m170604_104340_change_user_table_add_two_label_columns extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'label_speedhelp', self::boolean()->defaultValue(false)->null());
        $this->addColumn('{{%user}}', 'label_help', self::boolean()->defaultValue(false)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'label_speedhelp');
        $this->dropColumn('{{%user}}', 'label_help');

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
