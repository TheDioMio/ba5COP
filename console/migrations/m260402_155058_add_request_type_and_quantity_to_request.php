<?php

use yii\db\Migration;

class m260402_155058_add_request_type_and_quantity_to_request extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            'fk-request-request_type_id',
            '{{%request}}',
            'request_type_id',
            '{{%request_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-request-request_type_id',
            '{{%request}}'
        );
    }
}
