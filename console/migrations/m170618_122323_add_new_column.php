<?php

use yii\db\Migration;

class m170618_122323_add_new_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user_votes}}', 'status', self::boolean()->defaultValue(true));
    }

    public function down()
    {
        $this->dropColumn('{{%user_votes}}', 'status');

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
