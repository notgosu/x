<?php

use yii\db\Schema;
use yii\db\Migration;

class m141204_210618_add_atack_tables extends Migration
{
	public $tableName = 'attack';

	public function safeUp()
	{
		$this->createTable(
			$this->tableName,
			[
				'id' => 'pk',
				'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Назва"',
				'object_type_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Тип об`єкту"',
				'attack_sum' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Витрати на атаку"',
				'tech_parameter' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL COMMENT "Технічний параметр"',

			],
			'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
		);

		$this->addForeignKey(
			'fk_att_object_id_to_object_type_table',
			$this->tableName,
			'object_type_id',
			'object_type',
			'id',
			'CASCADE'
		);


	}


	public function safeDown()
	{
		$this->dropTable($this->tableName);
	}
}
