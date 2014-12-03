<?php

use yii\db\Schema;
use yii\db\Migration;

class m141201_210804_add_category_attribute_value_table extends Migration
{
	public $tableName = 'company_category_attribute_value';

    public function safeUp()
    {
	    $this->createTable($this->tableName, [
			    'id' => 'pk',
			    'company_id' => Schema::TYPE_INTEGER.' NOT NULL',
			    'category_id' => Schema::TYPE_INTEGER.' NOT NULL',
			    'attribute_id' => Schema::TYPE_INTEGER.' NOT NULL',
		    ],
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );


	    $this->addForeignKey(
		    'fk_cat_attr_val_company_id_to_company_table',
		    $this->tableName,
		    'company_id',
		    'company',
		    'id',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk_cat_attr_val_category_id_to_company_cat_table',
		    $this->tableName,
		    'category_id',
		    'company_attribute_category',
		    'id',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk_cat_attr_val_attr_id_to_company_attr_table',
		    $this->tableName,
		    'attribute_id',
		    'company_attribute',
		    'id',
		    'CASCADE'
	    );

    }

    public function safeDown()
    {
       $this->dropTable($this->tableName);
    }
}
