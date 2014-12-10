<?php

use yii\db\Schema;
use yii\db\Migration;

class m141210_204232_add_name_to_user_table extends Migration
{
	public $tableName = 'user';

    public function safeUp()
    {
		$this->addColumn($this->tableName, 'name', Schema::TYPE_STRING.' NOT NULL');
    }

    public function safeDown()
    {
	    $this->dropColumn($this->tableName, 'name');
    }
}
