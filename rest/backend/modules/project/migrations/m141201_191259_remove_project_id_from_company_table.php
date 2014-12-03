<?php

use yii\db\Schema;
use yii\db\Migration;

class m141201_191259_remove_project_id_from_company_table extends Migration
{
	public $tableName = 'company';
	public $tableName2 = 'project';

    public function safeUp()
    {
	    $this->db->createCommand('SET FOREIGN_KEY_CHECKS=0;')->execute();
	    $this->dropForeignKey('fk_company_project_id_to_user_table_id', $this->tableName);
		$this->dropColumn($this->tableName, 'project_id');

	    $this->addColumn($this->tableName2, 'company_id', Schema::TYPE_INTEGER.  ' NOT NULL');

	    $this->addForeignKey(
		    'fk_project_table_company_id_to_company_table',
	        $this->tableName2,
		    'company_id',
		    $this->tableName,
		    'id',
		    'CASCADE'
	    );
    }

    public function safeDown()
    {
        echo "m141201_191259_remove_project_id_from_company_table cannot be reverted.\n";

        return false;
    }
}
