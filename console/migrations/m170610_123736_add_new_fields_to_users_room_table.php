<?php

use yii\db\Migration;

class m170610_123736_add_new_fields_to_users_room_table extends Migration
{
    public function up()
    {
        $this->createTable('room_user_give_bonuses_to_user', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'from_user_id' => $this->integer()->notNull(),
            'to_user_id' => $this->integer()->notNull(),
            'bonus_type' => $this->string(),
            'bonus_count' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()
        ]);

        $this->createTable('room_user_give_bonuses_to_room', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'bonus_type' => $this->string(),
            'created_at' => $this->timestamp()
        ]);

        $this->addColumn("{{%room_users}}", 'show_users_in_room', self::boolean()->defaultValue(false));
        $this->addColumn("{{%room_users}}", 'show_users_in_room_time', self::timestamp());
        $this->addColumn('{{%room_users}}', 'user_position', self::integer()->defaultValue(0));
    }

    public function down()
    {
        $this->dropTable('{{%room_user_give_bonuses}}');
        $this->dropTable('{{%room_user_give_bonuses_to_room}}');

        $this->dropColumn("{{%room_users}}", "show_users_in_room");
        $this->dropColumn("{{%room_users}}", "show_users_in_room_time");
        $this->dropColumn("{{%room_users}}", "user_position");

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
