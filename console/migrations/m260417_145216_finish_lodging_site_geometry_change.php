<?php

use yii\db\Migration;

class m260417_145216_finish_lodging_site_geometry_change extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk_lodging_location', '{{%lodging_site}}');
        $this->dropIndex('fk_lodging_location', '{{%lodging_site}}');

        $this->dropColumn('{{%lodging_site}}', 'location_id');

        $this->addColumn('{{%lodging_site}}', 'geometry', $this->text()->null());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%lodging_site}}', 'geometry');

        $this->addColumn('{{%lodging_site}}', 'location_id', $this->integer()->null());

        $this->createIndex(
            'fk_lodging_location',
            '{{%lodging_site}}',
            'location_id'
        );

        $this->addForeignKey(
            'fk_lodging_location',
            '{{%lodging_site}}',
            'location_id',
            '{{%location}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }
}
