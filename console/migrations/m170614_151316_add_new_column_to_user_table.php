<?php

use yii\db\Migration;

/**
 * Handles adding new to table `user`.
 */
class m170614_151316_add_new_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%user}}', 'referral_link', self::string(255)->null());
        $this->addColumn('{{%user}}', 'referral_last_bonus_date', self::timestamp()->null());

        $this->createTable('{{%user_referral_registration}}', [
            'id' => $this->primaryKey(),
            'user_referral_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()
        ]);

        $this->createTable('{{%user_referral_bonuses}}', [
            'id' => $this->primaryKey(),
            'user_referral_id' => $this->integer()->notNull(),
            'user_from_id' => $this->integer()->notNull(),
            'new_user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%user}}', 'referral_link');
        $this->dropColumn('{{%user}}', 'referral_last_bonus_date');

        $this->dropTable('{{%user_referral_registration}}');
        $this->dropTable('{{%user_referral_bonuses}}');
    }
}
