<?php

use yii\db\Schema;
use yii\db\Migration;

class m141118_211452_add_user_prefs_table extends Migration
{
	public $tableName = 'user_prefs';

    public function up()
    {
	    $this->createTable($this->tableName, [
			    'id' => 'pk',
			    'user_id' => Schema::TYPE_INTEGER. ' NOT NULL',
			    'birthday' => Schema::TYPE_DATE. ' NOT NULL',
			    'sex' => Schema::TYPE_SMALLINT. ' NOT NULL DEFAULT 1',
			    'city' => Schema::TYPE_STRING . ' NOT NULL',
			    'company' => Schema::TYPE_STRING . ' NOT NULL',
			    'appointment' => Schema::TYPE_STRING . ' NOT NULL',
			    'short_info' => Schema::TYPE_TEXT . ' NOT NULL',
			    'avatar_id' => Schema::TYPE_INTEGER . ' NOT NULL',
			    'phones' => Schema::TYPE_TEXT . ' NOT NULL',
			    'skype' => Schema::TYPE_STRING . ' NOT NULL',
			    'site' => Schema::TYPE_STRING . ' NOT NULL',
		    ]);

	    $this->addForeignKey('fk_user_prefs_user_id_to_user_table_id', $this->tableName, 'user_id', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
