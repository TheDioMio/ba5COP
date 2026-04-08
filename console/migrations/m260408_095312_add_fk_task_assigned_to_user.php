<?php

use yii\db\Migration;

class m260408_095312_add_fk_task_assigned_to_user extends Migration
{
    public function safeUp()
    {
        $this->createIndex(
            'idx-task-assigned_to',
            '{{%task}}',
            'assigned_to'
        );

        $this->addForeignKey(
            'fk-task-assigned_to-user-id',
            '{{%task}}',
            'assigned_to',
            '{{%user}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-task-assigned_to-user-id',
            '{{%task}}'
        );

        $this->dropIndex(
            'idx-task-assigned_to',
            '{{%task}}'
        );
    }
}
