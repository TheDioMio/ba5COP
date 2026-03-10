<?php

use yii\db\Migration;

class m260310_143311_turn_checkoutat_null_to_lodging_entry_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('lodging_entry', 'checkout_at', $this->dateTime()->null());
    }

    public function safeDown()
    {
        $this->alterColumn('lodging_entry', 'checkout_at', $this->dateTime()->notNull()
        );
    }
}
