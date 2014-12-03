<?php

use yii\db\Schema;
use yii\db\Migration;

class m141202_194731_add_employee_table extends Migration
{
	public $tableName = 'employee_psycho_type';
	public $tableName2 = 'employee';
	public $tableName3 = 'employee_access_type';

    public function safeUp()
    {
	    $this->createTable($this->tableName, [
		        'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Назва"',
			    'value' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL COMMENT "Значення"',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Порядок" DEFAULT 0',
		    ]
		    ,
		    'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
	    );

		$this->createTable($this->tableName2, [
				'id' => 'pk',
				'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "ПIБ"',
				'company_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Компанія"',
				'post' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Посада"',
				'psycho_type_id' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Психотип"',
				'motivation' => Schema::TYPE_DECIMAL. '(6,2) NOT NULL COMMENT "Мотивація"',
				'addition_resources' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Додаткові ресурси"',
				'emails' => Schema::TYPE_TEXT. ' NOT NULL COMMENT "E-mails"',
				'phones' => Schema::TYPE_TEXT. ' NOT NULL COMMENT "Телефон(и)"',
				'site' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Сайт"',
				'messengers' => Schema::TYPE_TEXT. ' NOT NULL COMMENT "Месенджер"',
				'address' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Контактна адреса"',
				'comment' => Schema::TYPE_TEXT. ' NOT NULL COMMENT "Коментарі"',

			],
			'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB'
		);

	    $this->createTable($this->tableName3,[
			    'id' => 'pk',
			    'name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Назва"',
			    'position' => Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Порядок" DEFAULT 0',
		    ]
	    );

	    $this->addForeignKey(
		    'fk_employee_company_id_to_company_table',
		    $this->tableName2,
		    'company_id',
		    'company',
		    'id',
		    'CASCADE'
	    );
	    $this->addForeignKey(
		    'fk_employee_psycho_type_id_to_employee_psycho_table',
		    $this->tableName2,
		    'psycho_type_id',
		    $this->tableName,
		    'id',
		    'CASCADE'
	    );
	    $this->addForeignKey(
		    'fk_company_employee_id_to_employee_table',
		    'company',
		    'employee_id',
		    $this->tableName2,
		    'id',
		    'CASCADE'
	    );
    }

    public function safeDown()
    {
       $this->dropTable($this->tableName3);
       $this->dropTable($this->tableName2);
	    $this->dropTable($this->tableName);

    }
}
