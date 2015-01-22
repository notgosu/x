<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150122_192421_change_fk_for_company*/
class m150122_192421_change_fk_for_company extends Migration
{

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->dropForeignKey('fk_project_table_company_id_to_company_table', 'project');

        $this->addForeignKey(
            'fk_project_table_company_id_to_company_table',
            'project',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        $this->dropForeignKey('fk_company_employee_id_to_employee_table', 'company');
        $this->addForeignKey(
            'fk_company_employee_id_to_employee_table',
            'company',
            'employee_id',
            'employee',
            'id',
            'CASCADE'
        );

        $this->dropForeignKey('fk_employee_company_id_to_company_table', 'employee');
        $this->addForeignKey(
            'fk_employee_company_id_to_company_table',
            'employee',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );

        $this->dropForeignKey('fk_object_company_id_to_company_table', 'object');
        $this->addForeignKey(
            'fk_object_company_id_to_company_table',
            'object',
            'company_id',
            'company',
            'id',
            'CASCADE'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        return true;
    }
}
