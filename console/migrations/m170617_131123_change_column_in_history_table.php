<?php

use yii\db\Migration;

class m170617_131123_change_column_in_history_table extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%user_history}}', 'date', 'data');
    }

    public function down()
    {


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
