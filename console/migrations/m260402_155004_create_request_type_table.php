<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request_type}}`.
 */
class m260402_155004_create_request_type_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci';
        }

        $this->createTable('{{%request_type}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(50)->notNull()->unique(),
        ], $tableOptions);

        $this->batchInsert('{{%request_type}}', ['description'], [
            ['MEAL'],
            ['BATH'],
            ['BED'],
            ['MACHINE_HOURS'],
            ['TEAM_HOURS'],
            ['LOGISTIC_SUPPORT'],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%request_type}}');
    }
}
