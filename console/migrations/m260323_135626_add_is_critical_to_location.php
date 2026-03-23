<?php

use yii\db\Migration;

class m260323_135626_add_is_critical_to_location extends Migration
{
    public function safeUp()
    {
        $this->addColumn('location', 'is_critical', $this->boolean()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->dropColumn('location', 'is_critical');
    }
}
