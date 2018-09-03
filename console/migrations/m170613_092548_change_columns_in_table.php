<?php

use yii\db\Migration;

class m170613_092548_change_columns_in_table extends Migration
{
    public function up()
    {
        $this->alterColumn('{{%user}}', 'cash', self::float(2));
        $this->alterColumn('{{%user}}', 'bonus_cash', self::float(2));
    }

    public function down()
    {
        echo "m170613_092548_change_columns_in_table cannot be reverted.\n";

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
