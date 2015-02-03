<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150126_200132_add_attack_group_id_col*/
class m150126_200132_add_attack_group_id_col extends Migration
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
        $this->addColumn($this->tableName, 'group_id', Schema::TYPE_INTEGER.' UNSIGNED NOT NULL AFTER id');


    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'group_id');
    }
}
