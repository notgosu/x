<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150126_200539_add_attack_group_id_fk*/
class m150126_200539_add_attack_group_id_fk extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%attack}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addForeignKey(
            'fk_attack_group_id_to_attack_group_table',
            $this->tableName,
            'group_id',
            '{{%attack_group}}',
            'id',
            'RESTRICT'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropForeignKey('fk_attack_group_id_to_attack_group_table', $this->tableName);
    }
}
