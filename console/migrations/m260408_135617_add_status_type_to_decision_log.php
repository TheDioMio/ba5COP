<?php

use yii\db\Migration;

class m260408_135617_add_status_type_to_decision_log extends Migration
{
    public function safeUp()
    {
        // 1. Adicionar coluna
        $this->addColumn('{{%decision_log}}', 'status_type_id', $this->integer()->notNull());

        // 2. Criar índice
        $this->createIndex(
            'idx-decision_log-status_type_id',
            '{{%decision_log}}',
            'status_type_id'
        );

        // 3. FK
        $this->addForeignKey(
            'fk-decision_log-status_type_id',
            '{{%decision_log}}',
            'status_type_id',
            '{{%status_type}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // remover FK
        $this->dropForeignKey(
            'fk-decision_log-status_type_id',
            '{{%decision_log}}'
        );

        // remover índice
        $this->dropIndex(
            'idx-decision_log-status_type_id',
            '{{%decision_log}}'
        );

        // remover coluna
        $this->dropColumn('{{%decision_log}}', 'status_type_id');
    }
}
