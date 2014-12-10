<?php

use yii\db\Schema;
use yii\db\Migration;

class m141207_200328_add_object_relative_tables extends Migration
{
	public $tableName = 'object_employee_params';
	public $tableName2 = 'object_attack_params';

	public function safeUp()
	{
		$this->createTable($this->tableName, [
				'id' => 'pk',
				'object_id' => Schema::TYPE_INTEGER.' DEFAULT NULL COMMENT "Об\'єкт"',
				'employee_id' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Співробітник"',
				'access_type_id' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Тип доступу"',
				'is_active' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Статус"',
				'temp_sign' => Schema::TYPE_STRING.' DEFAULT NULL'
			],
			'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
		);

		$this->createTable($this->tableName2, [
				'id' => 'pk',
				'object_id' => Schema::TYPE_INTEGER.' DEFAULT NULL COMMENT "Об\'єкт"',
				'attack_id' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Атака"',
				'cost' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Вартість"',
				'is_active' => Schema::TYPE_INTEGER.' NOT NULL COMMENT "Статус"',
				'temp_sign' => Schema::TYPE_STRING.' DEFAULT NULL'
			],
			'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
		);


		$this->addForeignKey(
			'fk_obj_empl_par_object_id_to_object_table',
			$this->tableName,
			'object_id',
			'object',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'fk_obj_empl_par_access_type_id_to_empl_acc_type_table',
			$this->tableName,
			'access_type_id',
			'employee_access_type',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'fk_obj_empl_par_employee_id_to_employee_table',
			$this->tableName,
			'employee_id',
			'employee',
			'id',
			'CASCADE'
		);

		$this->addForeignKey(
			'fk_obj_att_par_object_id_to_object_table',
			$this->tableName2,
			'object_id',
			'object',
			'id',
			'CASCADE'
		);
		$this->addForeignKey(
			'fk_obj_attack_par_attack_id_to_attack_table',
			$this->tableName2,
			'attack_id',
			'attack',
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
