<?php

use yii\db\Schema;
use yii\db\Migration;

class m141127_214935_create_company_table extends Migration
{
	public $tableName = 'company';

    public function up()
    {
	    $this->createTable($this->tableName, [
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL',
			    'photo_id' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'critical_info_price' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'market_info_price' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'emails' => Schema::TYPE_TEXT. ' NOT NULL',
			    'phones' => Schema::TYPE_TEXT. ' NOT NULL',
			    'site' => Schema::TYPE_STRING. ' NOT NULL',
			    'messengers' => Schema::TYPE_TEXT. ' NOT NULL',
			    'address' => Schema::TYPE_STRING. ' NOT NULL',
			    'juristic_address' => Schema::TYPE_STRING. ' NOT NULL',
			    'bank_requisites' => Schema::TYPE_STRING. ' NOT NULL',
			    'comment' => Schema::TYPE_TEXT. ' NOT NULL',
			    'project_id' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'employee_id' => Schema::TYPE_INTEGER. ' NOT NULL',
		    ],
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );

	    $this->addForeignKey('fk_company_project_id_to_user_table_id', $this->tableName, 'project_id', 'project', 'id', 'CASCADE');

    }

    public function down()
    {
	    $this->dropTable($this->tableName);
    }
}
