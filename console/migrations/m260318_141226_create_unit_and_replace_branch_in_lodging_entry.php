<?php

use yii\db\Migration;
use yii\db\Query;

class m260318_141226_create_unit_and_replace_branch_in_lodging_entry extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // 1) Criar tabela unit
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex(
            'idx-unit-branch_id',
            '{{%unit}}',
            'branch_id'
        );

        $this->addForeignKey(
            'fk-unit-branch_id',
            '{{%unit}}',
            'branch_id',
            '{{%branch}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // 2) Adicionar unit_id à lodging_entry (sem FK ainda)
        $this->addColumn(
            '{{%lodging_entry}}',
            'unit_id',
            $this->integer()->null()
        );

        // 3) Criar uma unidade genérica por cada branch
        $branches = (new Query())
            ->from('{{%branch}}')
            ->all();

        foreach ($branches as $branch) {
            $this->insert('{{%unit}}', [
                'name' => 'Unidade Genérica - ' . $branch['description'],
                'branch_id' => $branch['id'],
            ]);
        }

        // 4) Migrar os dados de branch_id -> unit_id
        $entries = (new Query())
            ->from('{{%lodging_entry}}')
            ->all();

        foreach ($entries as $entry) {
            $unit = (new Query())
                ->from('{{%unit}}')
                ->where(['branch_id' => $entry['branch_id']])
                ->one();

            if ($unit) {
                $this->update(
                    '{{%lodging_entry}}',
                    ['unit_id' => $unit['id']],
                    ['id' => $entry['id']]
                );
            }
        }

        // 5) Tornar unit_id obrigatório
        $this->alterColumn(
            '{{%lodging_entry}}',
            'unit_id',
            $this->integer()->notNull()
        );

        // 6) Só agora criar índice e FK de unit_id
        $this->createIndex(
            'idx-lodging_entry-unit_id',
            '{{%lodging_entry}}',
            'unit_id'
        );

        $this->addForeignKey(
            'fk-lodging_entry-unit_id',
            '{{%lodging_entry}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // 7) Remover FK antiga de branch_id
        $this->dropForeignKey(
            'fk_lodging_entry_branch',
            '{{%lodging_entry}}'
        );

        // 8) Remover índice antigo de branch_id
        $this->dropIndex(
            'fk_lodging_entry_branch',
            '{{%lodging_entry}}'
        );

        // 9) Remover coluna branch_id
        $this->dropColumn('{{%lodging_entry}}', 'branch_id');
    }

    public function safeDown()
    {
        // 1) Voltar a criar branch_id
        $this->addColumn(
            '{{%lodging_entry}}',
            'branch_id',
            $this->integer()->null()
        );

        $entries = (new Query())
            ->from('{{%lodging_entry}}')
            ->all();

        foreach ($entries as $entry) {
            $unit = (new Query())
                ->from('{{%unit}}')
                ->where(['id' => $entry['unit_id']])
                ->one();

            if ($unit) {
                $this->update(
                    '{{%lodging_entry}}',
                    ['branch_id' => $unit['branch_id']],
                    ['id' => $entry['id']]
                );
            }
        }

        $this->alterColumn(
            '{{%lodging_entry}}',
            'branch_id',
            $this->integer()->notNull()
        );

        $this->createIndex(
            'fk_lodging_entry_branch',
            '{{%lodging_entry}}',
            'branch_id'
        );

        $this->addForeignKey(
            'fk_lodging_entry_branch',
            '{{%lodging_entry}}',
            'branch_id',
            '{{%branch}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        // 2) Remover FK/index de unit_id
        $this->dropForeignKey(
            'fk-lodging_entry-unit_id',
            '{{%lodging_entry}}'
        );

        $this->dropIndex(
            'idx-lodging_entry-unit_id',
            '{{%lodging_entry}}'
        );

        // 3) Remover unit_id
        $this->dropColumn('{{%lodging_entry}}', 'unit_id');

        // 4) Remover unit
        $this->dropForeignKey(
            'fk-unit-branch_id',
            '{{%unit}}'
        );

        $this->dropIndex(
            'idx-unit-branch_id',
            '{{%unit}}'
        );

        $this->dropTable('{{%unit}}');
    }
}
