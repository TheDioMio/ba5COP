<?php

use yii\db\Migration;

class m260306_130949_add_notes_to_location extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%location}}', 'notes', $this->string(255)->null()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%location}}', 'notes');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260306_130949_add_notes_to_location cannot be reverted.\n";

        return false;
    }
    */
}
