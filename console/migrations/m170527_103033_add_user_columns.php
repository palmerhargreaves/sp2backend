<?php

use yii\db\Migration;

class m170527_103033_add_user_columns extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'firstname', self::string(255)->null()->defaultValue(''));
        $this->addColumn('{{%user}}', 'surname', self::string(255)->null()->defaultValue(''));

        $this->addColumn('{{%user}}', 'cash', self::integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%user}}', 'bonus_cash', self::integer()->notNull()->defaultValue(0));

    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'firstname');
        $this->dropColumn('{{%user}}', 'surname');


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
