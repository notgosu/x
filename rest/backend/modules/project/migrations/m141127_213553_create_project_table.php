<?php

use yii\db\Schema;
use yii\db\Migration;

class m141127_213553_create_project_table extends Migration
{
	public $tableName = 'project';

    public function safeUp()
    {
		$this->createTable($this->tableName, [
				'id' => 'pk',
				'name' => Schema::TYPE_STRING. ' NOT NULL',
				'user_id' => Schema::TYPE_INTEGER. ' NOT NULL',
				'logo_id' => Schema::TYPE_INTEGER. ' NOT NULL',
				'short_info' => Schema::TYPE_TEXT . ' NOT NULL',
				'show_in_sidebar' => Schema::TYPE_SMALLINT . ' NOT NULL',
				'position' => Schema::TYPE_INTEGER. ' NOT NULL DEFAULT 0',
			]);

	    $this->addForeignKey('fk_project_user_id_to_user_table_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
