<?php

use yii\db\Migration;

/**
 * Handles adding new to table `user`.
 */
class m170615_083359_add_new_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%user}}', 'enabled', self::boolean()->defaultValue(true));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%user}}', '');
    }
}
