<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m141223_200022_add_color_params_to_config*/
class m141223_200022_add_color_params_to_config extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%configuration}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $date = date('Y-m-d H:i:s');
        $this->batchInsert($this->tableName, ['config_key', 'value', 'type', 'preload', 'created', 'modified'], [
                [
                    'GreenValue',
                    0.37,
                    1,
                    1,
                    $date,
                    $date,
                ],
                [
                    'YellowValue',
                    0.64,
                    1,
                    1,
                    $date,
                    $date,
                ],
            ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        return true;
    }
}
