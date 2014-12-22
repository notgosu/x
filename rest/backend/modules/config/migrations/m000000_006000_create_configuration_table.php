<?php
use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m000000_006000_create_configuration_table
 */
class m000000_006000_create_configuration_table extends Migration
{
	/**
	 * migration related table name
	 */
	public $tableName = 'configuration';

	/**
	 * commands will be executed in transaction
	 */
	public function up()
	{
		$this->createTable(
			$this->tableName,
			array(
				'id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',

				'config_key' => Schema::TYPE_STRING.'(100) NOT NULL',

				'value' => Schema::TYPE_TEXT.' NULL DEFAULT NULL',
				'description' => Schema::TYPE_STRING.'(250) NULL DEFAULT NULL',
				'type' => Schema::TYPE_SMALLINT.'(1) UNSIGNED NULL DEFAULT NULL',

				'preload' => Schema::TYPE_SMALLINT.'(1) UNSIGNED NOT NULL DEFAULT 0',

				'created' => Schema::TYPE_DATETIME.' NOT NULL',
				'modified' => Schema::TYPE_DATETIME.' NOT NULL',

				'UNIQUE KEY (config_key)',
			),
			'ENGINE=InnoDB DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci'
		);


        $date = date('Y-m-d H:i:s');
        $this->batchInsert($this->tableName, ['config_key', 'value', 'type', 'preload', 'created', 'modified'], [
                [
                    'CCmin',
                    0.15,
                    1,
                    1,
                    $date,
                    $date,
                ],
                [
                    'CCmax',
                    0.89,
                    1,
                    1,
                    $date,
                    $date,
                ],
                [
                    'Wcrit',
                    0.3,
                    1,
                    1,
                    $date,
                    $date,
                ],
                [
                    'KTcrit',
                    0.35,
                    1,
                    1,
                    $date,
                    $date,
                ],
                [
                    'Acrit',
                    0.25,
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
	public function down()
	{
		$this->dropTable($this->tableName);
	}
}
