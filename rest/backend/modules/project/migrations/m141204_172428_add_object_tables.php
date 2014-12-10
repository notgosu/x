<?php

use yii\db\Schema;
use yii\db\Migration;

class m141204_172428_add_object_tables extends Migration
{
	public $tableName = 'object_type';

	public $tableName2 = 'object';

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
				'company_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Компанія"',
				'object_type_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Тип об\'єкту"',
				'info_amount' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Загальний обєм інформації (від 1 до 100)"',
			],
			'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
		);

		$this->addForeignKey(
			'fk_object_company_id_to_company_table',
			$this->tableName2,
			'company_id',
			'company',
			'id',
			'CASCADE'
		);

		$this->addForeignKey(
			'fk_object_object_type_id_to_object_type_table',
			$this->tableName2,
			'object_type_id',
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
