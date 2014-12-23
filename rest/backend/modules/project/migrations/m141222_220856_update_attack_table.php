<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m141222_220856_update_attack_table*/
class m141222_220856_update_attack_table extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%attack}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'attack_sum');
        $this->addColumn($this->tableName, 'access_type_id', Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Тип доступу"');

        $access = \backend\modules\project\models\EmployeeAccessType::find()->one();

        if ($access){
            \backend\modules\project\models\Attack::updateAll([
                    'access_type_id' => $access->id
                ]);
        }

        $this->addForeignKey(
            'fk_attack_access_type_id_to_employee_access',
            $this->tableName,
            'access_type_id',
            'employee_access_type',
            'id',
            'RESTRICT'
        );
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'access_type_id');
        $this->addColumn($this->tableName, 'attack_sum', Schema::TYPE_INTEGER. ' NOT NULL COMMENT "Витрати на атаку"');
    }
}
