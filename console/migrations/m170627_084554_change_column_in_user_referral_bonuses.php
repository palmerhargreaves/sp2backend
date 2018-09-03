<?php

use yii\db\Migration;

class m170627_084554_change_column_in_user_referral_bonuses extends Migration
{
    public function up()
    {
        $this->renameColumn('{{%user_referral_bonuses}}', 'new_user_id', 'base_user_referral_id');
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
