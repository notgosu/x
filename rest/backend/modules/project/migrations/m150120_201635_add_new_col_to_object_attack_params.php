<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150120_201635_add_new_col_to_object_attack_params*/
class m150120_201635_add_new_col_to_object_attack_params extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%object_attack_params}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->addColumn($this->tableName, 'start_value', Schema::TYPE_DECIMAL.'(12, 8) NOT NULL DEFAULT 0.00');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'start_value');
    }
}
