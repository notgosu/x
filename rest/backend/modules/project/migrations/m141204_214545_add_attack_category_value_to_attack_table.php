<?php

use yii\db\Schema;
use yii\db\Migration;

class m141204_214545_add_attack_category_value_to_attack_table extends Migration
{
	public $tableName = 'attack_category_value_to_attack';

    public function safeUp()
    {
	    $this->createTable($this->tableName, [
			    'id' => 'pk',
			    'attack_id' => Schema::TYPE_INTEGER.' NOT NULL',
			    'attack_category_id' => Schema::TYPE_INTEGER.' NOT NULL',
			    'attack_value_id' => Schema::TYPE_INTEGER.' NOT NULL',
		    ],
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );


	    $this->addForeignKey(
		    'fk_att_cat_val_attack_id_to_attack_table',
		    $this->tableName,
		    'attack_id',
		    'attack',
		    'id',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk_att_cat_val_attack_category_id_to_attack_category_table',
		    $this->tableName,
		    'attack_category_id',
		    'attack_category',
		    'id',
		    'CASCADE'
	    );

	    $this->addForeignKey(
		    'fk_att_cat_val_attack_value_id_to_attack_value_table',
		    $this->tableName,
		    'attack_value_id',
		    'attack_category_value',
		    'id',
		    'CASCADE'
	    );

    }

    public function safeDown()
    {
       $this->dropTable($this->tableName);
    }
}
