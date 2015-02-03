<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150126_191638_add_attack_group_table*/
class m150126_191638_add_attack_group_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%attack_group}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Название"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
