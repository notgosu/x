<?php

use yii\db\Schema;
use yii\db\Migration;

class m141216_171934_update_fk_behavior extends Migration
{
    public function safeUp()
    {
		$this->dropForeignKey('fk_project_table_company_id_to_company_table', 'project');

	    $this->addForeignKey(
		    'fk_project_table_company_id_to_company_table',
		    'project',
		    'company_id',
		    'company',
		    'id',
		    'RESTRICT'
	    );

	    $this->dropForeignKey('fk_company_employee_id_to_employee_table', 'company');
	    $this->addForeignKey(
		    'fk_company_employee_id_to_employee_table',
		    'company',
		    'employee_id',
		    'employee',
		    'id',
		    'RESTRICT'
	    );

	    $this->dropForeignKey('fk_employee_company_id_to_company_table', 'employee');
	    $this->addForeignKey(
		    'fk_employee_company_id_to_company_table',
		    'employee',
		    'company_id',
		    'company',
		    'id',
		    'RESTRICT'
	    );

	    $this->dropForeignKey('fk_employee_psycho_type_id_to_employee_psycho_table', 'employee');
	    $this->addForeignKey(
		    'fk_employee_psycho_type_id_to_employee_psycho_table',
		    'employee',
		    'psycho_type_id',
		    'employee_psycho_type',
		    'id',
		    'RESTRICT'
	    );

	    $this->dropForeignKey('fk_object_company_id_to_company_table', 'object');
	    $this->addForeignKey(
		    'fk_object_company_id_to_company_table',
		    'object',
		    'company_id',
		    'company',
		    'id',
		    'RESTRICT'
	    );

	    $this->dropForeignKey('fk_object_object_type_id_to_object_type_table', 'object');
	    $this->addForeignKey(
		    'fk_object_object_type_id_to_object_type_table',
		    'object',
		    'object_type_id',
		    'object_type',
		    'id',
		    'RESTRICT'
	    );

	    //fk_att_object_id_to_object_type_table
	    $this->dropForeignKey('fk_att_object_id_to_object_type_table', 'attack');
	    $this->addForeignKey(
		    'fk_att_object_type_id_to_object_type_table',
		    'attack',
		    'object_type_id',
		    'object_type',
		    'id',
		    'RESTRICT'
	    );

    }

    public function safeDown()
    {
        return true;
    }
}
