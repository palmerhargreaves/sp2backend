<?php

use yii\db\Migration;

class m170527_105549_add_user_role_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user%}}', 'role', self::integer()->defaultValue(99)->notNull());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'role');

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
