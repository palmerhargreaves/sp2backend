<?php

use yii\db\Migration;

/**
 * Handles the creation of table `new`.
 */
class m170613_140523_create_new_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%site_bonuses}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'from_user_id' => $this->integer()->notNull(),
            'bonus_count' => $this->float()->notNull(),
            'created_at' => $this->timestamp()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('new');
    }
}
