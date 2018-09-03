<?php

use yii\db\Migration;

class m170527_110215_add_user_bonus_cash_column_change extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'bonus_cash', self::float(2));
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
