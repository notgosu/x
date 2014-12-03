<?php

use yii\db\Schema;
use yii\db\Migration;

class m141127_214947_create_company_attributes_table extends Migration
{
	public $tableName = 'company_attribute_category';
	public $tableName2 = 'company_attribute';

    public function safeUp()
    {
	    $this->createTable($this->tableName, [
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL DEFAULT 0',
		    ]);

	    $this->createTable($this->tableName2, [
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL',
			    'category_id' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'value' => Schema::TYPE_DECIMAL. '(7,2) NOT NULL',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL DEFAULT 0',
		    ]);

	    $this->addForeignKey(
		    'fk_comp_attr_company_id_to_comp_attr_cat_table_id',
		    $this->tableName2,
		    'category_id',
		    $this->tableName,
		    'id',
		    'CASCADE'
	    );

    }

    public function safeDown()
    {
        $this->dropTable($this->tableName2);
        $this->dropTable($this->tableName);
    }
}
