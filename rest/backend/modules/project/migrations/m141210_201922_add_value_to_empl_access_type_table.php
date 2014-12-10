<?php

use yii\db\Schema;
use yii\db\Migration;

class m141210_201922_add_value_to_empl_access_type_table extends Migration
{
	public $tableName = 'employee_access_type';

    public function safeUp()
    {
		$this->addColumn($this->tableName, 'value', Schema::TYPE_DECIMAL.'(6,2) NOT NULL');
    }

    public function safeDown()
    {
	    $this->dropColumn($this->tableName, 'value');
    }
}
