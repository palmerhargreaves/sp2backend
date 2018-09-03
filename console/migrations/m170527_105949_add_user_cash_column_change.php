<?php

use yii\db\Migration;

class m170527_105949_add_user_cash_column_change extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'cash', self::float(2));
    }

    public function down()
    {
        //$this->alertColumn();

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
