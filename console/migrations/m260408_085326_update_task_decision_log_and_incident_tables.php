<?php

use yii\db\Migration;

class m260408_085326_update_task_decision_log_and_incident_tables extends Migration
{
    public function safeUp()
    {
        // TASK
        $this->addColumn(
            '{{%task}}',
            'block_reason',
            $this->string(120)->null()->after('status_type_id')
        );

        // DECISION LOG
        $this->addColumn(
            '{{%decision_log}}',
            'impact',
            $this->string(50)->null()->after('reason')
        );

        $this->alterColumn(
            '{{%decision_log}}',
            'reason',
            $this->string(50)->null()
        );

        // INCIDENT
        $this->addColumn(
            '{{%incident}}',
            'mitigate_by',
            $this->dateTime()->null()->after('status_type_id')
        );
    }

    public function safeDown()
    {
        // INCIDENT
        $this->dropColumn('{{%incident}}', 'mitigate_by');

        // DECISION LOG
        $this->alterColumn(
            '{{%decision_log}}',
            'reason',
            $this->string(30)->null()
        );

        $this->dropColumn('{{%decision_log}}', 'impact');

        // TASK
        $this->dropColumn('{{%task}}', 'block_reason');
    }
}
