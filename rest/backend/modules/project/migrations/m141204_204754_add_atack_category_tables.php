<?php

use yii\db\Schema;
use yii\db\Migration;

class m141204_204754_add_atack_category_tables extends Migration
{
	public $tableName = 'attack_category';
	public $tableName2 = 'attack_category_value';

    public function safeUp()
    {
	    $this->createTable(
		    $this->tableName,
		    [
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Назва"',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Порядок" DEFAULT 0',

		    ],
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );

	    $this->createTable(
		    $this->tableName2,
		    [
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Назва"',
			    'category_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Категорія"',
			    'value' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL COMMENT "Значення"',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Порядок" DEFAULT 0',

		    ],
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );

	    $this->addForeignKey(
		    'fk_att_cat_val_cat_id_to_att_category_table',
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
