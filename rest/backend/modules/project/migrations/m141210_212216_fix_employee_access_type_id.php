<?php

use yii\db\Schema;
use yii\db\Migration;

class m141210_212216_fix_employee_access_type_id extends Migration
{
	public $tableName = 'object_employee_params';

    public function safeUp()
    {
		$this->alterColumn($this->tableName, 'access_type_id',  Schema::TYPE_INTEGER.' DEFAULT NULL COMMENT "Тип доступу"');
    }

    public function safeDown()
    {
       return true;
    }
}
