<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150122_204533_change_decimals_signature*/
class m150122_204533_change_decimals_signature extends Migration
{
    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
       $this->alterColumn('{{%attack}}', 'tech_parameter', Schema::TYPE_DECIMAL. '(12,8) NOT NULL COMMENT "Технічний параметр"');
       $this->alterColumn('{{%attack_category_value}}', 'value', Schema::TYPE_DECIMAL. '(12,8) NOT NULL COMMENT "Значення"');
       $this->alterColumn('{{%company_attribute}}', 'value', Schema::TYPE_DECIMAL. '(13,8) NOT NULL');
       $this->alterColumn('{{%employee_access_type}}', 'value', Schema::TYPE_DECIMAL. '(12,8) NOT NULL');
       $this->alterColumn('{{%employee_psycho_type}}', 'value', Schema::TYPE_DECIMAL. '(12,8) NOT NULL');
       $this->alterColumn('{{%employee}}', 'motivation', Schema::TYPE_DECIMAL. '(12,8) NOT NULL');
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        return true;
    }
}
