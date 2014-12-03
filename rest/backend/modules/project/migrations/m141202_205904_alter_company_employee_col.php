<?php

use yii\db\Schema;
use yii\db\Migration;

class m141202_205904_alter_company_employee_col extends Migration
{
	public $tableName = 'company';

    public function up()
    {
		$this->alterColumn($this->tableName, 'employee_id', Schema::TYPE_INTEGER. ' DEFAULT NULL');
    }

    public function down()
    {
        echo "m141202_205904_alter_company_employee_col cannot be reverted.\n";

        return false;
    }
}
